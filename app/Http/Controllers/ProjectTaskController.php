<?php

namespace App\Http\Controllers;

use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectTaskController extends Controller
{
    public function addTaskToProject(Request $request, $id){
        $data = $request->validate([
            'name' => 'required',
            'description' => '',
            'team_id' => '',
            'project_id' => '',
            'member_id' => '',
            'deadline' => '',
            'user_id' => '',
        ]);

        $data['description'] = 'No description';

        $task = new ProjectTask();
        $task->name = $data['name'];
        $task->user_id = auth()->user()->id;
        $task->description = $data['description'];
        $task->team_id = $data['team_id'];
        $task->project_id = $id;

        $task->save();

        return response()->json(['message' => 'Task added successfully']);
    }

    public function getTasksByProject($projectId) {

        $tasks = ProjectTask::where('project_id', $projectId)
                            ->with('user')
                            ->get();

        return response()->json($tasks);
    }

    public function deleteProjectTasks($taskId) {
        //UPDATE CEMO COMPLETED NA TRUE ZA ODREDENI TASK
        $task = ProjectTask::find($taskId);
        $task->completed = true;
        $task->save();

        return response()->json(['message' => 'Tasks deleted successfully']);

    }

    public function assignTaskToMember(Request $request, $projectId) {

        $data = $request->validate([
            'member_id' => 'required',
            'task_id' => 'required',
        ]);


        $task = ProjectTask::where('project_id', $projectId)
                        ->where('id', $data['task_id'])
                        ->first();



        $task->member_id = $data['member_id'];
        $task->save();

        return response()->json(['message' => 'Task assigned successfully']);
    }

    public function addTaskProjectDeadline($id, Request $request){
        $data = $request->validate([
            'deadline' => 'required',
        ]);

        $task = ProjectTask::find($id);
        $task->deadline = $data['deadline'];
        $task->save();

        return response()->json(['message' => 'Task deadline added successfully']);
    }

    public function getTasksByProjectMember($projectId) {

        $userId = Auth::id();

        // Dohvati zadatke dodijeljene korisniku
        $tasks = ProjectTask::where('project_id', $projectId)
                            ->where('member_id', $userId)
                            ->with('user')
                            ->get();

        return response()->json($tasks);
    }


}
