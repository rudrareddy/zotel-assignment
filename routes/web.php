<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\RatePlanController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\InventoryController;


Route::get('/', function () {
    return view('search');
});

Route::get('/dashboard', function () {
    if(Auth::user()->hasRole('admin')){
     //return "hi";
     return redirect('admin/room-types');
  }
 
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::group(['prefix' =>'admin','middleware'=>['role:admin','auth']],function($role){
    Route::resource('room-types', RoomTypeController::class)
    ->missing(function () {
        return redirect()
            ->route('room-types.index')
            ->with('error', 'Room Type not found.');
    });
    Route::resource('rate-plans',RatePlanController::class)->missing(function () {
        return redirect('admin/rate-plans')
            ->with('error', 'Rate Plan not found');});
    Route::post('room-types/{roomType}/rates', [RoomTypeController::class, 'updateRates']);
    //dscounts
    Route::resource('discounts',DiscountController::class)->missing(function () {
        return redirect('admin/room-types')->with('error', 'Discount Rule not found');});

    Route::get('inventory/{slug}',[InventoryController::class,'index']);
    Route::get('inventory/{slug}/create',[InventoryController::class,'create']);
    Route::post('inventory/store',[InventoryController::class,'store']);
    Route::get('inventory/{inventory}/edit', [InventoryController::class, 'edit'])
    ->missing(function () {
        return redirect()
            ->back()
            ->with('error', 'Inventory not found.');
    });
    Route::patch('inventory/update/{inventory}',[InventoryController::class,'update']);
});

