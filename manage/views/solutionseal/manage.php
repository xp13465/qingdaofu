<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SolutionsealSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理单据';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solutionseal-index" >
<p>
     <?= Html::a('统计报表', ['statistics'], ['class' => 'btn btn-success']) ?>
</p>
<?php Pjax::begin(); ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
				'class' => 'yii\grid\SerialColumn',
				'headerOptions' => ['width' => '50'],
			],

            [
				'attribute' => 'personnel_name',
				'value' =>  function ($data) {
					$html = $data->personnel_name;
					if($data->personnelName && $data->personnelName == $data->personnel_name){
						$html.='(员工)';
					}else if($data->personnelName){
						$html.="({$data->personnelName})";
					}else{
						$html.='(未关联)';
					}
					return $html;
				},
				'headerOptions' => ['width' => '120'],
			],
			[
				'attribute' => 'entrust_other',
				'value' => "entrust_other",
				'headerOptions' => ['width' => '120'],
			],
			[
				'attribute' => 'account',
				'value' => function ($data) {
					return $data->account;
				},
				'headerOptions' => ['width' => '70'],
			],
			
			[
				'attribute'=>'typenum',
				'label'=>'服务佣金',
				'value'=>"fuwufei",
				'options' => ['style' => 'width:110px;'],
			],	
			[
				'attribute' => 'earnestmoney',
				'value' => function ($data) {
					return $data->earnestmoney>0?($data->actualamount.'元'):'';
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'pact',
				'value' => function ($data) {
					return $data->pact?"已确认":"无";
				},
				'headerOptions' => ['width' => '80'],
			],
			[
                'attribute' => 'status',
				'label' => '状态',
                'filter' => \app\models\Solutionseal::$status+["40,50,60,70"=>"已接单"],
                'value' => function ($data) {
					$status = \app\models\Solutionseal::$status;
					return isset($status[$data->status])?$status[$data->status]:"";
					 
                },
                'format' => 'raw',
				'options' => ['style' => 'width:100px;'],
            ],
			[
				'attribute' => 'productid',
				'filter'=>["1"=>"未生成","2"=>"已生成"],
				'format' => 'raw', 
				'value' => function ($data) {
					$html = "";
					if($data->productid){
						$html.=   "".Html::a('查看', ["/products/view","id"=>$data->productid], ['target'=>'_blank','class'=>'btnLate btn btn-info btn-xs' ,"data-productid"=>$data->productid , 'title' => '查看' ]);
					}elseif($data->status=="40"){
						$html.=   "".Html::a('生成', 'javascript:void(0)', ['class'=>'btnLate btn btn-info btn-xs' ,"data-solutionsealid"=>$data->solutionsealid, 'title' => '生成' ] );
					}else{
						$html.=   "";
					}
					return $html;
				},
				'headerOptions' => ['width' => '60'],
			],
			[
				'attribute' => 'number',
				'value' => function ($data) {
					return $data->number;
				},
				'headerOptions' => ['width' => '140'],
			],
			[
				'attribute' => 'borrowmoney',
				'value' => function ($data) {
					return $data->borrowmoney>0?($data->borrowmoney.'元'):'';
				},
				'headerOptions' => ['width' => '90'],
			],
			[
				'attribute' => 'backmoney',
				'value' => function ($data) {
					return $data->backmoney>0?($data->backmoney.'元'):'';
				},
				'headerOptions' => ['width' => '90'],
			],
			[
				'attribute' => 'actualamount',
				'value' => function ($data) {
					return $data->actualamount>0?($data->actualamount.'元'):'';
				},
				'headerOptions' => ['width' => '90'],
			],
			[
				'attribute' => 'payinterest',
				'value' => function ($data) {
					return $data->payinterest>0?($data->payinterest.'元'):'';
				},
				'headerOptions' => ['width' => '90'],
			],
			[
				'attribute' => 'closeddate',
				'value' => function ($data) {
					return $data->closeddate!="0000-00-00"?date('Y-m-d',$data->closeddate):"";
				},
				'headerOptions' => ['width' => '100'],
			],
            
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
