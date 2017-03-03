<!DOCTYPE html>
<html lang="en">

<head>
<?php

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php  echo $this->title?></title>

    <!-- Bootstrap Core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/css/list-item.css" rel="stylesheet">
    <link href="/css/jquery-ui.css" rel="stylesheet">
    <link href="/css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="/css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/js/msgbox/jquery.msgbox.css" rel="stylesheet" type="text/css">


    <script language="JavaScript" type="text/javascript" src="/js/jquery.js"></script>
    <script language="JavaScript" type="text/javascript" src="/js/msgbox/jquery.msgbox.js"></script>
    <script language="JavaScript" type="text/javascript">var $ = jQuery;</script>
    <script language="JavaScript" type="text/javascript" src="/js/jquery-ui.js"></script>
    <script language="JavaScript" type="text/javascript" src="/js/yii.js"></script>
    <script language="JavaScript" type="text/javascript" src="/js/yii.admin.js"></script>
    <script language="JavaScript" type="text/javascript" src="/js/jquery.validate.js"></script>
    <script language="JavaScript" type="text/javascript" src="/js/card.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/js/bootstrap.min.js"></script>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">清道夫债管家总后台</a>
        </div>
        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo Yii::$app->user->getId()? Yii::$app->db->createCommand("select username from zcb_admin where id = ".Yii::$app->user->getId())->queryScalar():'';?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="<?php echo \yii\helpers\Url::to('/admin/profile')?>"><i class="fa fa-fw fa-user"></i> 资料</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="<?php echo \yii\helpers\Url::to('/site/logout')?>"><i class="fa fa-fw fa-power-off"></i>注销</a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <!--<div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li class="active">
                    <a href="/"><i class="fa fa-fw fa-dashboard"></i> 首页</a>
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#admin"><i class="fa  fa-fw fa-user"></i> 用户管理 <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="admin" class="collapse">
                        <li>
                            <a href="<?php echo \yii\helpers\Url::to('/admin/list');?>">用户列表</a>
                        </li>
                        <li>
                            <a href="<?php echo \yii\helpers\Url::to('/admin/adduser');?>">添加用户</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> 新闻管理 <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="demo" class="collapse">
                        <li>
                            <a href="<?php echo \yii\helpers\Url::to('/news/list');?>">新闻列表</a>
                        </li>
                        <li>
                            <a href="<?php echo \yii\helpers\Url::to('/news/addnews');?>">添加新闻</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#prev"><i class="fa fa-fw fa-dashboard"></i> 权限管理 <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="prev" class="collapse">
                        <li>
                            <a href="<?php echo \yii\helpers\Url::to('/menu/index');?>">菜单列表</a>
                        </li>
                        <li>
                            <a href="<?php echo \yii\helpers\Url::to('/route/index');?>">路由</a>
                        </li>
                        <li>
                            <a href="<?php echo \yii\helpers\Url::to('/rule/index');?>">规则列表</a>
                        </li>
                        <li>
                            <a href="<?php echo \yii\helpers\Url::to('/permission/index');?>">权限列表</a>
                        </li>
                        <li>
                            <a href="<?php echo \yii\helpers\Url::to('/role/index');?>">角色列表</a>
                        </li>
                        <li>
                            <a href="<?php echo \yii\helpers\Url::to('/assignment/index');?>">分配</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>-->


        <!-- /.navbar-collapse -->
        <?php
        use backend\components\MenuHelper;
        use yii\bootstrap\NavSec;
        echo NavSec::widget([
           'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id)
        ]);
        ?>
    </nav>

    <div id="page-wrapper">

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <!--<h1 class="page-header">
                        Dashboard <small>Statistics Overview</small>
                    </h1>-->
                    <ol class="breadcrumb">
                        <li class="active">
                            <i class="fa fa-dashboard"></i> <?php echo $this->context->header;?>
                        </li>
                    </ol>
                </div>
            </div>
           <?php echo $content;?>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->


</body>

</html>
