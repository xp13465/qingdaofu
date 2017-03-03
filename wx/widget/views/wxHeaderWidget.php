<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 2016/4/13
 * Time: 14:22
 */
?>
<?php if($homebtn){?>
<div style="height:50px;width:50px;position:fixed;bottom:50px;z-index:99999;">
    <a href="<?php echo yii\helpers\Url::toRoute('/site/index')?>"><img src="/images/back_home.png"></a>
</div>
<?php }?>
<header>
    <div class="cm-header">
		<span class="" style='left:0;position: absolute;top: 0;' >
		<?php if($backurl){?>
			<span class="icon-back" ></span>
		<?php }?>
		<?php if($fork){ ?>
				<span class="icon-close" ></span>
		<?php } ?>
		</span>
        <code><?=$title?></code>
		
		<span class="gohtml" >
		<?php if($reload){?>
			<a href="javascript:void(0);" class="reload"><i></i></a>
		<?php }?>
		<?=$gohtml?>
		</span>
        
    </div>
<div style="height:50px;width:50px;position:fixed;bottom:50px;z-index:99999;">
	<a href="<?= yii\helpers\Url::toRoute('/site/index') ?> ">
		<img src="/images/back_home.png">
	</a>
</div>
</header>
<script type="text/javascript">
    $(document).ready(function () {
        $('.icon-back').click(function () {
			<?php if($backurl&&is_string($backurl)){?>
			 location.href = '<?=$backurl?>';
			<?php }else{?>
				history.go(-1);
			<?php }?>
			var index = layer.load(1, {
			   time:2000,
				shade: [0.4,'#fff'] //0.1透明度的白色背景
			});
        });
		$('.reload').click(function () {
			location.reload()
			var index = layer.load(1, {
			   time:2000,
				shade: [0.4,'#fff'] //0.1透明度的白色背景
			});
        });
    });
</script>