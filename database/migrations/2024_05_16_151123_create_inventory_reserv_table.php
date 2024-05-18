<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up( ) : void {
		Schema::create( 'inventory_reserv', function( Blueprint $table ) {
			$table->foreignId( 'inventory_id' )->constrained( )->cascadeOnDelete( );
			$table->foreignId( 'reserv_id' )->constrained( )->cascadeOnDelete( );
			$table->foreignId( 'inventory_price_id' )->constrained( )->cascadeOnDelete( );
			$table->smallInteger( 'returned' );
			$table->text( 'comment' );
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down( ) : void {
		Schema::dropIfExists( 'inventory_reserv' );
	}
};
