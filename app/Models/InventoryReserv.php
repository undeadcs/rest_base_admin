<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Инвентарь в брони апартаментов
 * 
 * @property int $inventory_id
 * @property int $reserv_id
 * @property int $inventory_price_id
 * @property bool $returned
 * @property string $comment
 */
class InventoryReserv extends Pivot {
}
