<div class="row">
    <form action="<?php echo \yii\helpers\Url::to('/admin/adduser')?>" method="post">
    <div class="col-lg-6  text-center">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <td colspan="2">添加用户</td>
                </tr>
                </thead>
                <tr>
                    <td align="left">用户名：</td>
                    <td><input class="form-control" placeholder="用户名" name ="username"></td>
                </tr>
                <tr>
                    <td align="left">密码：</td>
                    <td><input class="form-control" placeholder="密码" type="password" name ="password_hash"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit" class="btn btn-primary">添加</button>
                        <button type="reset" class="btn btn-success">重置</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    </form>
</div>