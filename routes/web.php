<?php

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('test', function () {

    /** 範例 - 客戶端權限 */
    $marketing = \App\Models\User::find(7);
    $manager = \App\Models\User::find(3);
    auth()->setUser($marketing);

    // if (Gate::allows('view-product')) {
    //     return "<pre>true test page</pre>";
    // } else {
    //     return "<pre>false test page</pre>";
    // }

    // if (Gate::allows('update-product')) {
    //     return "<pre>true test page</pre>";
    // } else {
    //     return "<pre>false test page</pre>";
    // }

    // if (Gate::allows('delete-product', '帶其他參數進去')) {
    //     return "<pre>true test page</pre>";
    // } else {
    //     return "<pre>false test page</pre>";
    // }

    /** 範例 - 客戶端授予權限 */
    // $marketing->givePermissionTo(
    //     ['create-product','update-product']
    // );
    // 或以多個字串型態也可以
    // $marketing->givePermissionTo('create-product','update-product');

    /** 範例 - 客戶端撤銷權限 */
    // $marketing->withdrawPermissionTo(['create-product']);
    // 或以多個字串型態也可以
    // $marketing->withdrawPermissionTo('create-product');

    /** 範例 - 客戶端更新權限 */
    $marketing->updatePermissions(['view-product', 'update-product']);

    echo 'done';
});