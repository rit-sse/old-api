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

Route::get('/go/{go_link}', 'LinkController@resolveLink');

Route::group(['middleware' => 'csrf', 'prefix' => 'api'], function () {
    Route::get('/', function() {
        return response()->json([
            'current_version' => '/v1',
        ]);
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('/', function() {
            return response()->json([
                'agenda_url' => '/agenda',
                'committee_url' => '/committees',
                'lingo_url' => '/lingo',
                'members_url' => '/members',
                'memberships_url' => '/memberships',
                'mentors_url' => '/mentors',
                'officers_url' => '/officers',
                'quotes_url' => '/quotes',
                'terms_url' => '/terms',
                'tips_url' => '/tips',
            ]);
        });

        Route::delete('/agenda', 'AgendaController@clear');
        Route::resource(
            'agenda',
            'AgendaController',
            ['except' => ['create', 'edit']]
        );

        Route::resource(
            'committees',
            'CommitteeController',
            ['except' => ['create', 'edit']]
        );

        Route::resource(
            'events',
            'EventController',
            ['except' => ['create', 'edit']]
        );

        Route::resource(
            'lingo',
            'LingoController',
            ['except' => ['create', 'edit']]
        );

        Route::resource(
            'links',
            'LinkController',
            ['except' => ['create', 'edit']]
        );

        Route::resource(
            'members',
            'MemberController',
            ['except' => ['create', 'edit']]
        );

        Route::resource(
            'memberships',
            'MembershipController',
            ['except' => ['create', 'edit', 'update']]
        );

        Route::resource(
            'mentors',
            'MentorController',
            ['except' => ['create', 'edit']]
        );

        Route::resource(
            'officers',
            'OfficerController',
            ['except' => ['create', 'edit']]
        );

        Route::resource(
            'quotes',
            'QuoteController',
            ['except' => ['create', 'edit']]
        );

        Route::get('terms/current_term', 'TermController@current_term');
        Route::resource(
            'terms',
            'TermController',
            ['only' => ['index', 'show']]
        );

        Route::resource(
            'tips',
            'TipController',
            ['except' => ['create', 'edit']]
        );
    });
});
