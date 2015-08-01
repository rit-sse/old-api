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
     * Get the current term.
     *
     * This method returns the instance that represents the current term by
     * calculating the season based on the current date. It then uses that
     * calculation to find the stored instance.
     *
     * @Get("/current_term")
     * @Response(200, body={"id": 1, "name": "Fall", "year": "2015"})
     */
    public function current_term()
    {
        $timezone = new \DateTimeZone('America/New_York');
        $date = (new \DateTime('now', $timezone))->format('Y-m-d');

        $term = Term::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();

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
    public function index(Request $request)
    {
        $this->validate($request, [
            'date' => 'date',
        ]);

        $queryParameters = array_filter(
            $request->only(['date', 'name'])
        );

        $terms = Term::query();

        if (array_key_exists('date', $queryParameters)) {
            $timezone = new \DateTimeZone('America/New_York');
            $date = (new \DateTime('now', $timezone))->format('Y-m-d');
            $terms->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date);
        }

        if (array_key_exists('name', $queryParameters)) {
            $terms = $terms->get()->filter(function($term) use($queryParameters) {
                return strpos($term->name, $queryParameters['name']) !== false;
            });
        } else {
            $terms = $terms->get();
        }

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
