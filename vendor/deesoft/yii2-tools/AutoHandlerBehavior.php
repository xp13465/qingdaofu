<?php

namespace dee\tools;

use Yii;
use yii\base\Behavior;
use yii\caching\Cache;

/**
 * Description of AutoHandlerBehavior
 *
 * @author dee
 */
class AutoHandlerBehavior extends Behavior
{
    /**
     *
     * @var Cache
     */
    public static $cache = 'cache';
    private static $_classes;

    /**
     *
     * @param \yii\base\Component $owner
     */
    public function attach($owner)
    {
        $this->owner = $owner;
        static::load();
        $class = get_class($owner);
        if (!isset(self::$_classes[$class])) {
            self::initClass($class);
        }
        foreach (self::$_classes[$class] as $event => $handler) {
            $owner->on($event, [$owner, $handler]);
        }
    }

    public function detach()
    {
        if ($this->owner) {
            foreach (self::$_classes[$class] as $event => $handler) {
                $this->owner->off($event, [$this->owner, $handler]);
            }
            $this->owner = null;
        }
    }

    /**
     *
     * @return Cache
     */
    private static function getCache()
    {
        if (is_string(self::$cache)) {
            self::$cache = Yii::$app->get(self::$cache, false);
        }
        return self::$cache;
    }

    private static function load()
    {
        if (self::$_classes === null) {
            if (($cache = self::getCache()) === null || (self::$_classes = $cache->get(__CLASS__)) === false) {
                self::$_classes = [];
            }
        }
    }

    private static function initClass($class)
    {
        self::$_classes[$class] = [];
        $ref = new \ReflectionClass($class);
        /* @var $method \ReflectionMethod */
        foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->isStatic() || strncmp($method->name, 'on', 2) !== 0) {
                continue;
            }
            self::$_classes[$class][lcfirst(substr($method->name, 2))] = $method->name;
        }
        if (($cache = self::getCache()) !== null) {
            $cache->set(__CLASS__, self::$_classes);
        }
    }
}
