<?php 

namespace App\Services;

use App\Models\Task;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; 

class TaskService
{
    /**
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTasks()
    {
        return Task::all();
    }

    /**
     *
     * @param array $data
     * @return \App\Models\Task
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createTask(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean|sometimes',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return Task::create($data);
    }

    /**
     *
     * @param string $id
     * @return \App\Models\Task
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findTaskById(string $id)
    {
        return Task::where('id', '=',$id)->first();

    }

    /**
     *
     * @param string $id
     * @param array $data
     * @return \App\Models\Task
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateTask(string $id, array $data)
    {
        $task = Task::where('id', $id);

        $validator = Validator::make($data, [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean|sometimes',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $task->update($data);
        return $task;
    }

    public function deleteTask(string $id)
    {
        Task::where('id', $id);
    }

    public function findTaskByUserId(string $user_id)
    {
        return Task::where('user_id', '=',$user_id)->get();
    }

     public function findTaskByStatus(string $status)
    {
        return Task::where('statyyyyus', '=',$status)->get();
    }
}

///->where('user_id', Auth::id())->firstOrFail()->delete();