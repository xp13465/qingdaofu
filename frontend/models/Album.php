<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%album}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $title
 * @property string $title_second
 * @property string $title_style
 * @property integer $catalog_id
 * @property integer $special_id
 * @property string $copy_from
 * @property string $copy_url
 * @property string $redirect_url
 * @property string $tags
 * @property string $view_count
 * @property string $commend
 * @property string $attach_file
 * @property string $attach_thumb
 * @property string $favorite_count
 * @property string $attention_count
 * @property string $top_line
 * @property string $reply_count
 * @property string $reply_allow
 * @property string $sort_order
 * @property string $status
 * @property string $create_time
 * @property string $update_time
 */
class Album extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%album}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'catalog_id', 'special_id', 'view_count', 'favorite_count', 'attention_count', 'reply_count', 'sort_order', 'create_time', 'update_time'], 'integer'],
            [['title'], 'required'],
            [['commend', 'top_line', 'reply_allow', 'status'], 'string'],
            [['title', 'title_second', 'title_style', 'copy_url', 'redirect_url', 'tags', 'attach_file', 'attach_thumb'], 'string', 'max' => 255],
            [['copy_from'], 'string', 'max' => 100],
        ];
    }
	
	public function getAlbumcontent()
    {
        return $this->hasOne(AlbumContent::className(), ['album_id' => 'id'])->alias("albumcontent");
    }
	
	public function getFiles()
    {
        return $this->hasOne(Files::className(), ['id' => 'attach_file'])->alias('files');
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '作者',
            'title' => '标题',
            'title_second' => '副标题',
            'title_style' => '标题样式',
            'catalog_id' => '分类',
            'special_id' => '专题编号',
            'copy_from' => '来源',
            'copy_url' => '来源url',
            'redirect_url' => '跳转URL',
            'tags' => 'tags',
            'view_count' => '查看次数',
            'commend' => '推荐',
            'attach_file' => '封面图片',
            'attach_thumb' => '封面缩略图',
            'favorite_count' => '收藏数量',
            'attention_count' => '关注次数',
            'top_line' => '头条',
            'reply_count' => '评论次数',
            'reply_allow' => '允许评论',
            'sort_order' => '排序',
            'status' => '是否显示',
            'create_time' => '添加时间',
            'update_time' => '最后更新时间',
        ];
    }

    /**
     * @inheritdoc
     * @return AlbumQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AlbumQuery(get_called_class());
    }
}
