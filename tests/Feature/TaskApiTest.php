<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker; // Not needed anymore
use Tests\TestCase;
use Tests\Utils\TestData; // Import the new TestData utility class

class TaskApiTest extends TestCase
{
    use RefreshDatabase; // Resets the database before each test
    // use WithFaker;      // REMOVIDO: Não precisamos mais do Faker

    protected User $user1;
    protected User $user2;
    protected array $tasksUser1;
    protected array $tasksUser2;

    // This method runs before each test method
    protected function setUp(): void
    {
        parent::setUp();

        TestData::createTestTask();
        $this->tasksUser1[] = TestData::createTestTask($this->user1, ['title' => 'Tarefa do Pedro 1']);
        $this->tasksUser1[] = TestData::createTestTask($this->user1, ['title' => 'Tarefa do Pedro 2']);
        $this->tasksUser2[] = TestData::createTestTask($this->user2, ['title' => 'Tarefa da Ana 1']);
        $this->tasksUser2[] = TestData::createTestTask($this->user2, ['title' => 'Tarefa da Ana 2']);
    }

 
    public function test_can_get_all_tasks(): void
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200) 
                 ->assertJsonCount(4) 
                 ->assertJsonFragment(['title' => 'Tarefa do Pedro 1']) // Check if a specific task title is present
                 ->assertJsonFragment(['title' => 'Tarefa da Ana 1']);
    }


    public function test_can_get_tasks_by_specific_user_id(): void
    {
        $responseUser1 = $this->getJson('/api/users/' . $this->user1->id . '/tasks');
        $responseUser1->assertStatus(200)
                      ->assertJsonCount(2) // Expect 2 tasks for user1
                      ->assertJsonFragment(['title' => 'Tarefa do Pedro 1'])
                      ->assertJsonFragment(['title' => 'Tarefa do Pedro 2'])
                      ->assertJsonMissing(['title' => 'Tarefa da Ana 1']); // Ensure user2's task is not there

        $responseUser2 = $this->getJson('/api/users/' . $this->user2->id . '/tasks');
        $responseUser2->assertStatus(200)
                      ->assertJsonCount(2) 
                      ->assertJsonFragment(['title' => 'Tarefa da Ana 1'])
                      ->assertJsonFragment(['title' => 'Tarefa da Ana 2'])
                      ->assertJsonMissing(['title' => 'Tarefa do Pedro 1']); // Ensure user1's task is not there
    }

    /**
     * Test deleting a task (DELETE /api/tasks/{taskId}).
     * @return void
     */
    public function test_can_delete_a_task(): void
    {
        $taskToDeleteId = $this->tasksUser1[0]->id; // Get the ID of a task to delete

        $response = $this->deleteJson('/api/tasks/' . $taskToDeleteId);

        $response->assertStatus(204); // Expect HTTP 204 No Content for successful deletion

        // Verify that the task is no longer in the database
        $this->assertDatabaseMissing('tasks', ['id' => $taskToDeleteId]);

        // Verify that the other tasks are still in the database
        $this->assertDatabaseHas('tasks', ['id' => $this->tasksUser1[1]->id]);
        $this->assertDatabaseHas('tasks', ['id' => $this->tasksUser2[0]->id]);
        $this->assertDatabaseHas('tasks', ['id' => $this->tasksUser2[1]->id]); // Ensure all remaining tasks exist
    }

    /**
     * Test getting tasks for a non-existent user (GET /api/users/{nonExistentUserId}/tasks).
     * Should return HTTP 404 Not Found.
     * @return void
     */
    public function test_get_tasks_by_non_existent_user_id_returns_404(): void
    {
        $nonExistentUserId = 9999; // An ID that definitely won't exist

        $response = $this->getJson('/api/users/' . $nonExistentUserId . '/tasks');

        $response->assertStatus(404) // Expect HTTP 404 Not Found
                 ->assertJson(['message' => 'User not found.']); // Check the error message from TaskService
    }
}