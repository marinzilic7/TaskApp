<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function addProject(Request $request, $id){

        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'team_id' => '',
        ]);

        $project = new Project();
        $project->name = $data['name'];
        $project->description = $data['description'];
        $project->team_id = $id;
        $project->save();

        return response()->json(['message' => 'Project added successfully']);
    }

    public function getProject($id){
        $project = Project::where('team_id', $id)->get();
    return response()->json($project);

    }
}
