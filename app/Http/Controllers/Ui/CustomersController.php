<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Navigation;
use App\Repositories\CustomerRepository;
use Illuminate\View\View;
use App\Enums\TopPage;
use App\Models\Customer;

class CustomersController extends Controller {
	protected Navigation $navigation;
	
	public function __construct( Navigation $navigation ) {
		$this->navigation = $navigation;
	}
	
	public function index( Request $request, CustomerRepository $customers ) : View {
		return view( 'components.pages.'.TopPage::Customers->value, [
			'top_nav_items'	=> $this->navigation->items( TopPage::Customers ),
			'paginator'		=> $customers->List( ( int ) $request->input( 'page' ), 17 )
		] );
	}
	
	public function add( ) : View {
		return view( 'components.pages.customer-form', [ 'top_nav_items' => $this->navigation->items( TopPage::Customers ), 'customer' => new Customer ] );
	}
	
	public function edit( Customer $customer, Request $request, CustomerRepository $customers ) : View {
		return view( 'components.pages.customer-form', [
			'top_nav_items'	=> $this->navigation->items( TopPage::Customers ),
			'customer'		=> $customer,
			'orders'		=> $customers->ListOrdersWithApartment( $customer, ( int ) $request->input( 'page' ) )
		] );
	}
}
