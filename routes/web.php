<?php

use App\Http\Controllers\IncidentController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
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

/*Route::get('/', function () {
    return redirect()->route('login');
});*/

Route::get('/', fn () => auth()->check() ? redirect('/home') : view('auth.login'));

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('incidents', IncidentController::class);
    Route::patch('/incidents/{incident}/assign-self', [IncidentController::class, 'assignToSelf'])
    ->name('incidents.assignSelf');
    Route::patch('/incidents/{incident}/status', [IncidentController::class, 'updateStatus'])->name('incidents.updateStatus');
    Route::get('/incidents/{incident}', [IncidentController::class, 'show'])->name('incidents.show');
    Route::post('/incidents/{incident}/comment', [IncidentController::class, 'addComment'])->name('incidents.comment');
    Route::patch('/incidents/{incident}/quick-assign', [IncidentController::class, 'quickAssign'])->name('incidents.quickAssign');





    /*Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}/', [UserController::class, 'update'])->name('users.update');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}/', [UserController::class, 'destroy'])->name('users.destroy');*/
    // Otras rutas protegidas...
});





/*Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users/', [UserController::class, 'store'])->name('users.store');
Route::get('/users', [UserController::class, 'index'])->name('users.index');*/


/*
Route::get('/create', function () {
    return Response::view('users/create');
});

//Route::get('/create', fn() => Response::view('users/create'));


Route::post('/create', function (Request $request) {
    dd($request);
    //dd($request->get('email')); #Debugar parametro
});

Route::post('/change-password', function (){
    if(auth()->check()){
        return response("Authenticated");
    }else{
        return response("Not   Authenticated", 401);
    }

});

#DB::statement("insert into contacts (name, email) values (?, ?)", [$data['name'],$data['email']]);
*/


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
