<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Committee;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;

class CommitteeMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($committeeId)
    {
        $committee = Committee::with('members')->findOrFail($committeeId);

        return response()->json($committee->members);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, $committeeId)
    {
        $this->validate($request, [
            'member_id' => 'required|unique:members,id',
        ]);

        $committee = Committee::findOrFail($committeeId);
        $memberId = $request->input('member_id');

        $committee->members()->attach($memberId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $committeeId
     * @return Response
     */
    public function destroy($committeeId, $memberId)
    {
        $committee = Committee::findOrFail($committeeId);

        $committee->members()->detach($memberId);
    }
}
