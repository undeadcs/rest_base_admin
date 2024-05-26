<?php

namespace App\Enums;

enum OrderStatus : int {
	case Pending	= 0;
	case Active		= 1;
	case Closed		= 2;
	case Canceled	= 3;
	
	public function title( ) : string {
		return match( $this ) {
			self::Pending	=> __( 'Ожидание' ),
			self::Active	=> __( 'Активен' ),
			self::Closed	=> __( 'Закрыт' ),
			self::Canceled	=> __( 'Отменен' )
		};
	}
}
