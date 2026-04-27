
<?php

return [
    'credentials' => [
        'monnifysecret' => env('MONNIFYSECRET', ''),
        'monnifycontract' => env('MONNIFYCONTRACT', ''),
        'monnifyapi' => env('MONNIFYAPI', ''),
        'monnify_base_url' => env('MONNIFY_BASE_URL', ''),
    ],
    'bank_codes' => [
        'code1' => env('BANKCODE1'),
        'code2' => env('BANKCODE2'),
        'code3' => env('BANKCODE3'),
    ],
];
