<?php
namespace AppBundle\Service;


class Singleton {
    protected static $instances;

    protected function __construct() { }

    final private function __clone() { }

    /**
     * @return static
     */
    public static function getInstance() {
        $class = get_called_class();

        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class;
        }
        return self::$instances[$class];
    }
}

