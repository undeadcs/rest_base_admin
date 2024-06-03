<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Клиент
 * 
 * @property int $id
 * @property string $name
 * @property string $phone_number
 * @property string $car_number
 * @property string $comment
 */
class Customer extends Model {
	use HasFactory;
	
	public $timestamps = false;
	
	public function orders( ) : HasMany {
		return $this->hasMany( Order::class )->orderBy( 'id', 'desc' );
	}
}
