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
    Route::get('/', ['as' => 'api', function () {
        return response()->json([
            'v1_url' => route('api.v1')
        ]);
    }]);

    Route::group(['prefix' => 'v1'], function () {
        Route::get('/', ['as' => 'api.v1', function () {
            return response()->json([
                'agenda_url' => route('api.v1.agenda.index'),
                'events_url' => route('api.v1.events.index'),
                'groups_url' => route('api.v1.groups.index'),
                'lingo_url' => route('api.v1.lingo.index'),
                'members_url' => route('api.v1.members.index'),
                'memberships_url' => route('api.v1.memberships.index'),
                'mentors_url' => route('api.v1.mentors.index'),
                'officers_url' => route('api.v1.officers.index'),
                'quotes_url' => route('api.v1.quotes.index'),
                'tasks-url' => route('api.v1.tasks.index'),
                'terms_url' => route('api.v1.terms.index'),
                'tips_url' => route('api.v1.tips.index'),
            ]);
        }]);

        // Authentication routes
        Route::get(
            'auth/google', 'Auth\AuthController@redirectToProvider'
        );
        Route::get(
            'auth/google/callback',
            'Auth\AuthController@handleProviderCallback'
        );
        Route::get(
            'auth/token', 'Auth\AuthController@getToken'
        );

        Route::delete('/agenda', 'AgendaController@clear');
        Route::resource(
            'agenda',
            'AgendaController',
            ['except' => ['create', 'edit']]
        );

        Route::resource(
            'events',
            'EventController',
            ['except' => ['create', 'edit']]
        );

        Route::resource(
            'groups',
            'GroupController',
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

        Route::resource(
            'tasks',
            'TasksController',
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

        Route::controller('statistics', 'StatisticsController');
    });
});
