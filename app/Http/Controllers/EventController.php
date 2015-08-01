<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Event;
use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @Resource("Events", uri="/events")
 */
class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'end_date' => 'date',
            'start_date' => 'date',
            'when' => 'in:past,future',
        ]);

        $queryParameters = array_filter(
            $request->only(['end_date', 'start_date', 'when'])
        );

        $events = Event::query();

        if (array_key_exists('end_date', $queryParameters)) {
            $events->where('end_date', $queryParameters['end_date']);
        }

        if (array_key_exists('start_date', $queryParameters)) {
            $events->where('start_date', $queryParameters['start_date']);
        }

        if (array_key_exists('when', $queryParameters)) {
            $now = Carbon::now();
            switch ($queryParameters['when']) {
                case 'past':
                    $events->where('end_date', '<', $now->toIso8601String());
                    break;
                case 'future':
                    $events->where('start_date', '>', $now->toIso8601String());
                    break;
                default:
                    abort(500);
            }
        }

        return response()->json($events->paginate());
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
            'description' => 'required|string',
            'end_date' => 'required|date',
            'featured' => 'boolean',
            'group' => 'required|exists:groups,id',
            'image' => 'required|string',
            'location' => 'required|string',
            'name' => 'required|string',
            'recurrence' => 'string',
            'short_description' => 'required|string',
            'short_name' => 'required|string',
            'start_date' => 'required|date',
        ]);

        $event = new Event();

        $event->description = $request->input('description');
        $event->end_date = $request->input('end_date');
        $event->featured = $request->input('featured', false);
        $event->group_id = $request->input('group');
        $event->image = $request->input('image');
        $event->location = $request->input('location');
        $event->name = $request->input('name');
        $event->recurrence = $request->input('recurrence');
        $event->short_description = $request->input('short_description');
        $event->short_name = $request->input('short_name');
        $event->start_date = $request->input('start_date');

        $event->save();

        return new JsonResponse(
            $event,
            Response::HTTP_CREATED
        );
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
            $event = Event::findOrFail($id);

            return response()->json($event);
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
            'description' => 'string',
            'end_date' => 'date',
            'featured' => 'boolean',
            'group' => 'exists:groups,id',
            'image' => 'string',
            'location' => 'string',
            'name' => 'string',
            'recurrence' => 'string',
            'short_description' => 'string',
            'short_name' => 'string',
            'start_date' => 'date',
        ]);

        try {
            $event = Event::findOrFail($id);

            $event->description = $request->input('description', $event->description);
            $event->end_date = $request->input('end_date', $event->end_date);
            $event->featured = $request->input('featured', $event->featured);
            $event->group_id = $request->input('group', $event->group_id);
            $event->image = $request->input('image', $event->image);
            $event->location = $request->input('location', $event->location);
            $event->name = $request->input('name', $event->name);
            $event->recurrence = $request->input('recurrence', $event->recurrence);
            $event->short_description = $request->input('short_description', $event->short_description);
            $event->short_name = $request->input('short_name', $event->short_name);
            $event->start_date = $request->input('start_date', $event->start_date);

            $event->save();

            return new JsonResponse(
                $event, Response::HTTP_CREATED
            );
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
        Event::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
