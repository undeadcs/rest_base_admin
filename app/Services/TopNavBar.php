<?php
namespace App\Services;

use App\Enums\TopPage;

/**
 * Сервис для рендеринга навигации в верхей части страниц
 */
class TopNavBar {
	public static function TopPageTitle( TopPage $page ) : string {
		return match( $page ) {
			TopPage::Main			=> __( 'Календарь' ),
			TopPage::Orders			=> __( 'Заявки' ),
			TopPage::Apartments		=> __( 'Апартаменты' ),
			TopPage::Customers		=> __( 'Клиенты' ),
			TopPage::Inventories	=> __( 'Инвентарь' )
		};
	}
	
	public static function TopPageUrl( TopPage $page ) : string {
		return route( 'page_'.$page->value );
	}
	
	public function items( ) : array {
		$items = [ ];
		
		foreach( TopPage::cases( ) as $page ) {
			$items[ ] = ( object ) [
				'url' => self::TopPageUrl( $page ),
				'title' => self::TopPageTitle( $page )
			];
		}
		
		return $items;
	}
}
