<?php
namespace App\Enums;

/**
 * Страница верхнего уровня
 */
enum TopPage : string {
	case Main			= 'main';
	case Orders			= 'orders';
	case Apartments		= 'apartments';
	case Customers		= 'customers';
	case Inventories	= 'inventories';
	
	public function title( ) : string {
		return match( $this ) {
			TopPage::Main			=> __( 'Календарь' ),
			TopPage::Orders			=> __( 'Заявки' ),
			TopPage::Apartments		=> __( 'Апартаменты' ),
			TopPage::Customers		=> __( 'Клиенты' ),
			TopPage::Inventories	=> __( 'Инвентарь' )
		};
	}
	
	public function url( ) : string {
		return route( 'page_'.$this->value );
	}
}
