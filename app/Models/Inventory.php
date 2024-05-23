<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Инвентарь
 * мангал, одеяло, кружка и т.п.
 * 
 * @property int $id
 * @property string $title
 */
class Inventory extends Model {
	use HasFactory;
	
	public $timestamps = false;
	
	public function prices( ) : HasMany {
		return $this->hasMany( InventoryPrice::class )->orderBy( 'created_at', 'desc' );;
	}
	
	public function currentPrice( ) : HasOne {
		return $this->prices( )->one( );
	}
}
