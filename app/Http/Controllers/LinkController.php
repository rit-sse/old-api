<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;
use App\Link;
use App\Http\Requests;

/**
 * @Resource("Short Links", uri="/links")
 * @Versions({"v1"})
 */
class LinkController extends Controller
{
    /**
     * Resolve a given go link into a redirect response.
     *
     * @return response
     */
    public function resolveLink($goLink)
    {
        $link = Link::where('go_link', $goLink)->first();

        if (is_null($link)) {
            return response()->view(
                'missing_link', ['link' => $goLink], Response::HTTP_NOT_FOUND
            );
        } else {
            return redirect($link->expanded_link);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $links = Link::all();

        return response()->json($links);
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
            'expanded_link' => 'required|url|unique:links,expanded_link',
            'go_link' => 'required|unique:links,go_link',
        ]);

        $link = new Link();

        $link->expanded_link = $request->input('expanded_link');
        $link->go_link = $request->input('go_link');
        $link->member_id = 1;

        $link->save();

        return response()->json($link);
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
            $link = Link::findOrFail($id);

            return response()->json($link);
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
            'expanded_link' => 'sometimes|required|url|unique:links,expanded_link',
            'go_link' => 'sometimes|unique:links,go_link',
        ]);

        try {
            $link = Link::findOrFail($id);

            $link->expanded_link = $request->input('expanded_link', $link->expanded_link);
            $link->go_link = $request->input('go_link', $link->go_link);

            $link->save();

            return response()->json($link);
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
        Link::destroy($id);
    }
}
