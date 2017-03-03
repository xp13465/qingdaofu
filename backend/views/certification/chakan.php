<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '用户认证信息';
?>
<div class="row">
    <div class="col-lg-6">
        <td class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <tbody>
                <?php $picture =explode(',',$certi['cardimg']);?>
                <?php if($certi->category == 1){?>
                    <tr>
                        <td>个人</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>姓名</td>
                        <td><?php echo $certi->name ?></td>
                    </tr>
                    <tr>
                        <td>身份证</td>
                        <td><?php echo $certi->cardno ?></td>
                    </tr>
                    <tr>
                        <td>图片</td>
						 <td>
                        <?php foreach($picture as $vc => $k):?>
                            <?php if($k){ ?>
                               <img  class='photoShow' width="60" height="60" src="http://<?=str_replace("http://","",Yii::$app->params['www'])?><?php echo substr($k, 1, -1)?>"/>
                            <?php } else {echo '';}?>
                        <?php endforeach;?>
						</td>
                    </tr>
                    <tr>
                        <td>邮箱</td>
                        <td><?php echo $certi->email ?></td>
                    </tr>
                    <tr>
                        <td>经典案例</td>
                        <td><?php echo $certi->casedesc ?></td>
                    </tr>
                <?php } else if($certi->category == 2){ ?>
                    <tr>
                        <td>律所</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>律所名称</td>
                        <td><?php echo $certi->name ?></td>
                    </tr>
                    <tr>
                        <td>执照许可证号</td>
                        <td><?php echo $certi->cardno ?></td>
                    </tr>
                    <tr>
                        <td>图片</td>
                        <td>
                        <?php foreach($picture as $vc => $k):?>
                            <?php if($k){ ?>
                                <img class='photoShow' width="60" height="60" src="http://<?=str_replace("http://","",Yii::$app->params['www'])?><?php echo substr($k, 1, -1)?>"/>
                            <?php } else {echo '';}?>
                        <?php endforeach;?>
                        </td>
                    </tr>
                    <tr>
                        <td>联系人</td>
                        <td><?php echo $certi->contact ?></td>
                    </tr>
                    <tr>
                        <td>联系方式</td>
                        <td><?php echo $certi->mobile ?></td>
                    </tr>
                    <tr>
                        <td>邮箱</td>
                        <td><?php echo $certi->email ?></td>
                    </tr>
                    <tr>
                        <td>经典案例</td>
                        <td><?php echo $certi->casedesc ?></td>
                    </tr>
                <?php }else{?>
                    <tr>
                        <td>公司</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>企业名称</td>
                        <td><?php echo $certi->name ?></td>
                    </tr>
                    <tr>
                        <td>营业执照号</td>
                        <td><?php echo $certi->cardno ?></td>
                    </tr>
					<tr>
                        <td>图片</td>
                        <td>
                        <?php foreach($picture as $vc => $k):?>
                            <?php if($k){ ?>
                                <img class='photoShow' width="60" height="60" src="http://<?=str_replace("http://","",Yii::$app->params['www'])?><?php echo substr($k, 1, -1)?>"/>
                            <?php } else {echo '';}?>
                        <?php endforeach;?>
                        </td>
                    </tr>
                    <tr>
                        <td>联系人</td>
                        <td><?php echo $certi->contact ?></td>
                    </tr>
                    <tr>
                        <td>联系方式</td>
                        <td><?php echo $certi->mobile ?></td>
                    </tr>
                    <tr>
                        <td>企业邮箱 </td>
                        <td><?php echo $certi->email ?></td>
                    </tr>
                    <tr>
                        <td>企业经营地址 </td>
                        <td><?php echo $certi->address ?></td>
                    </tr>
                    <tr>
                        <td>公司网站</td>
                        <td><?php echo $certi->enterprisewebsite ?></td>
                    </tr>
                    <tr>
                        <td>经典案例</td>
                        <td><?php echo $certi->casedesc ?></td>
                    </tr>





                <?php } ?>
                    </tbody>
            </table>
        </div>
    </div>
  
<script>
    $('.uploadsChe').click(function(){
        var id = "<?php echo yii::$app->request->get('id')?>";
        var pid = $(this).attr('id');
        $.msgbox({
            closeImg: '/images/close.png',
            height:500,
            width:600,
            content:"<?php echo yii\helpers\Url::to(["/product/uploadscheck"])?>?id="+id+'&pid='+pid,
            type:'ajax',
        });
    })
	$("img.photoShow").on('click',function(){
		// alert($(this).parent().find("img"))
		// alert( $(this).index())
		var   json = {
			"status": 1,
			"msg": "查看",
			"title": "查看",
			"id": 0,
			"start": $(this).index(),
			"data": []
		};
		$(this).parent().find("img.photoShow").each(function(){
			console.log( $(this))
			json.data.push({
				"alt": "",
				"pid": 0,
				"src": $(this).attr("src"),
				"thumb": ""
			})
		  
		})  
		layer.photos({
			//area: ['auto', 'auto'],
			photos: json
		});
		
	})
</script>
