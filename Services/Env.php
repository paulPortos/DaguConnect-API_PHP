<?php

    namespace DaguConnect\Services;

    use Dotenv\Dotenv;

    class Env
    {
        protected static bool $loaded = false;
        public static function load(): void
        {
            if (!self::$loaded) {
                require_once __DIR__ . '/../vendor/autoload.php';
                $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
                $dotenv->load();
                self::$loaded = true;
            }
        }

        public function __construct()
        {
            self::load();
        }
    }
