<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        *{ margin:0; padding:0;border-collapse:collapse; font-family:"微软雅黑";}
        div.emails table{ width:600px; margin:10px auto;}
        div.emails table tr{border:1px solid #ccc; height:40px; line-height:40px;
            font-size:14px; color:#333;}
        div.emails table tr th{ padding:5px; border: 1px solid #ccc;}
        div.emails table tr td{text-align: center;}
    </style>
</head>
<body>
<div class="emails">
    <?= $content ?>
</div>
</body>
</html>