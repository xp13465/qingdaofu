<table>
    <tr>
        <th colspan="7">未接单(超过24h邮件给我，每天下午16:00)</th>
    </tr>
    <tr>
        <th>发布类型</th>
        <th>发布时间</th>
        <th>发布金额</th>
        <th>发布编号</th>
        <th>发布人</th>
        <th>处理状态</th>
        <th>手机号</th>
    </tr>
    <?php foreach($data as $d){?>
        <tr>
            <td><?php echo \services\FuncServices::$category[$d['category']];?></td>
            <td><?php echo date("Y-m-d H:i:s",$d['create_time']);?></td>
            <td><?php echo $d['money'];?></td>
            <td><?php echo $d['code'];?></td>
            <td><?php echo \Yii::$app->db->createCommand("select mobile from zcb_user where id =".$d['uid'])->queryScalar();?></td>
            <td><?php echo \services\FuncServices::$listMenu[$d['progress_status']];?></td>
            <td><?php echo \Yii::$app->db->createCommand("select mobile from zcb_user where id =".$d['uid'])->queryScalar();?></td>
        </tr>
    <?php }?>
</table>