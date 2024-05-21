<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\ApartmentRepository;
use App\Models\Apartment;

class ApartmentsController extends Controller {
	protected ApartmentRepository $apartments;
	
	public function __construct( ApartmentRepository $apartments ) {
		$this->apartments = $apartments;
	}
	
	public function index( ) : JsonResponse {
		return response( )->json( $this->apartments->List( )->toArray( ) );
	}
	
	public function instance( Apartment $apartment ) : JsonResponse {
		return response( )->json( $apartment->toArray( ) );
	}
	
	public function prices( Apartment $apartment ) : JsonResponse {
		return response( )->json( $apartment->prices->toArray( ) );
	}
}
