<?php

namespace DaguConnect;

require_once __DIR__ . '/Services/Env.php';
use DaguConnect\Services\Env;

class initialize
{

    public function __construct()
    {
        new Env();

        defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
        defined('SITE_ROOT') ? null : define('SITE_ROOT', __DIR__ );

        defined('INC_PATH') ? null : define('INC_PATH', SITE_ROOT . DS . 'Includes');
        defined('CORE_PATH') ? null : define('CORE_PATH', SITE_ROOT . DS . 'Core');
        require_once __DIR__ . '/vendor/autoload.php';
        require_once __DIR__ . '/Includes/config.php';
        require_once(INC_PATH . DS . "/config.php");
    }
}


