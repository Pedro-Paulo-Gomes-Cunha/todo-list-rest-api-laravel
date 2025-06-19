<?php

namespace Tests\Utils;

use App\Models\User;
use App\Models\Task;


use App\Services\TaskService;
use App\Services\UsersService;

/**
 * Utility class to create static test data without using Laravel factories.
 * These methods will manually create and save models to the database.
 */
class TestData
{
    private static $userCounter = 0;
    private static $taskCounter = 0;

    /**
     * Creates and saves a User model with predefined data.
     *
     * @param array $overrides Optional array to override default user data.
     * @return User
     */

 
    public static function createTestUser()
    {
        $user2=new User();
        $user2->name='Pedro';
        $user2->email='exempl@exemple.com';
        $user2->password='12345';

        /*self::$userCounter++;
        $userData = [
            'name' => 'User1 ' . self::$userCounter,
            'email' => 'teste_user_' . self::$userCounter . '@example.com', // Unique email for each user
            'password' => bcrypt('password123'), // Static password
        ];*/

        // Merge with any provided overrides
        //$user = new User(array_merge($userData, $overrides));
       // $user->save(); // IMPORTANT: Manually save the user to the database
        User::create($user2);
    }

    /**
     * Creates and saves a Task model with predefined data.
     *
     * @param User|null $user The user to associate the task with. Can be null if user_id is nullable.
     * @param array $overrides Optional array to override default task data.
     * @return Task
     */
    public static function createTestTask(?User $user = null, array $overrides = []): Task
    {
        self::$taskCounter++;
        $taskData = [
            'title' => 'task 1 ' . self::$taskCounter,
            'description' => '.... ' . self::$taskCounter,
            'Status' => 'pedente',
            'user_id' => $user ? $user->id : null, // Associate task with user if provided
        ];

        // Merge with any provided overrides
        $task = new Task(array_merge($taskData, $overrides));
       // $task->save(); */// IMPORTANT: Manually save the task to the database
        Task::create($task);
        return $task;
    }
}