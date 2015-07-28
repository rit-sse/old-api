<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tip;

/**
 * Tip resource representation.
 *
 * This endpoint controls all aspects of creating, updating, fetching, and
 * deleting instances of the model.
 *
 * @Resource("Tips", uri="/tips")
 * @Versions({"v1"})
 */
class TipController extends Controller
{
    /**
     * Display a listing of the tip.
     *
     * @Get("/")
     * @Response(200, body={"id": 1, "body": "The lab is in GOL-1670.",
     *                      "author_url": "/members/1", "edited_by": "",
     *                      "url": "/tips/1"})
     * @return Response
     */
    public function index()
    {
        $tips = Tip::query();

        return response()->json($tips->paginate());
    }

    /**
     * Store a newly created tip in storage.
     *
     * The 'author' field will be set to the current authenticated user id.
     *
     * @Post("/")
     * @Transaction(
     *     @Request({"body": "Reviews are on Sundays!"}),
     *     @Response(201, body={"id": 1, "body": "The lab is in GOL-1670.",
     *                          "author_url": "/members/1", "edited_by": "",
     *                          "url": "/tips/1"}),
     *     @Response(422, body={"body": {"The body field is required."}})
     * )
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['body' => 'required']);

        $tip = new Tip();

        $tip->body = $request->input('body');
        $tip->created_by = $request->member->id;

        $tip->save();

        return new JsonResponse($tip, Response::HTTP_CREATED);
    }

    /**
     * Display the specified tip.
     *
     * @Get("/{id}")
     * @Response(200, body={"id": 1, "body": "The lab is in GOL-1670.",
     *                      "author_url": "/members/1", "url": "/tips/1"})
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $tip = Tip::findOrFail($id);

            return response()->json($tip);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse(
                ['error' => 'not found'], Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Update the specified tip in storage.
     *
     * The 'edited_by' field will be updated with the currently authenticated
     * user id.
     *
     * @Put("/{id}")
     * @Transaction(
     *     @Request({"body": "The lab is in GOL-1650."}),
     *     @Response(200, body={}),
     *     @Response(422, body={"body": {"The body field is required."}})
     * )
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['body' => 'required']);

        $tip = Tip::findOrFail($id);

        $tip->body = $request->input('body');
        $tip->updated_by = $request->member->id;

        $tip->save();

        return response()->json($tip);
    }

    /**
     * Remove the specified tip from storage.
     *
     * Marks the tip as removed from the application (uses soft deletes).
     *
     * @Delete("/{id}")
     * @Response(204)
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Tip::destroy($id);

        return response('', Response::HTTP_NO_CONTENT);
    }
}
