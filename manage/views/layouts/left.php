<?php

use manage\widgets\Menu as NavMenu;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Nav;
use mdm\admin\components\MenuHelper;

?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

         <!-- search form -->
        <!--<form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>-->
        <!-- /.search form -->
<?php

$menuItems = [];
$favouriteMenuItems[] = ['label'=>'WWW.ZCB2016.COM 清道夫债管家', 'options'=>['class'=>'header']];
$developerMenuItems = [];

if (!YII_ENV_TEST) {
	
    $developerMenuItems = [
        ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
        [
            'label' => 'Same tools',
            'icon' => 'fa fa-share',
            'url' => '#',
            'items' => [
                ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
                ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']]
            ],
        ]
    ];
}
if (Yii::$app->user->identity) {
    $menuItems[] = [
        'url' => '#',
        'icon' => 'fa fa-cog',
        'label'   => 'Developer',
        'items'   => $developerMenuItems,
        'options' => ['class' => 'treeview'],

    ];
}

echo NavMenu::widget([
    'options' => ['class' => 'sidebar-menu'],
    'items' => $favouriteMenuItems,
    //'items' => \yii\helpers\ArrayHelper::merge($favouriteMenuItems, $menuItems),
]);

$callback = function($menu){
    $data = json_decode($menu['data'], true);
    $items = $menu['children'];
    $return = [
        'label' => $menu['name'],
        'url' => [$menu['route']],
    ];
    if ($data) {
        isset($data['visible']) && $return['visible'] = $data['visible'];
        isset($data['icon']) && $data['icon'] && $return['icon'] = $data['icon'];
        $return['options'] = $data;
    }

    (!isset($return['icon']) || !$return['icon']) && $return['icon'] = 'fa fa-circle-o';
    $items && $return['items'] = $items;
    return $return;
};


echo NavMenu::widget([
    'options' => ['class' => 'sidebar-menu'],
    "items" => MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $callback),
]);

?>
    </section>
</aside>
