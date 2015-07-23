<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Officer;

/**
 * Controls the collection of officers.
 *
 * @Resource("Officers", uri="/officers")
 * @Versions({"v1"})
 */
class OfficerController extends Controller
{
    /**
     * Get the collection of officers.
     *
     * @Get("/{?member,term,title}")
     * @Parameters(
     *     @Parameter("member", description="Member id of an officer.", default=""),
     *     @Parameter("term", description="Term of officer(s).", default=""),
     *     @Parameter("title", description="Title of an officer.", default="")
     * )
     * @Response(200, body={{"id": 1, "member_id": 1, "term_id": 1,
     *                      "title": "President", "position": "president",
     *                      "email": "president@sse.se.rit.edu", "url": "/officers/1"}}),
     * @return Response
     */
    public function index(Request $request)
    {
        $queryParameters = array_filter(
            $request->only(['member', 'term', 'title'])
        );

        $officers = Officer::query();

        if (array_key_exists('member', $queryParameters)) {
            $officers->where('member_id', $queryParameters['member']);
        }

        if (array_key_exists('term', $queryParameters)) {
            $officers->where('term_id', $queryParameters['term']);
        }

        if (array_key_exists('title', $queryParameters)) {
            $officers->where('title', 'like', $queryParameters['title']);
        }

        return response()->json($officers->get());
    }

    /**
     * Store a newly created officer in storage.
     *
     * @Post("/")
     * @Transaction(
     *     @Request({"member_id": 1, "title": "President"}),
     *     @Response(201, body={"id": 1, "member_id": 1, "term_id": 1,
     *                          "title": "President", "position": "president",
     *                          "email": "president@sse.se.rit.edu", "url": "/officers/1"}),
     *     @Response(422, body={"member_id": {"The member id field is required."}})
     * )
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // FIXME: Validate unique title for current term
        $this->validate($request, [
            'member_id' => 'required',
            'title' => 'required',
        ]);

        // FIXME: Replace with internal route to /terms/current_term
        $term_id = 1;

        $officer = Officer::where(
            ['title' => $request->input('title'), 'term_id' => $term_id]
        );

        if ($officer) {
            $officer->delete();
        }

        $officer = new Officer();

        $officer->member_id = $request->input('member_id');
        $officer->title = $request->input('title');
        $officer->term_id = $term_id;

        $officer->save();

        return new JsonResponse($officer, Response::HTTP_CREATED);
    }

    /**
     * Display the specified officer.
     *
     * @Get("/{id}")
     * @Transaction(
     *     @Response(200, body={"id": 1, "member_id": 1, "term_id": 1,
     *                          "title": "President", "position": "president",
     *                          "email": "president@sse.se.rit.edu", "url": "/officers/1"}),
     *     @Response(404, body={"error": "not found"})
     * )
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $officer = Officer::findOrFail($id);

            return response()->json($officer);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse(
                ['error' => 'not found'], Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Remove the specified officer from storage.
     *
     * @Delete("/{id}")
     * @Response(200)
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Officer::destroy($id);

        return response('', Response::HTTP_NO_CONTENT);
    }
}
