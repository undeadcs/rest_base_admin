<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Платеж
 * 
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $order_id
 * @property float $amount
 * @property string $comment
 */
class Payment extends Model {
	use HasFactory;
	
	public function order( ) : BelongsTo {
		return $this->belongsTo( Order::class );
	}
}
