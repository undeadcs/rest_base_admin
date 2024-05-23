<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApartmentsController;
use App\Http\Controllers\Api\InventoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller( ApartmentsController::class )->prefix( 'apartments' )->group( function( ) {
	Route::get( '/', 'index' );
	Route::get( '/{apartment}', 'instance' );
	Route::get( '/{apartment}/prices', 'prices' );
} );

Route::controller( InventoryController::class )->prefix( 'inventories' )->group( function( ) {
	Route::get( '/', 'index' );
	Route::get( '/{inventory}', 'instance' );
	Route::get( '/{inventory}/prices', 'prices' );
} );
