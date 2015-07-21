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
 * @Resource("Lingo", uri="/lingo")
 */
class LingoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $lingo = Lingo::all();

        return response()->json($lingo);
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
            'phrase' => 'required|unique:lingo,phrase',
            'definition' => 'required'
        ]);

        $lingo = new Lingo();

        $lingo->phrase = $request->input('phrase');
        $lingo->definition = $request->input('definition');

        $lingo->save();
        return response()->json($lingo);
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
            $lingo = Lingo::findOrFail($id);

            return response()->json($lingo);
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
        $lingo = Lingo::findOrFail($id);

        $lingo->definition = $request->input('definition', $this->definition);

        $lingo->save();

        return response()->json($lingo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Lingo::destroy($id);
    }
}
