<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the Tasks.
     *
     * @return Response
     */
    public function index()
    {
        $query = Task::query();
        return response()->json($query->paginate());
    }

    /**
     * Store a newly created Task in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
            'assignee' => 'exists:member,id'
        ]);

        $task = new Task();

        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->creator_id = $request->member->id;
        $task->assignee_id = $request->input('assignee', $request->member->id);
        $task->completed = false;

        $task->save();
        return new JsonResponse($task, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource if found
     * else, 404
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $task = Task::findOrFail($id);

            return response()->json($task);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse(
                ['error' => 'not found'], Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Update the specified resource in storage if found,
     * else 404
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'completed' => 'boolean',
        ]);

        try {
            $task = Task::findOrFail($id);

            // Assign new valued if present, if not leave defaults
            $task->name = $request->input('name', $task->name);
            $task->description = $request->input('description', $task->description);
            $task->assignee_id = $request->input('assignee', $task->assignee_id);
            $task->completed = $request->input('completed', $task->completed);

            $task->save();

            return response()->json($task);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse(
                ['error' => 'not found'], Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Remove the specified resource from storage if found,
     * else, 404
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            Task::findOrFail($id);
            Task::destroy($id);

            return response('', Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse(
                ['error' => 'not found'], Response::HTTP_NOT_FOUND
            );
        }
    }
}
