<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class TodoController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allTask = Todo::get();
        return view('todolist', compact('allTask'));
    }

    /**
     * Add a new task
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $taskName = $request->name;

            if ($taskName == NULL) {
                return response()->json([
                    'message' => 'Task cannot be blank',
                    'status' => 404
                ]);
            }

            // Check if the task name is already there in the database
            $checkTask = Todo::where('name', $request->name)->first();
            if ($checkTask) {
                return response()->json([
                    'message' => 'Task already exist!',
                    'status' => 404
                ]);
            }

            $addTask = new Todo();
            $addTask->name = $request->name;
            $addTask->is_active = 0;
            $addTask->save();

            return response()->json([
                'message' => 'Task added successfully',
                'tasks' => $addTask,
                'status' => 200
            ]);

        } catch(Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }

    /**
     * Add a new task
     *
     * @return \Illuminate\Http\Response
     */
    public function markTaskComplete(Request $request) {
        try {
                $taskId = $request->taskId;
                $taskFind = Todo::where('id', $taskId)->first();

                if ($taskFind) {
                    $taskFind->is_active = 1;
                    $taskFind->save();

                    return response()->json([
                        'message' => 'Task marked completed successfully!',
                        'task' => $taskFind,
                        'status' => 404
                    ]);

                } else {
                    return response()->json([
                        'message' => 'Task not found!',
                        'status' => 404
                    ]);        
                }

        } catch(Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }

    /**
     * Show all tasks
     *
     * @return \Illuminate\Http\Response
     */
    public function showAllTasks(Request $request) {
        $allTask = Todo::get();

        return response()->json([
            'message' => 'All tasks fetched successfully!',
            'tasks' => $allTask,
            'status' => 200
        ]);
    }


    /**
     * Delete a tasks
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteTask(Request $request) {
        try {
            // Find task if exists
            $task = Todo::where('id', $request->taskId);
            if($task) {

                $task->delete();

                return response()->json([
                    'message' => 'Task deleted successfully!',
                    'status' => 200
                ]);

            } else {
                return response()->json([
                    'message' => 'Task not found!',
                    'status' => 404
                ]);    
            }

        } catch(Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }
    
}
