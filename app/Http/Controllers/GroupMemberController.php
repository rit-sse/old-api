<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Group;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;

class GroupMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($groupId)
    {
        $group = Group::with('members')->findOrFail($groupId);

        return response()->json($group->members);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, $groupId)
    {
        $this->validate($request, [
            'member_id' => 'required|unique:members,id',
        ]);

        $group = Group::findOrFail($groupId);
        $memberId = $request->input('member_id');

        $group->members()->attach($memberId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $groupId
     * @return Response
     */
    public function destroy($groupId, $memberId)
    {
        $group = Group::findOrFail($groupId);

        $group->members()->detach($memberId);
    }
}
