<?php

namespace dee\tools;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\base\ModelEvent;
use yii\db\AfterSaveEvent;

/**
 * Description of StateChangeBehavior
 *
 * @property ActiveRecord $owner
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class StateChangeBehavior extends Behavior
{
    /**
     *
     * @var string
     */
    public $attribute = 'status';
    /**
     *
     * @var string
     */
    public $whenInsert = ActiveRecord::EVENT_AFTER_INSERT;
    /**
     * Status state, [old_status, new_status, handler]
     * ```
     * [
     *     [null, Purchase::STATUS_APPLY, 'apply'],
     *     [Purchase::STATUS_DRAFT, Purchase::STATUS_APPLY, 'apply'],
     *     [Purchase::STATUS_APPLY, Purchase::STATUS_DRAFT, 'revert'],
     * ]
     * ```
     * @var array
     */
    public $states = [];
    private $_status;
    private $_messages = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return[
            $this->whenInsert => 'onInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'onUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'onDelete',
        ];
    }

    /**
     * Get status change
     * @return boolean
     */
    public function getStateChanged()
    {
        return $this->_status;
    }

    /**
     * Get status change message.
     * @return string
     */
    public function getStateMessage()
    {
        return reset($this->_messages);
    }

    /**
     * Add state change message.
     * @param string $message
     */
    public function addStateMessage($message)
    {
        $this->_messages[] = $message;
    }

    /**
     *
     * @param ModelEvent|AfterSaveEvent $event
     */
    public function onInsert($event)
    {
        if ($this->_status === null) {
            $this->_status = true;
        }
        $this->_messages = [];
        $model = $this->owner;
        $attribute = $this->attribute;
        if (($new = $model->$attribute) != null) {
            foreach ($this->states as $state) {
                if ($state[0] == null && $state[1] == $new) {
                    $handler = is_string($state[2]) ? [$model, $state[2]] : $state[2];
                    $result = call_user_func_array($handler, array_slice($state, 3));
                    if ($result === false) {
                        if ($event instanceof ModelEvent) {
                            $event->isValid = false;
                        } else {
                            if (count($this->_messages)) {
                                $message = $this->_messages[0];
                            } else {
                                $message = is_string($state[2]) ? "Error at '{$state[2]}'" : 'Error after insert';
                            }
                            throw new StateChangeException($message);
                        }
                        $this->_status = false;
                        return;
                    }
                }
            }
        }
    }

    /**
     *
     * @param ModelEvent $event
     */
    public function onUpdate($event)
    {
        $this->_status = true;
        $model = $this->owner;
        $attribute = $this->attribute;
        $dirty = $model->getDirtyAttributes([$attribute]);
        if (isset($dirty[$attribute])) {
            $new = $dirty[$attribute];
            $old = $model->getOldAttribute($attribute);
            foreach ($this->states as $state) {
                if ($state[0] == $old && $state[1] == $new) {
                    $handler = is_string($state[2]) ? [$model, $state[2]] : $state[2];
                    $result = call_user_func_array($handler, array_slice($state, 3));
                    if ($result === false) {
                        $event->isValid = false;
                        $this->_status = false;
                        return;
                    }
                }
            }
        }
    }

    /**
     *
     * @param ModelEvent $event
     */
    public function onDelete($event)
    {
        $this->_status = true;
        $model = $this->owner;
        $attribute = $this->attribute;
        if (($old = $model->$attribute) != null) {
            foreach ($this->states as $state) {
                if ($state[0] == $old && $state[1] == null) {
                    $handler = is_string($state[2]) ? [$model, $state[2]] : $state[2];
                    $result = call_user_func_array($handler, array_slice($state, 3));
                    if ($result === false) {
                        $event->isValid = false;
                        $this->_status = false;
                        return;
                    }
                }
            }
        }
    }
}
