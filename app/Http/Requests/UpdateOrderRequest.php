<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\OrderStatus;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest {
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
			'persons_number' => [ 'numeric' ],
			'comment'		=> [ 'string', 'max:3000' ],
			'status'		=> [ Rule::enum( OrderStatus::class ) ],
			'payments.*.id'			=> [ 'required', 'integer' ],
			'payments.*.amount'		=> [ 'required', 'numeric' ],
			'payments.*.comment'	=> [ 'required', 'string' ],
			'inventories.*.id'		=> [ 'required', 'integer' ],
			'inventories.*.comment'	=> [ 'required', 'string' ],
			'inventories.*.inventory_id' => [ 'numeric' ]
		];
	}
}
