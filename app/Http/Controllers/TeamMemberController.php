<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function addMember(Request $request, $id ){
        $data = $request->validate([
            'user_id' => '',
            'team_id' => '',
        ]);

        $member = new TeamMember();
        $member->user_id = $data['user_id'];
        $member->team_id = $id;
        $member->save();

        return response()->json(['message' => 'Member added successfully']);
    }

    public function getMemberGroup($teamId) {

        $members = TeamMember::where('team_id', $teamId)
            ->with('user')
            ->get();


        return response()->json($members);
    }

    public function getYourTeam(){
        $user = auth()->user();
        $teams = TeamMember::where('user_id', $user->id)
            ->with('team')
            ->get();
        return response()->json($teams);
    }
}
