<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Services\TopNavBar;
use App\Repositories\ApartmentRepository;
use App\Repositories\DatabaseApartmentRepository;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Register any application services.
	 */
	public function register( ) : void {
		$this->app->singleton( TopNavBar::class );
		$this->app->singleton( ApartmentRepository::class, DatabaseApartmentRepository::class );
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot( ) : void {
		Route::pattern( 'id', '[0-9]+' );
	}
}
