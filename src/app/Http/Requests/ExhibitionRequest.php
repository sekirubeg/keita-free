<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ExhibitionRequest extends FormRequest
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
            'name' => ['required', ],
            'price' => ['required', 'integer', 'min:0'],
            'description' => ['required', 'max:255'],
            'image_at' => ['required', 'image', 'mimes:jpeg,png', 'max:5120'],
            'tags' => ['required', 'max:3'],
            'status' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '価格は整数で入力してください',
            'price.min' => '価格は0以上の整数で入力してください',
            'description.required' => '説明文を入力してください',
            'description.string' => '説明文は文字列で入力してください',
            'image_at.required' => '画像を選択してください',
            'image_at.image' => '画像ファイルを選択してください',
            'image_at.mimes' => '画像ファイルはjpegまたはpng形式で選択してください',
            'image_at.max' => '画像ファイルは5MB以下で選択してください',
            'tags.required' => 'タグを選択してください',
            'tags.max' => 'タグは最大3つまで指定できます',

            'status.required' => '出品状態を選択してください',
        ];
    }
}
