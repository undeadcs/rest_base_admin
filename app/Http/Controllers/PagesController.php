<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Enums\TopPage;
use App\Services\TopNavBar;

class PagesController extends Controller {
	protected TopNavBar $topNavBar;
	
	public function __construct( TopNavBar $topNavBar ) {
		$this->topNavBar = $topNavBar;
	}
	
	public function main( ) : View {
		return view( 'components.pages.'.TopPage::Main->value, [
			'top_nav_items' => $this->topNavBar->items( )
		] );
	}
}
