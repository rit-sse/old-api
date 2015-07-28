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
     * @Response(200, body={{"id": 1, "body": "foo", "created_by": "1",
     *                       "url": "/agenda/1"}})
     * @return Response
     */
    public function index()
    {
        $items = AgendaItem::query();

        return response()->json($items->paginate());
    }

    /**
     * Delete all current agenda items.
     *
     * @Delete("/")
     * @Response(204)
     * @return Response
     */
    public function clear()
    {
        AgendaItem::where('id', '>', 0)->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Store a newly created agenda item.
     *
     * @Post("/")
     * @Transaction(
     *     @Request({"body": "foo"}),
     *     @Response(201, body={"id": 2, "body": "foo", "created_by": "1",
     *                          "url": "/agenda/2"}),
     *     @Response(422, body={"body": {"The body has already been taken."}})
     * )
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'body' => 'required|unique:agenda_items,body',
        ]);

        $agendaItem = new AgendaItem();

        $agendaItem->body = $request->input('body');
        $agendaItem->created_by = $request->member->id;

        $agendaItem->save();

        return new JsonResponse($agendaItem, Response::HTTP_CREATED);
    }

    /**
     * Get the specified agenda item.
     *
     * @Get("/{id}")
     * @Transaction(
     *     @Response(200, body={"id": 1, "body": "foo", "edited_by_url": "",
     *                          "author_url": "/members/1", "url": "/agenda/2"}),
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
     *     @Request({"body": "bar"}),
     *     @Response(200, body={"id": 1, "body": "bar", "created_by": "1", "url": "/agenda/2"}),
     *     @Response(404, body={"error": "not found"})
     * )
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'body' => 'required',
        ]);

        $agendaItem = AgendaItem::findOrFail($id);

        $agendaItem->body = $request->input('body', $agendaItem->body);
        $agendaItem->updated_by = $request->member->id;

        $agendaItem->save();

        return response()->json($agendaItem);
    }

    /**
     * Remove the specified agenda item from storage.
     *
     * @Delete("/{id}")
     * @Response(204)
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        AgendaItem::destroy($id);

        return response('', Response::HTTP_NO_CONTENT);
    }
}
