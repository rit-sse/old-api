<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Officer;

/**
 * @Resource("Officers", uri="/officers")
 */
class OfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $officers = Officer::all();

        return response()->json($officers);
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

        return response()->json($officer);
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
            $officer = Officer::findOrFail($id);

            return response()->json($officer);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse(
                ['error' => 'not found'], Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Officer::destroy($id);
    }
}
