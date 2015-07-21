<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\AgendaItem;
use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Controls the collection of agenda items.
 *
 * @Resource("Agenda Items", uri="/agenda")
 * @Versions({"v1"})
 */
class AgendaController extends Controller
{
    /**
     * Get the collection of agenda items.
     *
     * @Get("/")
     * @Response(200, body={{"id": 1, "content": "foo", "created_by": "1", "url": "/agenda/1"}})
     * @return Response
     */
    public function index()
    {
        $items = AgendaItem::all();

        return response()->json($items);
    }

    /**
     * Delete all current agenda items.
     *
     * @Delete("/")
     * @Response(200)
     * @return Response
     */
    public function clear()
    {
        AgendaItem::where('id', '>', 0)->delete();
    }

    /**
     * Store a newly created agenda item.
     *
     * @Post("/")
     * @Transaction(
     *     @Request({"content": "foo"}),
     *     @Response(200, body={"id": 2, "content": "foo", "created_by": "1", "url": "/agenda/2"}),
     *     @Response(422, body={"content": {"The content has already been taken."}})
     * )
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
     * Get the specified agenda item.
     *
     * @Get("/{id}")
     * @Transaction(
     *     @Response(200, body={"id": 1, "content": "foo", "created_by": "1", "url": "/agenda/2"}),
     *     @Response(404, body={"error": "not found"})
     * )
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
     * Update the specified agenda item in storage.
     *
     * @Put("/{id}")
     * @Transaction(
     *     @Request({"content": "bar"}),
     *     @Response(200, body={"id": 1, "content": "bar", "created_by": "1", "url": "/agenda/2"}),
     *     @Response(404, body={"error": "not found"})
     * )
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
     * Remove the specified agenda item from storage.
     *
     * @Delete("/{id}")
     * @Response(200)
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        AgendaItem::destroy($id);
    }
}
