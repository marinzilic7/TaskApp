<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function addSubtask(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'task_id' => 'required',
        ]);

        $subtask = new Subtask($data);
        $subtask->save();

        return response()->json(['message' => 'Uspješno dodan podzadatak'], 200);
    }

    public function getSubtasks($id)
    {
        $subtasks = Subtask::where('task_id', $id)->get();
        return response()->json($subtasks, 200);
    }
}
