<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function addTeam(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);

        $user = auth()->user();
        $team = new Team();
        $team->name = $data['name'];
        $team->user_id = $user->id;
        $team->save();

        return response()->json(['message' => 'Team added successfully']);
    }

    public function getTeams(){
        $user = auth()->user();
        $teams = Team::where('user_id', $user->id)
                 ->with('user') // Uključuje podatke o korisniku
                 ->get();
        return response()->json($teams);
    }

    public function updateTeam($id){
        $data = request()->validate([
            'name' => 'required',
        ]);

        $team = Team::find($id);
        $team->name = $data['name'];
        $team->save();

        return response()->json(['message' => 'Team updated successfully']);
    }

    public function deleteTeam($id){
        $team = Team::find($id);
        $team->delete();

        return response()->json(['message' => 'Team deleted successfully']);
    }

    public function getTeamData($id){

        $team = Team::find($id);

        return response()->json($team);
    }
}
