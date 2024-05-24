<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest {
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
			'name'			=> [ 'required', 'min:1', 'max:255' ],
			'phone_number'	=> [ 'required', 'max:255' ],
			'car_number'	=> [ 'string', 'max:255' ],
			'comment'		=> [ 'string', 'max:3000' ]
		];
	}
}
