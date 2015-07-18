<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;

class TermController extends Controller
{
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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $terms = Term::all();

        return response()->json($terms);
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
            $term = Term::findOrFail($id);

            $response = new JsonResponse($term);
        } catch (ModelNotFoundException $e) {
            $response = new JsonResponse(['error' => 'not found'], 404);
        } finally {
            return $response;
        }
    }
}
