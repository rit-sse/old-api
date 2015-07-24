<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Lingo;

/**
 * Controls the collection of lingo.
 *
 * @Resource("Lingo", uri="/lingo")
 * @Versions({"v1"})
 */
class LingoController extends Controller
{
    /**
     * Get the collection of lingo.
     *
     * @Get("/{?phrase}")
     * @Parameter("phrase", type="string", description="The phrase filter.", default="")
     * @Response(200, body={{"id": 1, "phrase": "261", "definition": "...", "url": "/lingo/1"}})
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $queryParameters = array_filter(
            $request->only(['phrase'])
        );

        $lingo = Lingo::query();

        if (array_key_exists('phrase', $queryParameters)) {
            $lingo = $lingo->where(['phrase' => $queryParameters['phrase']]);
        }

        return response()->json($lingo->paginate());
    }

    /**
     * Store a newly created lingo in storage.
     *
     * @Post("/")
     * @Transaction(
     *     @Request({"phrase": "361", "definition": "..."}),
     *     @Response(201, body={"id": 2, "phrase": "361", "definition": "...", "url": "/lingo/2"}),
     *     @Response(422, body={"phrase": {"The phrase has already been taken."}})
     * )
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'phrase' => 'required|unique:lingo,phrase',
            'definition' => 'required'
        ]);

        $lingo = new Lingo();

        $lingo->phrase = $request->input('phrase');
        $lingo->definition = $request->input('definition');

        $lingo->save();

        return new JsonResponse($lingo, Response::HTTP_CREATED);
    }

    /**
     * Display the specified lingo.
     *
     * @Get("/{id}")
     * @Transaction(
     *     @Response(200, body={"id": 1, "phrase": "261", "definition": "...", "url": "/lingo/1"}),
     *     @Response(404, body={"error": "not found"})
     * )
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $lingo = Lingo::findOrFail($id);

            return response()->json($lingo);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse(
                ['error' => 'not found'], Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Update the specified lingo in storage.
     *
     * @Put("/{id}")
     * @Transaction(
     *     @Request({"definition": "new"}),
     *     @Response(200, body={"id": 1, "phrase": "261", "definition": "new", "url": "/lingo/1"}),
     *     @Response(404, body={"error": "not found"})
     * )
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $lingo = Lingo::findOrFail($id);

        $lingo->definition = $request->input('definition', $this->definition);

        $lingo->save();

        return response()->json($lingo);
    }

    /**
     * Remove the specified lingo from storage.
     *
     * @Delete("/{id}")
     * @Response(204)
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Lingo::destroy($id);

        return response('', Response::HTTP_NO_CONTENT);
    }
}
