<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Бронь апартаментов
 * 
 * @property int $id
 * @property int $customer_id
 * @property int $apartment_id
 * @property int $apartment_price_id
 * @property string $from
 * @property strong $to
 * @property int $persons_number
 * @property string $comment
 */
class Reserv extends Model {
	use HasFactory;
}
