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
            'group_id' => 'required',
        ]);

        $subtask = new Subtask($data);
        $subtask->save();

        return response()->json(['message' => 'Uspješno dodan podzadatak'], 200);
    }

    public function getSubtasks($id)
    {
        $subtasks = Subtask::where('group_id', $id)->get();
        return response()->json($subtasks, 200);
    }

    public function deleteSubtask($id)
    {
        $subtask = Subtask::find($id);
        $subtask->completed = true;
        $subtask->save();
        return response()->json(['message' => 'Uspješno obrisan podzadatak'], 200);
    }

    public function addDeadlineSubTask(Request $request, $id)
    {
        $data = $request->validate([
            'deadline' => 'required',
        ]);

        $subtask = Subtask::find($id);
        $subtask->deadline = $data['deadline'];
        $subtask->save();

        return response()->json(['message' => 'Uspješno dodan rok podzadatka'], 200);
    }

    public function importantSubtask($id)
    {
        $subtask = Subtask::find($id);
        $subtask->isImportant = !$subtask->isImportant;
        $subtask->save();
        return response()->json(['message' => 'Uspješno promijenjen status podzadatka'], 200);
    }

    public function getImportantSubtasks($id)
    {
        $subtasks = Subtask::where('group_id', $id)->where('isImportant', true)->get();
        return response()->json($subtasks, 200);
    }

    public function getCompletedSubtasks($id)
    {
        $subtasks = Subtask::where('group_id', $id)->where('completed', true)->get();
        return response()->json($subtasks, 200);
    }


    public function deleteCompletedSubtask(Request $request, $taskId)
    {
        // Validacija za groupId
        $request->validate([
            'groupId' => 'required|exists:groups,id', // Provjera da li grupa postoji
        ]);

        // Dohvat 'groupId' iz zahtjeva
        $groupId = $request->groupId;

        // Ažuriranje podzadatka tako da postavimo 'completed' na true
        $subtask = Subtask::where('group_id', $groupId)
            ->where('id', $taskId)
            ->where('completed', true) // Provjera da je podzadatak još uvijek nije označen kao završen
            ->first();

        // Ako podzadatak postoji

            $subtask->completed = false;// Postavljanje 'completed' na true
            $subtask->save(); // Spremanje promjena u bazu


    }
}
