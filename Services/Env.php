<?php

    namespace DaguConnect\Services;

    class Env
    {
        protected static $loaded = false;
        public static function load()
        {
            if (!self::$loaded) {
                require_once __DIR__ . '/../vendor/autoload.php';
                $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
                $dotenv->load();
                self::$loaded = true;
            }
        }

        public function __construct()
        {
            self::load();
        }
    }
