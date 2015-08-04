<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;

/**
 * @Resource("Members", uri="/members")
 */
class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $queryParameters = array_filter(
            $request->only(['first_name', 'last_name', 'dce', 'group'])
        );

        $members = Member::query();

        if (array_key_exists('group', $queryParameters)) {
            $groupId = $queryParameters['group'];
            unset($queryParameters['group']);

            $members = Member::whereHas(
                'groups',
                function ($query) use ($groupId) {
                    $query->where('group_id', $groupId);
                }
            );
        }

        $members->where($queryParameters);

        return response()->json($members->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @Response(201)
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'dce' => 'required|unique:members,regex:/[a-zA-Z]{2,3}\d{4}/',
        ]);

        $member = new Member();

        $member->first_name = $request->input('first_name');
        $member->last_name = $request->input('last_name');
        $member->dce = $request->input('dce');

        $member->save();

        return new JsonResponse($member, Response::HTTP_CREATED);
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
            $result = Member::with('memberships')->findOrFail($id);

            return response()->json($result);
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
            'first_name' => 'string',
            'last_name' => 'string',
        ]);

        try {
            $member = Member::findOrFail($id);

            $member->first_name = $request->input('first_name', $member->first_name);
            $member->last_name = $request->input('last_name', $member->last_name);

            $member->save();

            return response()->json($member);
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
        Member::destroy($id);

        return response('', Response::HTTP_NO_CONTENT);
    }
}
