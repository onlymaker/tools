<?php

define('SYSTEM', __DIR__);

require_once SYSTEM . '/vendor/autoload.php';

call_user_func(function ($f3) {

    if (!$f3->log) {
        $f3->config(SYSTEM . '/cfg/system.ini');
        $f3->config(SYSTEM . '/cfg/local.ini');
        $f3->mset([
            'AUTOLOAD' => SYSTEM . '/src/',
            'LOGS' => SYSTEM . '/logs/'
        ]);

        $logger = new Log(date('Y-m-d') . '.log');

        $f3->log = function ($message, $context = []) use ($logger) {
            if (false !== strpos($message, '{') && !empty($context)) {
                $replacements = [];
                foreach ($context as $key => $val) {
                    if (is_null($val) || is_scalar($val) || (is_object($val) && method_exists($val, "__toString"))) {
                        $replacements['{' . $key . '}'] = $val;
                    } elseif (is_object($val)) {
                        $replacements['{' . $key . '}'] = '[object ' . get_class($val) . ']';
                    } else {
                        $replacements['{' . $key . '}'] = '[' . gettype($val) . ']';
                    }
                }
                $message = strtr($message, $replacements);
            }
            $logger->write($message, 'Y-m-d H:i:s');
        };
    }
}, Base::instance());
