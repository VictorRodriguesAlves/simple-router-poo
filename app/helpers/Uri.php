<?php
declare(strict_types=1);
namespace app\helpers;

class Uri
{
    public static function getUri($type): string
    {
        return parse_url($_SERVER["REQUEST_URI"])[$type];
    }
}