<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */


    public function rules(): array
    {
        return [
            //
            'payment' => ['required', 'in:1,2'],
            'post_code' => [],
            'address' => [],
        ];
    }
    public function messages(): array
    {
        return [
            'payment.required' => '支払い方法を選択してください。',
            'payment.in'       => '選択された支払い方法が無効です。',
            'post_code.required' => '郵便番号を入力してください。',
            'address.required' => '住所を入力してください。',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = Auth::user();
            $hasSessionPostCode = session('purchase_post_code') ;
            $hasSessionAddress = session('purchase_address');
            $hasUserPostCode = $user->post_code;
            $hasUserAddress = $user->address;

            if (!$hasSessionPostCode && !$hasUserPostCode) {
                $validator->errors()->add('post_code', '郵便番号が未入力です。');
            }

            if (!$hasSessionAddress && !$hasUserAddress) {
                $validator->errors()->add('address', '住所が未入力です。');
            }
        });
    }
}
