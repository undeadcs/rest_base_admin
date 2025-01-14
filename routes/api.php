<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApartmentsController;
use App\Http\Controllers\Api\InventoriesController;
use App\Http\Controllers\Api\CustomersController;
use App\Http\Controllers\Api\OrdersController;

Route::controller( ApartmentsController::class )->prefix( 'apartments' )->group( function( ) {
	Route::get( '/', 'index' );
	Route::get( '/{apartment}', 'instance' );
	Route::get( '/{apartment}/prices', 'prices' );
	Route::get( '/{apartment}/orders', 'orders' );
	Route::get( '/{apartment}/orders-by-period', 'ordersByPeriod' );
} );

Route::controller( InventoriesController::class )->prefix( 'inventories' )->group( function( ) {
	Route::get( '/', 'index' );
	Route::get( '/{inventory}', 'instance' );
	Route::get( '/{inventory}/prices', 'prices' );
	Route::get( '/{inventory}/orders', 'orders' );
} );

Route::controller( CustomersController::class )->prefix( 'customers' )->group( function( ) {
	Route::get( '/', 'index' );
	Route::get( '/find-for-order', 'findForOrder' );
	Route::get( '/{customer}', 'instance' );
	Route::get( '/{customer}/orders', 'orders' );
} );

Route::controller( OrdersController::class )->prefix( 'orders' )->group( function( ) {
	Route::get( '/', 'index' );
	Route::get( '/find-by-period', 'findByPeriod' );
	Route::get( '/{order}', 'instance' );
	Route::get( '/{order}/inventories', 'inventories' );
	Route::get( '/{order}/payments', 'payments' );
} );
