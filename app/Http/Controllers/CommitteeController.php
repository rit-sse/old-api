<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Committee;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $committees = Committee::all();

        return response()->json($committees);
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
            'name' => 'required|unique:committees,name',
            'head_id' => 'required|exists:officers,id',
        ]);

        $committee = new Committee();

        $committe->name = $request->input('name');
        $committe->head_id = $request->input('head_id');

        $committe->save();

        return response()->json($committee);
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
            $committee = Committee::with('head')->findOrFail($id);

            return response()->json($committee);
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
            'name' => 'unique:committees,name',
            'head_id' => 'required|exists:officers,id',
        ]);

        $committee = Committee::findOrFail($id);

        $committee->name = $request->input('name', $committee->name);
        $committee->head_id = $request->input('head_id', $committee->head_id);

        $committee->save();

        return response()->json($committee);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Committee::destroy($id);
    }
}
