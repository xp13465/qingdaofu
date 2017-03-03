<div class="con_people">
    <ul class="peoples clearfix">
        <?php foreach($law as $value):?>
            <li><i><a href="<?php echo yii\helpers\Url::toRoute(['/homepage/introduce','id'=>$value['id']])?>"><img src="<?php echo 'http://zichanbao.admin/'.$value['picture']?>" height="80" width="80" alt="" name="picture"  id="<?php echo $value['id']?>"></a></i><span class="f18"><?php echo isset($value['name'])?$value['name']:''?><p><?php echo common\models\ClassicCase::$occupation[$value['occupation']]?></p></span></li>
        <?php endforeach;?>
    </ul>
    <div class="left2"><span> < </span></div>
    <div class="right2"><span> > </span></div>
</div>
<ul class="cons clearfix">
    <li><span class="left3"></span></li>
    <li><p id="descindividual"><?php echo $value['abstract'];?></p></li>

    <li><span class="right3"></span></li>
</ul>
<script type="text/javascript">
    $('img[name=picture]').hover(
        function(){
            var id = $(this).attr('id');
            var url = "<?php echo yii\helpers\Url::toRoute('/homepage/descindividual')?>";

            $.ajax({
                url:url,
                type:'post',
                data:{id:id},
                dataType:'html',
                success:function(html){
                    $('#descindividual').html(html);
                }
            });
        }
    );
</script>