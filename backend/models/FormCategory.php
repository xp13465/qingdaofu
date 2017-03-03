<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "zcb_form_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class FormCategory extends \yii\db\ActiveRecord
{

    static $fieldTypes = [
            'string' => 'String',
            'text' => 'Text',
            'boolean' => 'Boolean',
            'select' => 'Select',
            'checkbox' => 'Checkbox'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_form_category';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'description'], 'required'],
            [['created_at', 'updated_at', 'steps'], 'integer'],
            [['name', 'type', 'description'], 'string', 'max' => 200],
            ['fields', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '表单名称',
            'type' => '表单类型',
            'description' => '表单描述',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'steps' => '工作流',
        ];
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if(!$this->fields || !is_array($this->fields)){
                $this->fields = [];
            }
            $this->fields = json_encode($this->fields);

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $attributes)
    {
        parent::afterSave($insert, $attributes);
        $this->parseFields();
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->parseFields();
    }


    public function getFieldByName($name)
    {
        foreach($this->fields as $field){
            if($field->name == $name){
                return $field;
            }
        }
        return null;
    }


    private function parseFields()
    {
        $this->fields = $this->fields !== '' ? json_decode($this->fields) : [];
    }


}
