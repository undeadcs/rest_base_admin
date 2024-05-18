<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up( ) : void {
		Schema::create( 'inventory_prices', function( Blueprint $table ) {
			$table->id( );
			$table->foreignId( 'inventory_id' )->constrained( )->cascadeOnDelete( );
			$table->timestamp( 'created_at' );
			$table->float( 'price' );
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down( ) : void {
		Schema::dropIfExists( 'inventory_prices' );
	}
};
