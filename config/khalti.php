<?php

return [
    /**
     * The public key that you receive from khalti
     */
    'public_key' => env('test_public_key_6b9f20329f55454bb9a38e4a40f136fb'),

    /**
     * The secret key that you receive from khalti
     */
    'secret_key' => env('test_secret_key_98eed0ae1a134e1e9a5562375294ee4b'),

    /**
     * The url that is used to verify khalti payment
     */
    'verification_url' => env('KHALTI_VEFIRICATION_URL', 'https://khalti.com/api/v2/payment/verify/')
];