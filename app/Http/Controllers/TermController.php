<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;

/**
 * Term resource representation.
 *
 * @Resource("Terms", uri="/terms")
 * @Versions({"v1"})
 */
class TermController extends Controller
{
    /**
     * Get the current term according to the date.
     *
     * @Get("/current_term")
     * @Response(200, body={"id": 1, "name": "Fall", "year": "2015"})
     */
    public function current_term()
    {
        $timezone = new \DateTimeZone('America/New_York');
        $date = new \DateTime('now', $timezone);

        $month = intval($date->format('m'));
        $year = $date->format('Y');
        $name = ((12 >= $month && $month >= 8) || $month === 1) ? 'Fall' : 'Spring';

        $term = Term::where(['name' => $name, 'year' => $year])->first();

        return response()->json($term);
    }

    /**
     * Get a listing of the term.
     *
     * @Get("/")
     * @Response(200, body={{"id": 1, "name": "Fall", "year": "2015"},
     *                      {"id": 2, "name": "Fall", "year": "2015"}})
     * @return Response
     */
    public function index()
    {
        $terms = Term::all();

        return response()->json($terms);
    }

    /**
     * Display the specified term.
     *
     * @Get("/{id}")
     * @Transaction(
     *     @Response(200, body={"id": 1, "name": "Fall", "year": "2015"}),
     *     @Response(404, body={"error": "not found"})
     * )
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $term = Term::findOrFail($id);

            return response()->json($term);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse(
                ['error' => 'not found'], Response::HTTP_NOT_FOUND
            );
        }
    }
}
