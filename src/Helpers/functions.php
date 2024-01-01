<?php

use Negartarh\Persiansms\PersianSms;

if (!function_exists('persian_sms')):
    function persian_sms(?array $config = []): PersianSms
    {
        return new PersianSms($config);
    }
endif;