<?php

namespace DaguConnect;

require_once '../DaguConnect-API_PHP/Services/Env.php';
use Services\Env;

class initialize
{

    public function __construct()
    {
        new Env();

        defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
        defined('SITE_ROOT') ? null : define('SITE_ROOT', __DIR__ );

        defined('INC_PATH') ? null : define('INC_PATH', SITE_ROOT . DS . 'includes');
        defined('CORE_PATH') ? null : define('CORE_PATH', SITE_ROOT . DS . 'core');

        require_once(INC_PATH . DS . "config.php");
        require_once(CORE_PATH . DS . "Post.php");
    }
}


