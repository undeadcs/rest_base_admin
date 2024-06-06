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
			'car_number'	=> [ 'nullable', 'max:255' ],
			'comment'		=> [ 'nullable', 'max:3000' ]
		];
	}
	
	/**
     * Get custom messages for validator errors.
     *
     * @return array
     */
	public function messages( ) : array {
		return [
			'name.required'			=> __( 'Нужно указать имя' ),
			'phone_number.required'	=> __( 'Нужно указать телефон' )
		];
	}
}
