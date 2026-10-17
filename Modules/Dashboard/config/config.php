<?php

return [
    'name' => 'Dashboard',

    /*
     | Editable system settings (Dashboard > General > System Settings).
     | Add a group or a field here and it appears on the page.
     |
     | Stored under "group.field". `secret` fields are masked and never sent
     | back to the browser. `config` is the config() key the value overrides;
     | when nothing is stored, the .env value behind that key still applies.
     | Labels are translation keys.
     */
    'settings' => [
        'kavenegar' => [
            'label' => 'dashboard::strings.Kavenegar SMS',
            'fields' => [
                'api_key' => [
                    'label' => 'dashboard::strings.Kavenegar API Key',
                    'secret' => true,
                    'config' => 'services.kavenegar.key',
                ],
                'sender' => [
                    'label' => 'dashboard::strings.Kavenegar Sender Number',
                    'config' => 'services.kavenegar.sender',
                    'rules' => 'nullable|string|max:20',
                ],
                'otp_template' => [
                    'label' => 'dashboard::strings.Kavenegar OTP Template',
                    'config' => 'services.kavenegar.otp_template',
                ],
            ],
        ],
    ],
];
