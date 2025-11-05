<?php

return [
    'required' => 'The :attribute field is required.',
    'unique' => 'The :attribute has already been taken.',
    'email' => 'The :attribute must be a valid email address.',
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'max' => [
        'string' => 'The :attribute may not be greater than :max characters.',
    ],

    'attributes' => [
        'name' => 'name',
        'username' => 'username',
        'email' => 'email',
        'password' => 'password',
        'phone' => 'phone',
        'display_name' => 'display name',
        'iso_code' => 'ISO code',
        'national_number' => 'dial code',
    ],
];
