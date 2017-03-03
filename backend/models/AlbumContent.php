<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%album_content}}".
 *
 * @property string $album_id
 * @property string $album_list
 * @property string $introduce
 * @property string $content
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 */
class AlbumContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%album_content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['album_id', ], 'required'],
            [['album_id'], 'integer'],
            [['album_list', 'introduce', 'content', 'seo_description'], 'string'],
            [['seo_title', 'seo_keywords'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'album_id' => '图集id',
            'album_list' => '组图',
            'introduce' => '摘要',
            'content' => '内容',
            'seo_title' => 'SEO标题',
            'seo_keywords' => 'SEO关键字',
            'seo_description' => 'SEO描述',
        ];
    }
	
}