<?php

namespace App\Enums;

enum ApartmentType : int {
	case House		= 0;
	case TentPlace	= 1;
	case HotelRoom	= 2;
	
	public function title( ) : string {
		return match( $this ) {
			self::House		=> __( 'Домик' ),
			self::TentPlace	=> __( 'Место для палатки' ),
			self::HotelRoom	=> __( 'Гостиничный номер' )
		};
	}
}
