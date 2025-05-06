<?php

namespace App\Http\Requests;

use Faker\Guesser\Name;
use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            //
            'name' => ['required'],
            'post_code' => ['required', 'regex:/^\d{3}-\d{4}$/' ],
            'address' => ['required'],
            'building' => ['required'],
            'image_at' => ['image', 'mimes:jpeg,png' , 'max:5120'],

        ];
    }
    public function messages()
    {
        return [
            'name.required' => '名前を入力してください',
            'post_code.required' => '郵便番号を入力してください',
            'post_code.regex' => '郵便番号はXXX-XXXXの形式で入力してください',
            'address.required' => '住所を入力してください',
            'building.required' => '建物を入力してください',
            'image_at.image' => '画像ファイルを選択してください',
            'image_at.mimes' => '画像ファイルはjpegまたはpng形式で選択してください',
            'image_at.max' => '画像ファイルは5MB以下で選択してください',
        ];
    }
}
