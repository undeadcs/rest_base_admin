<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up( ) : void {
		Schema::create( 'apartments', function( Blueprint $table ) {
			$table->id( );
			$table->string( 'title' );
			$table->integer( 'number' );
			$table->integer( 'capacity' );
			$table->smallInteger( 'type' );
			$table->text( 'comment' );
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down( ): void {
		Schema::dropIfExists( 'apartments' );
	}
};
