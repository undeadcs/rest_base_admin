<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ApartmentsController;
use App\Http\Controllers\InventoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\Ui\InventoriesController as UiInventoriesController;
use App\Http\Controllers\Ui\ApartmentsController as UiApartmentsController;

Route::controller( PagesController::class )->group( function( ) {
	Route::get( '/', 'main' )->name( 'page_main' );
	Route::get( '/orders', 'orders' )->name( 'page_orders' );
	Route::get( '/orders/add', 'newOrder' );
	Route::get( '/orders/{order}', 'editOrder' );
	Route::get( '/customers', 'customers' )->name( 'page_customers' );
	Route::get( '/customers/add', 'newCustomer' );
	Route::get( '/customers/{customer}', 'editCustomer' );
} );

Route::controller( UiApartmentsController::class )->prefix( 'apartments' )->group( function( ) {
	Route::get( '/', 'index' )->name( 'page_apartments' );
	Route::get( '/add', 'add' );
	Route::get( '/{apartment}', 'edit' );
} );

Route::controller( ApartmentsController::class )->group( function( ) {
	Route::post( '/apartments', 'add' );
	Route::put( '/apartments/{apartment}', 'update' );
} );

Route::controller( UiInventoriesController::class )->prefix( 'inventories' )->group( function( ) {
	Route::get( '/', 'index' )->name( 'page_inventories' );
	Route::get( '/add', 'add' );
	Route::get( '/{inventory}', 'edit' );
} );

Route::controller( InventoriesController::class )->group( function( ) {
	Route::post( '/inventories', 'add' );
	Route::put( '/inventories/{inventory}', 'update' );
} );

Route::controller( CustomersController::class )->group( function( ) {
	Route::post( '/customers', 'add' );
	Route::put( '/customers/{customer}', 'update' );
} );

Route::controller( OrdersController::class )->group( function( ) {
	Route::post( '/orders', 'add' );
	Route::put( '/orders/{order}', 'update' );
} );
