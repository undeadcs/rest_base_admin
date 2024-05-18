<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Цена за апартаменты
 * 
 * @property int $id
 * @property int $apartment_id
 * @property string $created_at
 * @property float $price
 */
class ApartmentPrice extends Model {
	use HasFactory;
}
