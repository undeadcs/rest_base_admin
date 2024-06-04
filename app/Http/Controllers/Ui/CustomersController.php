<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TopNavBar;
use App\Repositories\CustomerRepository;
use Illuminate\View\View;
use App\Enums\TopPage;
use App\Models\Customer;

class CustomersController extends Controller {
	protected TopNavBar $topNavBar;
	
	public function __construct( TopNavBar $topNavBar ) {
		$this->topNavBar = $topNavBar;
	}
	
	public function index( Request $request, CustomerRepository $customers ) : View {
		return view( 'components.pages.'.TopPage::Customers->value, [
			'top_nav_items'	=> $this->topNavBar->items( ),
			'paginator'		=> $customers->List( ( int ) $request->input( 'page' ), 17 )
		] );
	}
	
	public function add( ) : View {
		return view( 'components.pages.customer-form', [ 'top_nav_items' => $this->topNavBar->items( ), 'customer' => new Customer ] );
	}
	
	public function edit( Customer $customer, Request $request, CustomerRepository $customers ) : View {
		return view( 'components.pages.customer-form', [
			'top_nav_items'	=> $this->topNavBar->items( ),
			'customer'		=> $customer,
			'orders'		=> $customers->ListOrdersWithApartment( $customer, ( int ) $request->input( 'page' ) )
		] );
	}
}
