<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Quote;
use App\Tag;

/**
 * Quote resource representation.
 *
 * @Resource("Quotes", uri="/quotes")
 * @Versions({"v1"})
 */
class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $quotes = Quote::with('tags')->where('approved', true);

        return response()->json($quotes->paginate());
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
            'body' => 'required|string',
            'description' => 'required|string',
            'tags' => 'required|array'
        ]);

        $tags = array_map(function($tag) {
            return new Tag(['body' => $tag]);
        }, $request->input('tags'));

        $quote = new Quote();

        $quote->approved = false;
        $quote->body = $request->input('body');
        $quote->description = $request->input('description');
        // FIXME: Replace with currently auth'd member
        $quote->member_id = $request->member->id;

        $quote->save();

        $quote->tags()->saveMany($tags);

        return new JsonResponse($quote, Response::HTTP_CREATED);
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
            $quote = Quote::findOrFail($id);

            return response()->json($quote);
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
        try {
            $quote = Quote::findOrFail($id);

            $quote->body = $request->input('body', $quote->body);
            $quote->description = $request->input('description', $quote->description);

            $quote->save();

            return response()->json($quote);
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
        Quote::destroy($id);

        return response('', Response::HTTP_NO_CONTENT);
    }
}
