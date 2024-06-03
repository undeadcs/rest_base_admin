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
		$columns = [
			( object ) [ 'fieldName' => 'name',			'title' => __( 'Имя'			) ],
			( object ) [ 'fieldName' => 'phone_number',	'title' => __( 'Телефон'		) ],
			( object ) [ 'fieldName' => 'car_number',	'title' => __( 'Номер машины'	) ]
		];
		$paginator = $customers->List( ( int ) $request->input( 'page' ), 17 );
		
		return view( 'components.pages.'.TopPage::Customers->value, [
			'top_nav_items'	=> $this->topNavBar->items( ),
			'customers'		=> $paginator->getCollection( ),
			'columns'		=> $columns,
			'baseUrl'		=> url( '/customers' ),
			'linkFieldName'	=> 'name',
			'editFieldName'	=> 'id',
			'newEntityUrl'	=> url( '/customers/add' ),
			'currentPage'	=> $paginator->currentPage( ),
			'lastPage'		=> $paginator->isEmpty( ) ? null : $paginator->lastPage( )
		] );
	}
	
	public function add( ) : View {
		return view( 'components.pages.customer-form', [ 'top_nav_items' => $this->topNavBar->items( ), 'customer' => new Customer ] );
	}
	
	public function edit( Customer $customer, CustomerRepository $customers ) : View {
		return view( 'components.pages.customer-form', [
			'top_nav_items' => $this->topNavBar->items( ),
			'customer' => $customer
		] );
	}
}
