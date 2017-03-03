<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zcb_evaluate".
 *
 * @property integer $id
 * @property integer $category
 * @property integer $product_id
 * @property double $serviceattitude
 * @property double $professionalknowledge
 * @property double $workefficiency
 * @property string $content
 * @property integer $uid
 * @property integer $buid
 * @property integer $pid
 * @property string $picture
 * @property integer $create_time
 * @property integer $youzhi
 * @property integer $zhuanye
 * @property integer $gaoxiao
 * @property integer $kuaijie
 * @property integer $isHide
 */
class Evaluate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_evaluate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'product_id', 'serviceattitude', 'professionalknowledge', 'workefficiency','uid', 'buid', 'create_time'], 'required'],
            [['category', 'product_id', 'uid', 'buid', 'pid', 'create_time', 'youzhi', 'zhuanye', 'gaoxiao', 'kuaijie', 'isHide','superaddition'], 'integer'],
            [['serviceattitude', 'professionalknowledge', 'workefficiency'], 'number'],
            [['content'], 'string', 'max' => 2000],
            [['picture'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'product_id' => 'Product ID',
            'serviceattitude' => 'Serviceattitude',
            'professionalknowledge' => 'Professionalknowledge',
            'workefficiency' => 'Workefficiency',
            'content' => 'Content',
            'uid' => 'Uid',
            'buid' => 'Buid',
            'pid' => 'Pid',
            'picture' => 'Picture',
            'create_time' => 'Create Time',
            'youzhi' => 'Youzhi',
            'zhuanye' => 'Zhuanye',
            'gaoxiao' => 'Gaoxiao',
            'kuaijie' => 'Kuaijie',
            'superaddition'=>'superaddition',
            'isHide' => 'Is Hide',
        ];
    }
}
