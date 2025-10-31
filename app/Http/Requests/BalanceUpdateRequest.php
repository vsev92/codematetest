<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BalanceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount'  => ['required', 'numeric', 'min:0.01'],
            'comment' => ['nullable', 'string', 'max:255'],
        ];
    }


    public function messages(): array
    {
        return [
            'user_id.required' => 'ID пользователя обязателен',
            'user_id.integer'  => 'ID пользователя должен быть числом',
            'user_id.exists'   => 'Пользователь с таким ID не найден',
            'amount.required'  => 'Сумма обязателена',
            'amount.numeric'   => 'Сумма должна быть числом',
            'amount.decimal'   => 'Сумма должна быть числом с максимум 2 знаками после запятой',
            'amount.min'       => 'Сумма должна быть больше 0',
            'comment.string'   => 'Комментарий должен быть строкой',
            'comment.max'      => 'Комментарий не должен превышать 255 символов',
        ];
    }
}
