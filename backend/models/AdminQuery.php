<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Album]].
 *
 * @see Album
 */
class AdminQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Album[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Album|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
