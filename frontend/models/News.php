<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_news".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $adminId
 * @property string $abstract
 * @property integer $category
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'create_time', 'update_time', 'adminId', 'category'], 'required'],
            [['content'], 'string'],
            [['create_time', 'update_time', 'adminId', 'category'], 'integer'],
            [['title'], 'string', 'max' => 500],
            [['abstract'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'adminId' => 'Admin ID',
            'abstract' => 'Abstract',
            'category' => 'Category',
        ];
    }
}
