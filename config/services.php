<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'whatsapp' => [
        // WhatsApp Cloud API / Meta: PHONE_NUMBER_ID and ACCESS_TOKEN
        'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
        // Optional: map internal keys to your registered template names
        'templates' => [
            'expiry_30' => env('WHATSAPP_TEMPLATE_EXPIRY_30', 'contract_reminder_30_days'),
            'expiry_15' => env('WHATSAPP_TEMPLATE_EXPIRY_15', 'contract_reminder_15_days'),
            'expiry_7' => env('WHATSAPP_TEMPLATE_EXPIRY_7', 'contract_reminder_7_days'),
            'expiry_3' => env('WHATSAPP_TEMPLATE_EXPIRY_3', 'contract_reminder_3_days'),
            'expiry_1' => env('WHATSAPP_TEMPLATE_EXPIRY_1', 'contract_reminder_1_day'),
            'expired' => env('WHATSAPP_TEMPLATE_EXPIRED', 'contract_expired'),
            // Optional per-day override mapping (day -> template name)
            'per_day' => [
                '30' => env('WHATSAPP_TEMPLATE_DAY_30', env('WHATSAPP_TEMPLATE_EXPIRY_30', 'contract_reminder_30_days')),
                '15' => env('WHATSAPP_TEMPLATE_DAY_15', 'contract_reminder_15_days'),
                '7' => env('WHATSAPP_TEMPLATE_DAY_7', env('WHATSAPP_TEMPLATE_EXPIRY_7', 'contract_reminder_7_days')),
                '3' => env('WHATSAPP_TEMPLATE_DAY_3', 'contract_reminder_3_days'),
                '1' => env('WHATSAPP_TEMPLATE_DAY_1', 'contract_reminder_1_day'),
            ],
            // backward-compatible typo alias (per_dau)
            'per_dau' => [
                '30' => env('WHATSAPP_TEMPLATE_DAY_30', env('WHATSAPP_TEMPLATE_EXPIRY_30', 'contract_reminder_30_days')),
                '15' => env('WHATSAPP_TEMPLATE_DAY_15', 'contract_reminder_15_days'),
                '7' => env('WHATSAPP_TEMPLATE_DAY_7', env('WHATSAPP_TEMPLATE_EXPIRY_7', 'contract_reminder_7_days')),
                '3' => env('WHATSAPP_TEMPLATE_DAY_3', 'contract_reminder_3_days'),
                '1' => env('WHATSAPP_TEMPLATE_DAY_1', 'contract_reminder_1_day'),
            ],
        ],
    ],

];
