<?php
use yii\helpers\Url;
use yii\helpers\Html;

$headImg = (!Yii::$app->user->isGuest&&Yii::$app->user->identity->files?Yii::$app->user->identity->files->file:($directoryAsset.'/img/user2-160x160.jpg'));
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?=$headImg?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= Yii::$app->user->identity->personnel?Yii::$app->user->identity->personnel->name:Yii::$app->user->identity->username ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
							<img src="<?=$headImg?>"  class="img-circle" alt="User Image"/>
							
							<p>
						<?php if (!Yii::$app->user->isGuest) { ?>
								<?= Yii::$app->user->identity->getRoleName() ?>
						<?php } ?>

                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
							<?= Html::a('资料', ['/admin/profile'], ['class' => 'btn btn-default btn-flat']) ?>
                            </div>
                            <div class="pull-right">
								<?= Html::a(
                                    '修改密码',
                                    ['/site/changepwd'],
                                    [ 'class' => 'btn btn-default btn-flat']
                                ) ?>
                                <?= Html::a(
                                    '注销',
                                    ['/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>
