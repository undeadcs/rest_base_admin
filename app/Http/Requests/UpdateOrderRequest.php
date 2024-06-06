<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\OrderStatus;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends AddOrderRequest {
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
		return array_merge( parent::rules( ), [
			'status'		=> [ Rule::enum( OrderStatus::class ) ],
			'payments.*.id'			=> [ 'required', 'integer' ],
			'payments.*.amount'		=> [ 'required', 'numeric' ],
			'payments.*.comment'	=> [ 'nullable', 'max:255' ],
			'inventories.*.id'		=> [ 'required', 'integer' ],
			'inventories.*.comment'	=> [ 'nullable', 'max:255' ],
			'inventories.*.inventory_id' => [ 'numeric' ]
		] );
		/*return [
			'apartment_id'	=> [ 'required', 'integer', 'min:1' ],
			'customer_id'	=> [ 'required', 'integer', 'min:1' ],
			'from'			=> [ 'required', 'date' ],
			'to'			=> [ 'required', 'date' ],
			'persons_number' => [ 'numeric' ],
			'comment'		=> [ 'nullable', 'max:3000' ],
			'status'		=> [ Rule::enum( OrderStatus::class ) ],
			'payments.*.id'			=> [ 'required', 'integer' ],
			'payments.*.amount'		=> [ 'required', 'numeric' ],
			'payments.*.comment'	=> [ 'nullable', 'max:255' ],
			'inventories.*.id'		=> [ 'required', 'integer' ],
			'inventories.*.comment'	=> [ 'nullable', 'max:255' ],
			'inventories.*.inventory_id' => [ 'numeric' ],
			'from_hour'		=> [ 'required', 'numeric', 'min:0', 'max:23' ],
			'from_minute'	=> [ 'required', 'numeric', 'min:0', 'max:59' ],
			'to_hour'		=> [ 'required', 'numeric', 'min:0', 'max:23' ],
			'to_minute'		=> [ 'required', 'numeric', 'min:0', 'max:59' ]
		];*/
	}
	
	/**
     * Get custom messages for validator errors.
     *
     * @return array
     */
	public function messages( ) : array {
		return array_merge( parent::messages( ), [
			'status'						=> __( 'Ошибка в указании статуса' ),
			'payments.*.id.required'		=> __( 'Нужно указать ID платежа' ),
			'payments.*.amount.required'	=> __( 'Нужно указать сумму платежа' ),
			'inventories.*.id.required'		=> __( 'Нужно указать инвенарь' ),
		] );
	}
}
