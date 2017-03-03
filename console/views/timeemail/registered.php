<table>
    <tr>
        <th colspan="6">发布邮件(每天15:00)</th>
    </tr>
    <tr>
        <th>注册时间</th>
        <th>手机号码</th>
        <th>是否是代理人</th>
        <th>是否认证</th>
        <th>认证类型</th>
        <th>姓名 /公司名</th>
    </tr>
    <?php foreach($data as $d):
        $certification = \services\FuncServices::getCertification($d['id']);
        ?>
        <tr>
            <td><?php echo date("Y-m-d H:i:s",$d['created_at']);?></td>
            <td><?php echo $d['mobile'];?></td>
            <td><?php echo $certification && $certification['uid'] == $d['pid'] ? '代理人':'主用户'; ?></td>
            <td><?php echo $certification && $certification['state'] == 1 ?'是':'否'; ?></td>
            <td><?php if($certification && $certification['category'] == 1){
                    echo '个人';
                }else if($certification && $certification['category'] == 2){
                    echo '律师';
                }else if($certification && $certification['category'] == 3){
                    echo '公司';
                }?></td>
            <?php if($certification['uid'] != $d['pid']){?>
            <td><?php echo $certification && $certification['name']?$certification['name']:$d['username']; ?></td>
            <?php }else{ ?>
             <td><?php echo $d['username']; ?></td>
            <?php } ?>
        </tr>
    <?php
    unset($certification);
    endforeach; ?>
</table>