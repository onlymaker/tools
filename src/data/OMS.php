<?php
namespace data;

use Base as F3;
use DB\SQL;

class OMS
{
    public static function instance()
    {
        $name = 'MYSQL';

        if (!\Registry::exists($name)) {
            $f3 = F3::instance();
            \Registry::set($name, new SQL(
                $f3->get($name . '_DSN'),
                $f3->get($name . '_USER'),
                $f3->get($name . '_PASSWORD')
            ));
        }

        return \Registry::get($name);
    }
}
