<?php

namespace App\Support;

class ValidationText
{
    public const MESSAGES = [
        'required' => 'Vui lòng nhập :attribute.',
        'string' => ':attribute không hợp lệ.',
        'max' => ':attribute quá dài (tối đa :max).',
        'min' => ':attribute không được nhỏ hơn :min.',
        'integer' => ':attribute phải là số nguyên.',
        'unique' => ':attribute đã tồn tại.',
        'in' => ':attribute không hợp lệ.',
        'exists' => ':attribute không tồn tại.',
        'date' => ':attribute không hợp lệ.',
    ];
}