<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Инвентарь
 * мангал, одеяло, кружка и т.п.
 * 
 * @property int $id
 * @property string $title
 * @property int $how_pay
 */
class Inventory extends Model {
	use HasFactory;
	
	const // виды оплаты
		PAY_ONCE	= 0,
		PAY_DAILY	= 1;
	
	public $timestamps = false;
}
