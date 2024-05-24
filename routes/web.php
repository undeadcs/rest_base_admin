<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ApartmentsController;
use App\Http\Controllers\InventoriesController;
use App\Http\Controllers\CustomersController;

Route::controller( PagesController::class )->group( function( ) {
	Route::get( '/', 'main' )->name( 'page_main' );
	Route::get( '/apartments', 'apartments' )->name( 'page_apartments' );
	Route::get( '/apartments/add', 'newApartment' );
	Route::get( '/apartments/{apartment}', 'editApartment' );
	Route::get( '/reservs', 'reservs' )->name( 'page_reservs' );
	Route::get( '/customers', 'customers' )->name( 'page_customers' );
	Route::get( '/customers/add', 'newCustomer' );
	Route::get( '/customers/{customer}', 'editCustomer' );
	Route::get( '/inventories', 'inventories' )->name( 'page_inventories' );
	Route::get( '/inventories/add', 'newInventory' );
	Route::get( '/inventories/{inventory}', 'editInventory' );
} );

Route::controller( ApartmentsController::class )->group( function( ) {
	Route::post( '/apartments', 'add' );
	Route::put( '/apartments/{apartment}', 'update' );
} );

Route::controller( InventoriesController::class )->group( function( ) {
	Route::post( '/inventories', 'add' );
	Route::put( '/inventories/{inventory}', 'update' );
} );

Route::controller( CustomersController::class )->group( function( ) {
	Route::post( '/customers', 'add' );
	Route::put( '/customers/{customer}', 'update' );
} );
