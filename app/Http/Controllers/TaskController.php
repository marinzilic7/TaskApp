<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function addTask(Request $request){



        $data = $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'user_id' => '',

        ],

        );

        $user_id = Auth::id();
        $data['user_id'] = $user_id;


        $task = new Task($data);
        $task->create($data);
        return response()->json(['message' => 'Uspješno dodan zadatak'], 200);

    }

    public function getTasks(){

        $user_id = Auth::id();
        $tasks = Task::where('user_id', $user_id)->get();
        return response()->json($tasks, 200);

    }
}
