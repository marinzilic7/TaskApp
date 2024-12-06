<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function addGroup(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
        ]);

        $data['user_id'] = auth()->id();

        $group = new Group($data);
        $group->save();

        return response()->json(['message' => 'Uspješno dodana grupa'], 200);

    }

    public function getGroup()
    {
        $user_id = auth()->id();
        $groups = Group::where('user_id', $user_id)->get();
        return response()->json($groups, 200);
    }

    public function deleteGroup($id)
    {
        $group = Group::find($id);
        $group->delete();
        return response()->json(['message' => 'Uspješno obrisana grupa'], 200);
    }

    public function getGroupData($id)
    {
        $group = Group::find($id);
        return response()->json($group, 200);

    }

    public function getGroupsWithTaskCount()
    {
        try {
            $groups = Group::withCount('subtasks')->get(); // Dohvati grupe s brojem zadataka
            return response()->json($groups);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch groups'], 500);
        }
    }


}
