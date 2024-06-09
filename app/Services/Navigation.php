<?php
namespace App\Services;

use App\Enums\TopPage;
use App\Enums\ApartmentType;

/**
 * Сервис для рендеринга навигации
 */
class Navigation {
	public function items( TopPage $currentPage ) : array {
		$items = [ ];
		
		foreach( TopPage::cases( ) as $page ) {
			$row = [
				'url'	=> $page->url( ),
				'title'	=> $page->title( )
			];
			
			if ( $page == $currentPage ) {
				$row[ 'current' ] = true;
			}
			
			$items[ ] = ( object ) $row;
		}
		
		return $items;
	}
	
	public function ApartmentTypeItems( ApartmentType $currentType ) : array {
		return [ ];
	}
}
