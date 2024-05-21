<?php
namespace App\Enums;

/**
 * Страница верхнего уровня
 */
enum TopPage : string {
	case Main = 'main';
	case Reservs = 'reservs';
	case Apartments = 'apartments';
	case Customers = 'customers';
	case Inventories = 'inventories';
}
