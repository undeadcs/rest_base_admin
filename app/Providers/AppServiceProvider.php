<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Services\Navigation;
use App\Repositories\ApartmentRepository;
use App\Repositories\DatabaseApartmentRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\DatabaseInventoryRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\DatabaseCustomerRepository;
use App\Repositories\OrderRepository;
use App\Repositories\DatabaseOrderRepository;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Register any application services.
	 */
	public function register( ) : void {
		$this->app->singleton( Navigation::class );
		$this->app->singleton( ApartmentRepository::class, DatabaseApartmentRepository::class );
		$this->app->singleton( InventoryRepository::class, DatabaseInventoryRepository::class );
		$this->app->singleton( CustomerRepository::class, DatabaseCustomerRepository::class );
		$this->app->singleton( OrderRepository::class, DatabaseOrderRepository::class );
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot( ) : void {
		Route::pattern( 'id', '[0-9]+' );
		Route::pattern( 'customer', '[0-9]+' );
		Route::pattern( 'inventory', '[0-9]+' );
		Route::pattern( 'apartment', '[0-9]+' );
		Route::pattern( 'order', '[0-9]+' );
	}
}
