<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Инвентарь в заявке
 * 
 * @property int $id
 * @property int $inventory_id
 * @property int $order_id
 * @property string $comment
 */
class InventoryOrder extends Pivot {
	public $incrementing = true;
}
