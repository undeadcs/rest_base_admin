<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Services\TopNavBar;
use App\Repositories\ApartmentRepository;
use App\Repositories\DatabaseApartmentRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\DatabaseInventoryRepository;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Register any application services.
	 */
	public function register( ) : void {
		$this->app->singleton( TopNavBar::class );
		$this->app->singleton( ApartmentRepository::class, DatabaseApartmentRepository::class );
		$this->app->singleton( InventoryRepository::class, DatabaseInventoryRepository::class );
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot( ) : void {
		Route::pattern( 'id', '[0-9]+' );
	}
}
