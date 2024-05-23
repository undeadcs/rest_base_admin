<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Цена за инвентарь
 * 
 * @property int $id
 * @property int $inventory_id
 * @property string $created_at
 * @property float $price
 */
class InventoryPrice extends Model {
	use HasFactory;
	
	public $timestamps = false;
	
	public function inventory( ) : BelongsTo {
		return $this->belongsTo( Inventory::class );
	}
}
