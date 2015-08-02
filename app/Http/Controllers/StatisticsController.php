<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Member;


class StatisticsController extends Controller
{
    /**
     * Display the member statistics
     *
     * Will return JSON mapping date => number of users who registered on date.
     * Note: Return JSON should have dates in order, but not guaranteed.
     *
     * @return Response
     */
    public function getMembers()
    {
        $result = [];

        $members = Member::orderBy('created_at', 'asc')->get(['created_at']);

        foreach ($members as $member) {
            // Make a frequency array mapping date=>frequency
            $date = $member->created_at->format('d/m/Y');
            if (array_key_exists($date, $result)) {
                $result[$date]++;
            } else {
                $result[$date] = 1;
            }
        }

        return response()->json($result);
    }

}
