<?php
namespace App\Enums;

/**
 * Вид оплаты
 */
enum PayType : int {
	case Daily = 0;
	case Once = 1;
}