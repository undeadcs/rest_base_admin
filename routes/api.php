<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApartmentsController;
use App\Http\Controllers\Api\InventoriesController;
use App\Http\Controllers\Api\CustomersController;

Route::controller( ApartmentsController::class )->prefix( 'apartments' )->group( function( ) {
	Route::get( '/', 'index' );
	Route::get( '/{apartment}', 'instance' );
	Route::get( '/{apartment}/prices', 'prices' );
} );

Route::controller( InventoriesController::class )->prefix( 'inventories' )->group( function( ) {
	Route::get( '/', 'index' );
	Route::get( '/{inventory}', 'instance' );
	Route::get( '/{inventory}/prices', 'prices' );
} );

Route::controller( CustomersController::class )->prefix( 'customers' )->group( function( ) {
	Route::get( '/', 'index' );
	Route::get( '/{customer}', 'instance' );
} );
