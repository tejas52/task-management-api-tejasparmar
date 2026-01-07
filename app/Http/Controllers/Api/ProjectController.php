<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    // LIST projects (only authenticated user's projects)
    public function index(Request $request)
    {
        $projects = Project::where('owner', $request->user()->id)->get();

        return response()->json([
            'status' => true,
            'data' => $projects
        ]);
    }

    //create a new project
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'owner' => $request->user()->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    // SHOW single project
    public function show(Request $request, $id)
    {
        $project = Project::where('id', $id)
            ->where('owner', $request->user()->id)
            ->first();

        if (!$project) {
            return response()->json([
                'status' => false,
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $project
        ]);
    }

    // UPDATE project
    public function update(Request $request, $id)
    {
        $project = Project::where('id', $id)
            ->where('owner', $request->user()->id)
            ->first();

        if (!$project) {
            return response()->json([
                'status' => false,
                'message' => 'Project not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,in_progress,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $project->update($request->only('name', 'description', 'status'));

        return response()->json([
            'status' => true,
            'message' => 'Project updated successfully',
            'data' => $project
        ]);
    }

    // DELETE project
   public function destroy(Request $request, $id)
    {
        $project = Project::where('id', $id)
            ->where('owner', $request->user()->id)
            ->first();

        if (!$project) {
            return response()->json([
                'status' => false,
                'message' => 'Project not found'
            ], 404);
        }

        $project->delete();

        return response()->json([
            'status' => true,
            'message' => 'Project moved to trash'
        ]);
    }




}
