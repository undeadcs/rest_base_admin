<?php
namespace App\Services;

use App\Enums\TopPage;
use App\Enums\ApartmentType;
use Illuminate\Http\Request;

/**
 * Сервис для рендеринга навигации
 */
class Navigation {
	protected Request $request;
	
	public function __construct( Request $request ) {
		$this->request = $request;
	}
	
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
	
	public function ApartmentTypeItems( ApartmentType $currentType, string $from = '', string $to = '' ) : array {
		$tmp = [ ];
		
		if ( $from ) {
			$tmp[ ] = 'f='.$from;
		}
		if ( $to ) {
			$tmp[ ] = 't='.$to;
		}
		
		$suffix = $tmp ? '&amp;'.join( '&amp;', $tmp ) : '';
		
		return [
			( object ) [
				'title' => __( 'Домики' ),
				'url' => route( 'page_'.TopPage::Main->value ).'?a='.ApartmentType::House->value.$suffix,
				'current' => $currentType == ApartmentType::House
			],
			( object ) [
				'title' => __( 'Палатки' ),
				'url' => route( 'page_'.TopPage::Main->value ).'?a='.ApartmentType::TentPlace->value.$suffix,
				'current' => $currentType == ApartmentType::TentPlace
			]
		];
	}
}
