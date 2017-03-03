<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/gallery/css/normalize.css">
    <link rel="stylesheet" href="/gallery/css/swipebox.css">
    <link rel="stylesheet" href="/js/msgbox/jquery.msgbox.css">
    <link rel="stylesheet" type="text/css" href="/gallery/css/default.css">
    <style type="text/css">
        #gallery-wrapper {
            position: relative;
            max-width: 75%;
            width: 75%;
            margin:50px auto;
        }
        img.thumb {
            width: 100%;
            max-width: 100%;
            height: auto;
        }
        .white-panel {
            position: absolute;
            background: white;
            border-radius: 5px;
            box-shadow: 0px 1px 2px rgba(0,0,0,0.3);
            padding: 10px;
        }
        .white-panel h1 {
            font-size: 1em;
        }
        .white-panel h1 a {
            color: #A92733;
        }
        .white-panel:hover {
            box-shadow: 1px 1px 10px rgba(0,0,0,0.5);
            margin-top: -5px;
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            -o-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }
        .wf{position:fixed;top:0;z-index:9;width:100%;max-width:640px;}
        .fh{width:100%;max-width:640px;margin:0 auto;background:#494A5F;line-height:50px;position:relative;overflow:hidden;}
        .bianji{float:right;margin-right:10px;}
        .bianji a{color:#fff;}
        .fh .icon-back{border-left: 2px solid #fff;border-bottom: 2px solid #fff;}

    </style>
    <!--[if IE]>
    <script src="http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js"></script>
    <![endif]-->
</head>
<style>
.sc_icon a{width:100%;background:#0065b3;line-height:50px;text-align: center;color:#fff;position:fixed;bottom:0;display:block;}
</style>
<body>
<header class="wf">
    <div class="fh">
        <span class="icon-back"></span>
        <div class="bianji">
            <a href="#">编辑</a>
            <a href="#">删除</a>
        </div>
    </div>
</header>

<section id="gallery-wrapper" class="main_image">
    <?php
        $str = Yii::$app->request->post('str');
        $data = explode(',',$str);
        foreach($data as $dd){
            if($dd != 'null' && $dd != null){
    ?>
            <article class="white-panel">
                <a class = "swipebox" href="<?php echo Yii::$app->params['wx'].trim($dd,"'");?>"><img src="<?php echo Yii::$app->params['wx'].trim($dd,"'");?>" class="thumb"></a>
            </article>
            <?php
             }
        }
    ?>
</section>
<div class="sc_icon">
    <?php echo \yii\helpers\Html::hiddenInput(Yii::$app->request->post('name'),Yii::$app->request->post('str'));?>
    <a href="#">上传</a>
</div>

<script src="/gallery/js/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="/gallery/js/jquery.swipebox.js"></script>
<script src="/gallery/js/pinterest_grid.js"></script>
<script src="/js/msgbox/jquery.msgbox.js"></script>
<script type="text/javascript">
    $(function(){
        $("#gallery-wrapper").pinterest_grid({
            no_columns: 3,
            padding_x: 3,
            padding_y: 3,
            margin_bottom: 50,
            single_column_breakpoint: 250
        });
        $(".swipebox").swipebox({
            useCSS : true, // false will force the use of jQuery for animations
            hideBarsDelay : 3000 // 0 to always show caption and action bar
        });

        $(document).delegate('.sc_icon', 'click', function () {
            var w = $(window).width() ;
            var h = $(window).height() ;
            var name = $(this).children('input:hidden').attr("name");
            $.msgbox({
                closeImg: '/images/close.png',
                async: false,
                width: w * 0.9,
                height: h * 0.8,
                title: '请选择图片',
                content: "<?php echo \yii\helpers\Url::toRoute(["/common/uploadimagegallery"])?>/?type=" + name,
                type: 'ajax'
            });
        });

        $('.icon-back').bind('touchstart click',function () {
             history.go(-1);
        });
    });


</script>
</body>
</html>