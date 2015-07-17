<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function() {
    return view('welcome');
});

Route::group(['middleware' => 'csrf', 'prefix' => 'api'], function () {
    Route::get('/', function() {
        return response()->json([
            'current_version' => '/v1',
        ]);
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('/', function() {
            return response()->json([
                'lingo_url' => '/lingo',
                'members_url' => '/members',
                'memberships_url' => '/memberships',
                'mentors_url' => '/mentors',
                'officesr' => '/officers',
                'quotes_url' => '/quotes',
                'terms_url' => '/terms',
            ]);
        });

        Route::resource(
            'events',
            'EventController',
            ['except' => ['create']]
        );

        Route::resource(
            'lingo',
            'LingoController',
            ['except' => ['create']]
        );

        Route::resource(
            'members',
            'MemberController',
            ['except' => ['create', 'edit']]
        );

        Route::resource(
            'memberships',
            'MembershipController'
        );

        Route::resource(
            'mentors',
            'MentorController'
        );

        Route::resource(
            'officers',
            'OfficerController'
        );

        Route::resource(
            'quotes',
            'QuoteController'
        );

        Route::resource(
            'terms',
            'TermController',
            ['only' => ['index', 'show']]
        );
    });
});
