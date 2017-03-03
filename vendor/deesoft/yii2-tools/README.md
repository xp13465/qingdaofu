yii2-tools
==========

Yii2 Tools

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require deesoft/yii2-tools "~1.0"
```

or add

```
"deesoft/yii2-tools": "~1.0"
```

to the require section of your `composer.json` file.


AutoHandlerBehavior
-------------------
Define event handler in self class.

```php
class User extends ActiveRecord
{
    public function onBeforeSave($event)
    {
        // execute at event beforeSave
        // do someting
    }

    public function behaviors()
    {
        return [
            'dee\tools\AutoHandlerBehavior',
        ];
    }
}
```

State
-----
Save information of client(browser).
```php
// config
'components' => [
    ...
    'profile' => 'dee\tools\State',
]
```
## Usage
```php
// this information is unique per client.
Yii::$app->profile->address = 'Jl. Buntu No 3426 Lamongan';

echo Yii::$app->profile->address;
```