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
 * @Resource("Tips", uri="/tips")
 */
class TipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $tips = Tip::all();

        return response()->json($tips);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['content' => 'required']);

        $tip = new Tip();

        $tip->content = $request->input('content');
        $tip->created_by = 1;

        $tip->save();

        return response()->json($tip);
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
            $tip = Tip::with('author', 'edited_by')->findOrFail($id);

            return response()->json($tip);
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
        $this->validate($request, ['content' => 'required']);

        $tip = Tip::findOrFail($id);

        $tip->content = $request->input('content');
        $tip->updated_by = 1;

        $tip->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Tip::destroy($id);
    }
}
