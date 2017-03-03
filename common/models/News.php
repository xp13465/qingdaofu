<?php

namespace common\models;

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
 */
class News extends \yii\db\ActiveRecord
{
	public static $category = [
		"0"=>"默认新闻",
		"1"=>"公告",
		"2"=>"公司新闻",
		"3"=>"财经资讯",
		"4"=>"行业动态",
	];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_news';
    }

    public static $notice = ['0'=>'新闻','1'=>'公告'];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'create_time', 'update_time', 'adminId','category'], 'required'],
            [['content','abstract'], 'string'],
            [['create_time', 'update_time', 'adminId'], 'integer'],
            [['title'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'title' => '标题',
            'content' => '内容',
            'create_time' => '发布时间',
            'update_time' => '更新时间',
            'adminId' => '操作人ID',
            'abstract'=>'摘要',
            'category' => '0为新闻，1为公告',
        ];
    }
}
