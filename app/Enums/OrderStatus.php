<?php

namespace App\Enums;

enum OrderStatus : int {
	case Pending	= 0;
	case Accepted	= 1;
	case Canceled	= 2;
}
