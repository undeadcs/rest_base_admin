<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up( ) : void {
		Schema::create( 'orders', function( Blueprint $table ) {
			$table->id( );
			$table->timestamps( );
			$table->foreignId( 'customer_id' )->constrained( )->cascadeOnDelete( );
			$table->foreignId( 'apartment_id' )->constrained( )->cascadeOnDelete( );
			$table->foreignId( 'apartment_price_id' )->constrained( )->cascadeOnDelete( );
			$table->smallInteger( 'status' );
			$table->timestamp( 'from' );
			$table->timestamp( 'to' );
			$table->smallInteger( 'persons_number' );
			$table->text( 'comment' );
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down( ) : void {
		Schema::dropIfExists( 'orders' );
	}
};
