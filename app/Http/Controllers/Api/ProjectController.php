<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Get projects for authenticated user.
     */
    public function index(Request $request)
    {
        $projects = Project::where('owner', $request->user()->id)
            ->latest()
            ->paginate(10); // change 10 as needed

        return response()->json([
            'status' => true,
            'data'   => $projects->items(),
            'meta'   => [
                'current_page' => $projects->currentPage(),
                'last_page'    => $projects->lastPage(),
                'per_page'     => $projects->perPage(),
                'total'        => $projects->total(),
            ],
        ]);
    }


    /**
     * Create a new project with logged-in user as owner.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'status'      => 'required|in:pending,in_progress,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $project = Project::create([
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => $request->status,
            'owner'       => $request->user()->id,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Project created successfully',
            'data'    => $project,
        ], 201);
    }

    /**
     * Show project details by ID (authenticated user only).
     */
    public function show(Request $request, int $id)
    {
        $project = Project::where('id', $id)
            ->where('owner', $request->user()->id)
            ->first();

        if (! $project) {
            return response()->json([
                'status'  => false,
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $project,
        ]);
    }

    /**
     * Update project details by ID (authenticated user only).
     */
    public function update(Request $request, int $id)
    {
        $project = Project::where('id', $id)
            ->where('owner', $request->user()->id)
            ->first();

        if (! $project) {
            return response()->json([
                'status'  => false,
                'message' => 'Project not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status'      => 'sometimes|required|in:pending,in_progress,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $project->update($request->only([
            'name',
            'description',
            'status',
        ]));

        return response()->json([
            'status'  => true,
            'message' => 'Project updated successfully',
            'data'    => $project,
        ]);
    }

    /**
     * Soft delete project (authenticated user only).
     */
    public function destroy(Request $request, int $id)
    {
        $project = Project::where('id', $id)
            ->where('owner', $request->user()->id)
            ->first();

        if (! $project) {
            return response()->json([
                'status'  => false,
                'message' => 'Project not found',
            ], 404);
        }

        $project->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Project moved to trash',
        ]);
    }
}
