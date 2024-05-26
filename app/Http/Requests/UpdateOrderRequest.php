<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\OrderStatus;

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
			'status'		=> [ 'required', 'integer', 'min:'.OrderStatus::Pending->value, 'max:'.OrderStatus::Canceled->value ],
		];
	}
}
