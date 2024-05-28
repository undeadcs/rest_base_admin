<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Enums\ApartmentType;

class ApartmentRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize( ) : bool {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules( ) : array {
		return [
			'title'		=> [ 'required', 'min:1', 'max:50' ],
			'type'		=> [ Rule::enum( ApartmentType::class ) ],
			'number'	=> [ 'required', 'numeric' ],
			'capacity'	=> [ 'required', 'numeric' ],
			'price'		=> [ 'required', 'numeric' ],
			'comment'	=> [ 'string', 'max:3000' ]
		];
	}
}
