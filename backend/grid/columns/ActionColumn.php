<?php

namespace backend\grid\columns;

use Closure;
use yii\helpers\Html;
use Yii;


class ActionColumn extends \yii\grid\DataColumn
{

    public $attribute = 'title';

    /**
     * @var string the ID of the controller that should handle the actions specified here.
     * If not set, it will use the currently active controller. This property is mainly used by
     * [[urlCreator]] to create URLs for different actions. The value of this property will be prefixed
     * to each action name to form the route of the action.
     */
    public $controller;

    /**
     * @var string the template used for composing each cell in the action column.
     * Tokens enclosed within curly brackets are treated as controller action IDs (also called *button names*
     * in the context of action column). They will be replaced by the corresponding button rendering callbacks
     * specified in [[buttons]]. For example, the token `{view}` will be replaced by the result of
     * the callback `buttons['view']`. If a callback cannot be found, the token will be replaced with an empty string.
     *
     * As an example, to only have the view, and update button you can add the ActionColumn to your GridView columns as follows:
     *
     * ```
     * ['class' => 'backend\components\TitleActionColumn', 'template' => '{view} {update}'],
     * ```
     *
     * @see buttons
     */
    //public $template = '<span class="action-title">{title}</span> <div class=""> {buttons} </div>';
    public $template = '<div class=""> {buttons} </div>';

    public $buttonsTemplate = '{update} {view} {delete}';

    public $buttons = [];

    /**
     *
     * @var callable or string
     */
    public $title;

    /**
     * @var callable a callback that creates a button URL using the specified model information.
     * The signature of the callback should be the same as that of [[createUrl()]].
     * If this property is not set, button URLs will be created using [[createUrl()]].
     */
    public $urlCreator;

    /**
     * @var array html options to be applied to the [[initDefaultButtons()|default buttons]].
     */
    public $buttonOptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initDefaultButtons();
    }

    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('zcb', 'View'),
                    'aria-label' => Yii::t('zcb', 'View'),
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                return Html::a(Yii::t('zcb', 'View'), $url, $options);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('zcb', 'Edit'),
                    'aria-label' => Yii::t('zcb', 'Edit'),
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                return Html::a(Yii::t('zcb', 'Edit'), $url, $options);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('zcb', 'Delete'),
                    'aria-label' => Yii::t('zcb', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ], $this->buttonOptions);

                return Html::a(Yii::t('zcb', 'Delete'), $url, $options);
            };
        }
    }

    public function createUrl($action, $model, $key, $index)
    {
        if ($this->urlCreator instanceof Closure) {
            return call_user_func($this->urlCreator, $action, $model, $key,
                $index);
        } else {
            $params = is_array($key) ? $key : ['id' => (string)$key];
            $params[0] = $this->controller ? $this->controller . '/' . $action : $action;

            return $params;
        }
    }


    protected function renderDataCellContent($model, $key, $index)
    {
        $buttons = preg_replace_callback('/\\{([\w\-\/]+)\\}/',
            function ($matches) use ($model, $key, $index) {
                $name = $matches[1];
                if (isset($this->buttons[$name])) {
                    $url = $this->createUrl($name, $model, $key, $index);

                    return call_user_func($this->buttons[$name], $url, $model, $key);
                } else {
                    return '';
                }
            }, $this->buttonsTemplate);


        return preg_replace_callback('/\\{([\w\-\/]+)\\}/',
            function ($matches) use ($buttons, $model) {
                $name = $matches[1];
                if ($name == 'buttons') {
                    return $buttons;
                } elseif ($name == 'title') {
                    return ($this->title instanceof Closure) ? call_user_func($this->title,
                        $model) : $this->title;
                } else {
                    return '';
                }
            }, $this->template);
    }


    protected function renderFilterCellContent()
    {
        if (is_string($this->filter)) {
            return $this->filter;
        }

        $model = $this->grid->filterModel;

        if ($this->filter !== false && $model instanceof Model && $this->attribute
            !== null && $model->isAttributeActive($this->attribute)
        ) {
            if ($model->hasErrors($this->attribute)) {
                Html::addCssClass($this->filterOptions, 'has-error');
                $error = ' ' . Html::error($model, $this->attribute,
                        $this->grid->filterErrorOptions);
            } else {
                $error = '';
            }

            return Html::activeTextInput($model, $this->attribute,
                $this->filterInputOptions) . $error;
        } else {
            return parent::renderFilterCellContent();
        }
    }




}