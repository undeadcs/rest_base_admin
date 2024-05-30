<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ApartmentsController;
use App\Http\Controllers\InventoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\Ui\InventoriesController as UiInventoriesController;
use App\Http\Controllers\Ui\ApartmentsController as UiApartmentsController;
use App\Http\Controllers\Ui\CustomersController as UiCustomersController;

Route::controller( PagesController::class )->group( function( ) {
	Route::get( '/', 'main' )->name( 'page_main' );
	Route::get( '/orders', 'orders' )->name( 'page_orders' );
	Route::get( '/orders/add', 'newOrder' );
	Route::get( '/orders/{order}', 'editOrder' );
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

Route::controller( UiCustomersController::class )->prefix( 'customers' )->group( function( ) {
	Route::get( '/', 'index' )->name( 'page_customers' );
	Route::get( '/add', 'add' );
	Route::get( '/{customer}', 'edit' );
} );

Route::controller( CustomersController::class )->group( function( ) {
	Route::post( '/customers', 'add' );
	Route::put( '/customers/{customer}', 'update' );
} );

Route::controller( OrdersController::class )->group( function( ) {
	Route::post( '/orders', 'add' );
	Route::put( '/orders/{order}', 'update' );
} );
