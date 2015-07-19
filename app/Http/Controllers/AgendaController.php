<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\AgendaItem;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = AgendaItem::all();

        return response()->json($items);
    }

    /**
     * Delete all resources.
     *
     * @return Response
     */
    public function clear()
    {
        AgendaItem::where('id', '>', 0)->delete();
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
            'content' => 'required|unique:agenda_items,content',
        ]);

        $agendaItem = new AgendaItem();

        $agendaItem->content = $request->input('content');
        $agendaItem->created_by = 1;

        $agendaItem->save();

        return response()->json($agendaItem);
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
            $agendaItem = AgendaItem::findOrFail($id);

            return response()->json($agendaItem);
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
            'content' => 'required',
        ]);

        $agendaItem = AgendaItem::findOrFail($id);

        $agendaItem->content = $request->input('content', $agendaItem->content);
        $agendaItem->updated_by = 1;

        $agendaItem->save();

        return response()->json($agendaItem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        AgendaItem::destroy($id);
    }
}
