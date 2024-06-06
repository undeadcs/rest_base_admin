<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest {
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
			'title'		=> [ 'required', 'min:1', 'max:255' ],
			'price'		=> [ 'required', 'numeric' ],
			'comment'	=> [ 'max:3000' ]
		];
	}
	
	/**
     * Get custom messages for validator errors.
     *
     * @return array
     */
	public function messages( ) : array {
		return [
			'title.required'	=> __( 'Нужно указать наименование' ),
			'price.required'	=> __( 'Нужно указать цену' )
		];
	}
}
