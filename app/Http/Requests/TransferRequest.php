<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from_user_id' => ['required', 'integer', 'exists:users,id'],
            'to_user_id'   => ['required', 'integer', 'exists:users,id', 'different:from_user_id'],
            'amount'       => ['required', 'numeric', 'min:0.01'],
            'comment'      => ['nullable', 'string', 'max:255'],
        ];
    }


    public function messages(): array
    {
        return [
            'from_user_id.required' => 'Поле "Отправитель" обязательно.',
            'from_user_id.integer'  => 'ID отправителя должен быть числом.',
            'from_user_id.exists'   => 'Пользователь-отправитель не найден.',

            'to_user_id.required'   => 'Поле "Получатель" обязательно.',
            'to_user_id.integer'    => 'ID получателя должен быть числом.',
            'to_user_id.exists'     => 'Пользователь-получатель не найден.',
            'to_user_id.different'  => 'Отправитель и получатель не могут совпадать.',

            'amount.required'       => 'Сумма перевода обязательна.',
            'amount.numeric'        => 'Сумма должна быть числом.',
            'amount.decimal'        => 'Сумма должна иметь максимум 2 знака после запятой.',
            'amount.min'            => 'Сумма перевода должна быть больше 0.',

            'comment.string'        => 'Комментарий должен быть строкой.',
            'comment.max'           => 'Комментарий не должен превышать 255 символов.',
        ];
    }
}
