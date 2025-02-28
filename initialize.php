<?php

namespace DaguConnect;

require_once __DIR__ . '/Services/Env.php';
use DaguConnect\Services\Env;

class initialize
{
    public function __construct()
    {
        $this->defineConstants();
        $this->loadDependencies();
    }

    private function defineConstants(): void
    {
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);
        defined('SITE_ROOT') or define('SITE_ROOT', __DIR__);
        defined('INC_PATH') or define('INC_PATH', SITE_ROOT . DS . 'Includes');
        defined('CORE_PATH') or define('CORE_PATH', SITE_ROOT . DS . 'Core');
    }

    private function loadDependencies(): void
    {
        require_once __DIR__ . '/vendor/autoload.php';
        Env::load();
        require_once INC_PATH . DS . 'config.php';
    }
}
