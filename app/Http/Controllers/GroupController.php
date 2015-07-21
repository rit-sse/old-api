<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Group;
use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @Resource("Groups", uri="/groups")
 */
class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $groups = Group::all();

        return response()->json($groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:groups,name',
            'head_id' => 'required|exists:officers,id',
        ]);

        $group = new Group();

        $group->name = $request->input('name');
        $group->head_id = $request->input('head_id');

        $group->save();

        return response()->json($group);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $group = Group::with('head')->findOrFail($id);

            return response()->json($group);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse(
                ['error' => 'not found'], Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'unique:groups,name',
            'head_id' => 'required|exists:officers,id',
        ]);

        $group = Group::findOrFail($id);

        $group->name = $request->input('name', $group->name);
        $group->head_id = $request->input('head_id', $group->head_id);

        $group->save();

        return response()->json($group);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Group::destroy($id);
    }
}
