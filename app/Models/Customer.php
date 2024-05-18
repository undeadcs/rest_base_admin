<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
