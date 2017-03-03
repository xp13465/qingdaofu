<!-- 终止发布 -->
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
    <table cellspacing="1" cellpadding="0">
        <thead>
        <tr>
            <th>区域</th>
            <th>类型</th>
            <th>编号</th>
            <th>发布时间</th>
            <th>金额(元)</th>
            <th>申请记录(次)</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($creditor as $v):?>
            <tr>
                <td><?php echo isset($v['city_id']) && $v['city_id']== 310100 ?'上海市':''?></td>
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
                <td><?php echo isset($v['code'])?$v['code']:'';?></td>
                <td><?php echo isset($v['create_time'])?date('Y-m-d',$v['create_time']):''?></td>
                <td><?php echo isset($v['money'])?$v['money']:''?></td>
                <td><?php if($pro_id){
                        if($pro_id[0]['product_id'] == $v['id']){
                            echo $pro_count;
                        }else{
                            echo 0;
                        }
                    }else{
                        echo 0;
                    }?></td>
                <td><?php echo isset($v['progress_status']) && $v['progress_status']==3 ? '处理中':''; ?></td>
                <td><a href="#" onclick="inquire('<?php echo $v['id']?>','<?php echo $v['category']?>');">查看</a></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?= linkPager::widget([
    'firstPageLabel' => '首页',
    'lastPageLabel' => '最后一页',
    'prevPageLabel' => '上一页',
    'nextPageLabel' => '下一页',
    'pagination' => $pagination,
    'maxButtonCount'=>4,
]);?>
<script type="text/javascript">
    function inquire(id,uid) {
        var user_url = '';
        if(uid == '1'){
            user_url = "<?php echo Url::toRoute('/user/financing_progress_pub')?>";
        } else if(uid == '2'|| uid == '3'){
            user_url = "<?php echo Url::toRoute('/user/coll')?>";
        } else {
            user_url = FALSE;
        }
        if(user_url) {
            location.href = user_url+'?id='+id;
        } else {
            alert('链接有问题！');
        }
    }
</script>