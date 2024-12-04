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

    public function deleteTask($id){

        $task = Task::find($id);

        $task->completed = true;


        $task->save();
        return response()->json(['message' => 'Uspješno obrisan zadatak'], 200);
    }

    public function importantTask($id){

            $task = Task::find($id);
            $task->isImportant = !$task->isImportant;
            $task->save();
            return response()->json(['message' => 'Uspješno promijenjen status zadatka'], 200);
    }

    public function getImportant(){

        $user_id = Auth::id();
        $tasks = Task::where('user_id', $user_id)->where('isImportant', 1)->get();
        return response()->json($tasks, 200);

    }

    public function deleteImportant(){

            $user_id = Auth::id();
            $tasks = Task::where('user_id', $user_id)->where('isImportant', 1)->update(['completed' => true]);
            return response()->json(['message' => 'Uspješno označeni važni zadaci kao dovršeni'], 200);

    }

    public function addImportant(Request $request){

        $data = $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'user_id' => '',

        ],

        );
        $data['isImportant'] = 1;
        $user_id = Auth::id();
        $data['user_id'] = $user_id;


        $task = new Task($data);
        $task->create($data);
        return response()->json(['message' => 'Uspješno dodan zadatak'], 200);
    }

    public function addDeadline($id, Request $request){

        $data = $request->validate([
            'deadline' => 'required',
        ],

        );

        $task = Task::find($id);
        $task->deadline = $data['deadline'];
        $task->save();
        return response()->json(['message' => 'Uspješno dodan rok zadatku'], 200);


    }


    public function deleteCompleted($id){
        $task = Task::find($id);
        $task->completed = false;
        $task->save();
        return response()->json(['message' => 'Uspješno obrisan zadatak'], 200);
    }

    public function getCompletedTasks(){

            $user_id = Auth::id();
            $tasks = Task::where('user_id', $user_id)->where('completed', 1)->get();
            return response()->json($tasks, 200);
    }
    public function getCompletedImportantTasks(){

        $user_id = Auth::id();
        $tasks = Task::where('user_id', $user_id)->where('completed', 1)->where('isImportant', 1)->get();
        return response()->json($tasks, 200);
}
}
