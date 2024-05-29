<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up( ) : void {
		Schema::create( 'inventory_order', function( Blueprint $table ) {
			$table->id( );
			$table->foreignId( 'inventory_id' )->constrained( )->cascadeOnDelete( );
			$table->foreignId( 'order_id' )->constrained( )->cascadeOnDelete( );
			$table->string( 'comment' );
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down( ) : void {
		Schema::dropIfExists( 'inventory_order' );
	}
};
