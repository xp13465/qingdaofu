<?php
namespace frontend\components;

class ActiveQuery extends \yii\db\ActiveQuery
{
    public function status($status)
    {
        $this->andWhere(['status' => (int)$status]);
        return $this;
    }

    public function desc()
    {
        $model = $this->modelClass;
        $this->orderBy([$model::primaryKey()[0] => SORT_DESC]);
        return $this;
    }

    public function asc()
    {
        $model = $this->modelClass;
        $this->orderBy([$model::primaryKey()[0] => SORT_ASC]);
        return $this;
    }


    public function sort()
    {
        $this->orderBy(['order_num' => SORT_DESC]);
        return $this;
    }

    public function sortDate()
    {
        $this->orderBy(['created_at' => SORT_DESC]);
        return $this;
    }
}