<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Апартаменты
 * домик/номер гостиницы и т.п.
 * 
 * @property int $id
 * @property string $title
 * @property int $number
 * @property int $capacity
 * @property string $comment
 */
class Apartment extends Model {
	use HasFactory;
	
	public $timestamps = false;
	
	public function prices( ) : HasMany {
		return $this->hasMany( ApartmentPrice::class )->orderBy( 'created_at', 'desc' );
	}
	
	public function currentPrice( ) : HasOne {
		return $this->prices( )->one( );
	}
}
