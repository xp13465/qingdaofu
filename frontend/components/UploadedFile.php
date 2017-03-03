<?php
namespace frontend\components;


class UploadedFile extends \yii\web\UploadedFile
{
    public static function getInstance($model, $attribute)
    {
        return static::getInstanceByName($attribute);
    }
}