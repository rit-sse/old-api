<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Member;


class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        $result = [];

        $members = Member::orderBy('created_at', 'asc')->get(array('created_at'));

        foreach ($members as $member) {
            // Make a frequency array mapping date=>frequency`
            $date = new DateTime($member->created_at);
            $formattedDate = $date->format('d/m/Y');
            if (array_key_exists($formattedDate, $result)) {
                $result[$formattedDate]++;
            } else {
                $result[$formattedDate] = 1;
            }
        }


        return response()->json($result);

    }

}
