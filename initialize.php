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

        // Correctly construct the SITE_ROOT path, ensuring proper directory separator
        defined('SITE_ROOT') ? null : define('SITE_ROOT', __DIR__ );

        // Debugging the paths
        var_dump(SITE_ROOT);  // Debug SITE_ROOT
        var_dump(SITE_ROOT . DS . "includes" . DS . "config.php"); // Debug full path to config.php

        defined('INC_PATH') ? null : define('INC_PATH', SITE_ROOT . DS . 'includes');
        defined('CORE_PATH') ? null : define('CORE_PATH', SITE_ROOT . DS . 'core');
        var_dump(INC_PATH . DS . "config.php");
        // Include the necessary files
        require_once(INC_PATH . DS . "config.php");
        require_once(CORE_PATH . DS . "Post.php");
    }
}


