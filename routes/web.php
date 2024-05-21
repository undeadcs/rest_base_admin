<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ApartmentsController;

Route::controller( PagesController::class )->group( function( ) {
	Route::get( '/', 'main' )->name( 'page_main' );
	Route::get( '/apartments', 'apartments' )->name( 'page_apartments' );
	Route::get( '/apartments/add', 'newApartment' );
	Route::get( '/apartments/{apartment}', 'editApartment' );
	Route::get( '/reservs', 'reservs' )->name( 'page_reservs' );
	Route::get( '/customers', 'customers' )->name( 'page_customers' );
	Route::get( '/inventories', 'inventories' )->name( 'page_inventories' );
} );

Route::controller( ApartmentsController::class )->group( function( ) {
	Route::post( '/apartments', 'add' );
	Route::put( '/apartments/{apartment}', 'update' );
} );
