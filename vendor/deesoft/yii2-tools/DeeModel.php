<?php

namespace dee\tools;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\DynamicModel;

/**
 * Description of DeeModel
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DeeModel extends DynamicModel
{
    public static $defintions;
    private $_name;
    private $_formName;
    private $_rules;
    private $_behaviors;
    private $_methods;

    /**
     * @inheritdoc
     */
    public function __construct($name, $attributes = [], $config = [])
    {
        $this->_name = $name;
        $definition = isset(static::$defintions[$name]) ? static::$defintions[$name] : ['attrs' => $attributes];
        $attributes = array_merge(ArrayHelper::getValue($definition, 'attrs', []), $attributes);

        foreach (['formName', 'rules', 'behaviors', 'methods'] as $attr) {
            if (array_key_exists($attr, $config)) {
                $definition[$name][$attr] = $config[$attr];
                unset($config[$attr]);
            }
        }
        if (!isset(static::$defintions[$name])) {
            static::$defintions[$name] = $definition;
        }
        $this->_formName = ArrayHelper::getValue($definition, 'formName');
        $this->_rules = ArrayHelper::getValue($definition, 'rules', []);
        $this->_behaviors = ArrayHelper::getValue($definition, 'behaviors', []);
        $this->_methods = ArrayHelper::getValue($definition, 'methods', []);
        parent::__construct($attributes, $config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->_rules ? : [];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return $this->_behaviors ? : [];
    }

    /**
     * @inheritdoc
     */
    public function formName($formName = null)
    {
        if ($formName !== null) {
            $this->_formName = $formName;
        } else {
            return $this->_formName ? : 'DeeModel';
        }
    }

    /**
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if (isset($this->_methods[$name])) {
            return Yii::$container->invoke($this->_methods[$name], $params);
        } else {
            return parent::__call($name, $params);
        }
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if (in_array($name, $this->attributes()) || !array_key_exists('get' . ucfirst($name), $this->_methods)) {
            return parent::__get($name);
        }
        return Yii::$container->invoke($this->_methods['get' . ucfirst($name)], [$this]);
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (in_array($name, $this->attributes()) || !array_key_exists('set' . ucfirst($name), $this->_methods)) {
            parent::__set($name, $value);
        } else {
            return Yii::$container->invoke($this->_methods['set' . ucfirst($name)], [$this, $value]);
        }
    }
}

if (DeeModel::$defintions === null) {
    DeeModel::$defintions = ArrayHelper::getValue(Yii::$app->params, 'dee.tools.model', []);
}