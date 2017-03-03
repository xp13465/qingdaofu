<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <td>用户名</td>
                    <td>是否锁定</td>
                    <td>操作</td>
                </tr>
                </thead>
                <?php foreach($data as $dd){?>
                <tr>
                    <td><?php echo $dd->username?></td>
                    <td><?php echo $dd->status?></td>
                    <td>
                        <button type="button" class="btn btn-primary">修改密码</button>
                        <button type="button" class="btn btn-success">分配角色</button>
                        <button type="button" class="btn btn-info">删除</button>
                        <button type="button" class="btn btn-warning">锁定</button>
                    </td>
                </tr>
                <?php }?>
                <tr>
            </table>

        </div>
        <div class="fenye clearfix"><?= \yii\widgets\LinkPager::widget([
                'firstPageLabel' => '首页',
                'lastPageLabel' => '最后一页',
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'pagination' => $pagination,
                'maxButtonCount'=>4,
            ]);?></div>
    </div>
</div>