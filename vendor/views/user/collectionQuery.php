<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="details">
    <div class="look">
        <h3>查看信息</h3>
    </div>
    <!-- 融资详情 -->
    <div class="finance">

        <?php echo $this->renderFile('@app/views/public/pubdetail.php',['category'=>2,'id'=>$id])?>

    </div>
    <!-- 处置方详情 -->
    <div class="apply">
        <span>申请记录</span>
        <i></i>
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
                <?php foreach($credis as $v):?>
                    <tr>
                        <td><input type="radio" class="idlist" name="idlist[]" value="<?php echo $v['uid']?>,<?php echo $v['id']?>,<?php echo $v['bid']?>"/></td>
                        <td><?php echo isset($v['create_time'])?(isset($v['city_id']) == 310100?'上海市':''):''?></td>
                        <td><?php echo isset($v['create_time'])?date('Y年m月d日 H:i:s',$v['create_time']):''?></td>
                        <td><?php echo isset($v['create_time'])?(isset($v['name'])?$v['name']:''):''?></td>
                        <td><?php echo isset($v['create_time'])?(isset($v['mobile'])?$v['mobile']:''):''?></td>
                        <td><?php switch(isset($v['category'])?$v['category']:''){
                                case 1:
                                    echo '融资';
                                    break;
                                case 2;
                                    echo '清收';
                                    break;
                                case 3;
                                    echo '诉讼';
                                default://保留
                                    break;
                            }?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <!-- 确认按钮 -->
        <div class="affirm">
            <a id="apply_submit">确认</a>
        </div>
    </div>
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
                var url = "<?php echo Url::to('/user/determine_user')?>";
                $.post(url,{idlist:idlist},function(v){
                    if(v == 0){
                        alert('请不要重复确认');
                    }else if(v == 1){
                        alert('已确认');
                        location.href="<?php echo Url::to('/inquire/termination')?>";
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
