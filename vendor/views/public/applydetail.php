<?php

if(in_array($category,[1,2,3])){
    $finances = \common\models\Apply::find()->where(['product_id'=>$id,'category'=>$category,'app_id'=>0])->all();
}else{

}
?>
<div class="apply">
    <span>申请记录</span>
    <i></i>
    <?php if(isset($finances[0]->id)){?>
        <div class="mytable">
            <table cellspacing="1" cellpadding="0">
                <thead>
                <tr>
                    <th>选择</th>
                    <th>区域</th>
                    <th>申请日期</th>
                    <th>姓名</th>
                    <th>联系方式</th>
                    <th>类型</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($finances as $v):
                    $user = \common\models\Certification::findOne(['uid'=>$v['uid']]);
                    ?>
                    <tr>
                        <td><input type="radio" class="idlist" name="idlist[]" value="<?php echo $v['id']?>"/></td>
                        <td><?php echo isset($v['create_time'])?(isset($v['city_id']) == 310100?'上海市':'上海市'):''?></td>
                        <td><?php echo isset($v['create_time'])?date('Y年m月d日 H:i:s',$v['create_time']):''?></td>
                        <td><?php echo isset($v['create_time'])?(isset($user['name'])?$user['name']:''):''?></td>
                        <td><?php echo isset($v['create_time'])?(isset($user['mobile'])?$user['mobile']:''):''?></td>
                        <td><?php echo \frontend\services\Func::$category[$v['category']];?></td>

                    </tr>
                <?php
                unset($user);
                endforeach;?>
                </tbody>
            </table>
        </div>
        <!-- 确认按钮 -->
        <div class="affirm">
            <a id="apply_submit">确认</a>
        </div>
    <?php }else{echo '无';}?>
</div>

<script type="text/javascript">
    $(function(){
        //确认事件
        $("#apply_submit").off().on("click", function(){
            if($("input[name='idlist[]']:checked").length > 0){
                //dopost..
                var idlist = '';
                $("input[name='idlist[]']:checked").each(function(){
                    idlist = $(this).val();
                });
                var url = "<?php echo \yii\helpers\Url::to('/apply/determinefinancing')?>";
                $.post(url,{idlist:idlist},function(v){
                    if(v == 0){
                        alert('请不要重复确认');
                    }else if(v == 1){
                        alert('已确认');
                        location.href="<?php echo \yii\helpers\Url::to('/list/termination')?>";
                    }else{
                        alert('提交失败');
                    }
                })
            } else {
                alert('请您选择要同意的用户!');
                return false;
            }
        });
    });
</script>