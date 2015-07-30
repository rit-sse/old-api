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
 * Group group representation.
 *
 * @Resource("Groups", uri="/groups")
 * @Versions({"v1"})
 */
class GroupController extends Controller
{
    /**
     * Show all groups.
     *
     * Get a JSON representation of all the registered groups.
     *
     * @Get("/")
     * @Response(200, body={{"id": 1, "name": "Website Committee",
     *                       "officer_id": 1, "officer_url": "/officers/1",
     *                       "url": "/groups/1"}})
     * @return Response
     */
    public function index(Request $request)
    {
        $queryParameters = array_filter(
            $request->only(['name'])
        );

        $groups = Group::query();

        if (array_key_exists('name', $queryParameters)) {
            $groups->where('name', 'like', $queryParameters['name']);
        }

        return response()->json($groups->paginate());
    }

    /**
     * Store a newly created group in storage.
     *
     * @Post("/")
     * @Transaction(
     *     @Request({"name": "Heist Organizers", "officer_id": 1}),
     *     @Response(201, body={{"id": 2, "name": "Heist Organizers",
     *                           "officer_id": 1, "officer_url": "/officers/1",
     *                           "url": "/groups/2"}}),
     *     @Response(422, body={"name": {"The name is already taken."},
     *                          "officer_id": {"The officer id does not exist."}})
     * )
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:groups,name',
            'officer_id' => 'required|exists:officers,id',
        ]);

        $group = new Group();

        $group->name = $request->input('name');
        $group->officer_id = $request->input('officer_id');

        $group->save();

        return new JsonResponse($group, Response::HTTP_CREATED);
    }

    /**
     * Display the specified group.
     *
     * @Get("/{id}")
     * @Transaction(
     *     @Response(200, body={{"id": 1, "name": "Website Committee",
     *                           "officer_id": 1, "officer_url": "/officers/1",
     *                           "url": "/groups/1"}}),
     *     @Response(404, body={"error": "not found"})
     * )
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $group = Group::with('officer')->findOrFail($id);

            return response()->json($group);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse(
                ['error' => 'not found'], Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Update the specified group in storage.
     *
     * @Put("/{id}")
     * @Transaction(
     *     @Request({"name": "Heist Committee", "officer_id": 2}),
     *     @Response(200, body={{"id": 1, "name": "Website Committee",
     *                           "officer_id": 1, "officer_url": "/officers/1",
     *                           "url": "/groups/1"}}),
     *     @Response(422, body={"name": {"That name is already taken."},
     *                          "officer_id": {"The officer id does not exist."}})
     * )
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'unique:groups,name',
            'officer_id' => 'required|exists:officers,id',
        ]);

        $group = Group::findOrFail($id);

        $group->name = $request->input('name', $group->name);
        $group->officer_id = $request->input('officer_id', $group->officer_id);

        $group->save();

        return response()->json($group);
    }

    /**
     * Remove the specified group from storage.
     *
     * @Delete("/{id}")
     * @Response(204)
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Group::destroy($id);

        return response('', Response::HTTP_NO_CONTENT);
    }
}
