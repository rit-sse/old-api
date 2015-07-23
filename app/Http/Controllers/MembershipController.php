<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;
use App\Membership;
use App\Term;

/**
 * @Resource("Memberships", uri="/memberships")
 */
class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $queryParameters = array_filter(
            $request->only(['member', 'term'])
        );

        $memberships = Membership::query();

        if (array_key_exists('member', $queryParameters)) {
            $memberships->where('member_id', $queryParameters['member']);
        }

        if (array_key_exists('term', $queryParameters)) {
            $memberships->where('term_id', $queryParameters['term']);
        }

        return response()->json($memberships->get());
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
            'member_id' => 'required|exists:members,id',
            'reason' => 'required',
            'term_id' => 'required|exists:terms,id',
        ]);

        $member = Member::findOrFail($request->input('member_id'));
        $term = Term::findOrFail($request->input('term_id'));
        $membership = new Membership();

        $membership->reason = $request->input('reason');
        $membership->term_id = $request->input('term_id');

        $member->memberships()->save($membership);

        return new JsonResponse($membership, Response::HTTP_CREATED);
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
            $membership = Membership::with('member', 'term')->findOrFail($id);

            return response()->json($membership);
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
        Membership::destroy($id);

        return response('', Response::HTTP_NO_CONTENT);
    }
}
