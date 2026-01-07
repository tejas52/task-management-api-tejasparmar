<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * List tasks by project.
     */
    public function index(Request $request, int $projectId)
    {
        try {
            $project = Project::findOrFail($projectId);

            $this->authorizeProject($request, $project);

            return response()->json([
                'status' => true,
                'data'   => $project->tasks,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Project not found',
            ], 404);
        }
    }

    /**
     * Create a task for a project.
     */
    public function store(Request $request, Project $project)
    {
        $this->authorizeProject($request, $project);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'status'      => 'nullable|in:todo,in_progress,done',
            'priority'    => 'nullable|in:low,medium,high',
            'due_date'    => 'nullable|date|after_or_equal:today',
        ]);

        $task = $project->tasks()->create($data);

        return response()->json([
            'status'  => true,
            'message' => 'Task created successfully',
            'data'    => $task,
        ], 201);
    }

    /**
     * Show task details.
     */
    public function show(Request $request, Task $task)
    {
        if ($task->project->owner !== $request->user()->id) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'status' => true,
            'data'   => $task,
        ]);
    }

    /**
     * Update a task.
     */
    public function update(Request $request, Task $task)
    {
        if ($task->project->owner !== $request->user()->id) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $data = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status'      => 'sometimes|required|in:todo,in_progress,done',
            'priority'    => 'sometimes|required|in:low,medium,high',
            'due_date'    => 'sometimes|nullable|date|after_or_equal:today',
        ]);

        $task->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'Task updated successfully',
            'data'    => $task,
        ]);
    }

    /**
     * Soft delete a task.
     */
    public function destroy(Request $request, Task $task)
    {
        if ($task->project->owner !== $request->user()->id) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $task->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Task moved to trash',
        ]);
    }

    /**
     * Restore a soft-deleted task.
     */
    public function restore(Request $request, int $id)
    {
        $task = Task::onlyTrashed()
            ->with('project')
            ->where('id', $id)
            ->firstOrFail();

        if ($task->project->owner !== $request->user()->id) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $task->restore();

        return response()->json([
            'status'  => true,
            'message' => 'Task restored successfully',
        ]);
    }

    /**
     * Permanently delete a task.
     */
    public function forceDelete(Request $request, int $id)
    {
        $task = Task::onlyTrashed()
            ->with('project')
            ->where('id', $id)
            ->firstOrFail();

        if ($task->project->owner !== $request->user()->id) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $task->forceDelete();

        return response()->json([
            'status'  => true,
            'message' => 'Task permanently deleted',
        ]);
    }

    /**
     * Authorize project ownership.
     */
    private function authorizeProject(Request $request, Project $project): void
    {
        if ($project->owner !== $request->user()->id) {
            throw new HttpResponseException(
                response()->json([
                    'status'  => false,
                    'message' => 'You are not authorized to access this project.',
                ], 403)
            );
        }
    }

    /**
     * Authorize task ownership.
     */
    private function authorizeTask(
        Request $request,
        Project $project,
        Task $task
    ): void {
        if (
            $project->owner !== $request->user()->id ||
            $task->project_id !== $project->id
        ) {
            throw new HttpResponseException(
                response()->json([
                    'status'  => false,
                    'message' => 'You are not authorized to access this task.',
                ], 403)
            );
        }
    }
}
