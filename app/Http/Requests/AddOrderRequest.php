<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddOrderRequest extends FormRequest {
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
			'apartment_id'	=> [ 'required', 'integer', 'min:1' ],
			'customer_id'	=> [ 'required', 'integer', 'min:1' ],
			'from'			=> [ 'required', 'date' ],
			'to'			=> [ 'required', 'date' ],
			'persons_number' => [ 'required', 'numeric' ],
			'comment'		=> [ 'string', 'max:3000' ],
			'from_hour'		=> [ 'required', 'numeric', 'min:0', 'max:23' ],
			'from_minute'	=> [ 'required', 'numeric', 'min:0', 'max:59' ],
			'to_hour'		=> [ 'required', 'numeric', 'min:0', 'max:23' ],
			'to_minute'		=> [ 'required', 'numeric', 'min:0', 'max:59' ]
		];
	}
}
