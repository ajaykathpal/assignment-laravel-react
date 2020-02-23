<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['jwt.auth','api-header', 'can-manage']], function () {
    Route::post('user', 'UserController@add');
    Route::patch('user/{userId}', 'UserController@edit');
    Route::delete('user/{userId}', 'UserController@remove');

    Route::post('role/', 'RoleController@add');
    Route::patch('role/{roleId}', 'RoleController@edit');
    Route::delete('role/{roleId}', 'RoleController@remove');

    Route::get('user/list', 'UserController@getList');

    Route::get('user/action', 'UserController@getUserActions');

    Route::get('role/action', 'RoleController@getRoleActions');

    Route::get('role/list', function(){
        $users = App\Roles::all();
        
        $response = ['success'=>true, 'data'=>$users];
        return response()->json($response, 201);
    });
});

Route::group(['middleware' => ['jwt.auth','api-header']], function () {
  
    // all routes to protected resources are registered here  
    // Route::get('users/list', function(){
    //     $users = App\User::all();
        
    //     $response = ['success'=>true, 'data'=>$users];
    //     return response()->json($response, 201);
    // });
});

Route::group(['middleware' => 'api-header'], function () {
  
    // The registration and login requests doesn't come with tokens 
    // as users at that point have not been authenticated yet
    // Therefore the jwtMiddleware will be exclusive of them

    Route::post('user/login', 'UserController@login');
    Route::post('user/register', 'UserController@register');
});