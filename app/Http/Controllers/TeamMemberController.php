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
        // Dohvati članove tima zajedno s podacima o korisnicima (npr. ime)
        $members = TeamMember::where('team_id', $teamId)
            ->with('user')  // Pretpostavljamo da imate relaciju 'user' između TeamMember i User modela
            ->get();

        // Vraćamo podatke o članovima tima s korisničkim informacijama
        return response()->json($members);
    }
}
