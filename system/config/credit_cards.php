<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Credit card validation configuration.
 *
 * Options for each credit card:
 *  length - All the allowed card number lengths, in a comma separated string
 *  prefix - The digits the card needs to start with, in regex format
 *  luhn   - Enable or disable card number validation by the Luhn algorithm
 */
return [

    'default' => [
        'length' => '13,14,15,16,17,18,19',
        'prefix' => '',
        'luhn' => true,
    ],

    'american express' => [
        'length' => '15',
        'prefix' => '3[47]',
        'luhn' => true,
    ],

    'diners club' => [
        'length' => '14,16',
        'prefix' => '36|55|30[0-5]',
        'luhn' => true,
    ],

    'discover' => [
        'length' => '16',
        'prefix' => '6(?:5|011)',
        'luhn' => true,
    ],

    'jcb' => [
        'length' => '15,16',
        'prefix' => '3|1800|2131',
        'luhn' => true,
    ],

    'maestro' => [
        'length' => '16,18',
        'prefix' => '50(?:20|38)|6(?:304|759)',
        'luhn' => true,
    ],

    'mastercard' => [
        'length' => '16',
        'prefix' => '5[1-5]',
        'luhn' => true,
    ],

    'visa' => [
        'length' => '13,16',
        'prefix' => '4',
        'luhn' => true,
    ],

];
