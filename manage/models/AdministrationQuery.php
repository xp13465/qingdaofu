<?php

namespace app\models;
use yii;
use yii\data\ActiveDataProvider;

use manage\models\Admin;
use app\models\Personnel;
use app\models\AuditLog;

/**
 * This is the ActiveQuery class for [[AuditLog]].
 *
 * @see AuditLog
 */
class AdministrationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AuditLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AuditLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
	
	public static $memoLabel = [
			'20'=>'上级领导审核成功',
			'21'=>'上级领导审核失败',
			'30'=>'总经理审核成功',
			'31'=>'总经理审核失败',
			'40'=>'人事确认',
			'50'=>'归档',
		
	];
	
	public static $supervi = [
	       '20'=>'supervisorsignature',
		   '30'=>'generalmanagersignature',
		   '40'=>'administrationsignature',
	];
	public static $signatureMemo = [
	       '20'=>'supervisormemo',
		   '30'=>'generalmanagermemo',
		   '40'=>'administrationmemo',
	];
	public static $signatureGraph = [
	       '20'=>'supervisorsignaturefile',
		   '30'=>'generalmanagersignaturefile',
		   '40'=>'administrationsignaturefile',
	];
	
	public static $signatureTime = [
	       '20'=>'supervisordate',
		   '30'=>'generalmanagerdate',
		   '40'=>'administrationdate',
	];
	
   //登录用户信息parentid
	public function username($type=false){
		$data = Admin::findIdentity(Yii::$app->user->getId());
		$query = Personnel::find();
		
		$query->alias('personnel');
			if(!$type){
					$personnel = $query->andFilterWhere(['parentid'=>$data->personnelid])->select('id')->Asarray()->all();
					$data = [];
					foreach($personnel as $key => $value){
						$data[] = $value['id'];
					}
			}else{
				$personnel = $query->andFilterWhere(['id'=>$data->personnelid])->select('name')->one();
				$data = $personnel['name'];
			}
			return $data;
	}
	
	//上级领导审核流程
	public function manageUpdate($id,$params=[]){
		if(!in_array($params['afterstatus'],['20','21','30','31','40','50']))return PARAMSCHECK;
		$model = $this->where(['id'=>$id,'validflag'=>'1'])->one();
	    if(!$model)return PARAMSCHECK;
		$uids = $this->getAuditUids($params['afterstatus']);
		if(!$uids&&in_array($params['afterstatus'],['30','40']))return NOAUDITUID;
		$status = $model->updateAll(
		[
		 'modify_at'=>time(),
		 'modify_by'=>Yii::$app->user->getId(),
		 'status'=>$params['afterstatus'],
		 'toexamineid'=>$uids,
		 $params['signatureMemo']=>$params['supervisormemo'],
		 $params['supervi']=>$params['username'],
		 $params['signatureGraph']=>$params['tpid'],
		 $params['signatureTime'] => date('Y-m-d',time()),
		],
		[
		'status'=>$params['beforestatus'],
		'validflag'=>'1','id'=>$id
		]);
	    if($status){
			$AuditLog  = new AuditLog();
			$data = [
			    'relatype'=>$params['relatype'],
				'relaid'=>$model->id,
				'afterstatus'=>$params['afterstatus'],
				'beforestatus'=>$model->status,
				'action_by'=>Yii::$app->user->getId(),
				'action_at'=>time(),
				'memo'=>$params['memo'],
			];
			$AuditLogStatus = $AuditLog->create($data);
				if($AuditLogStatus){
					return OK;
				}else{
					return MODELDATACHECK;
				}
		}else{
			return PARAMSCHECK;
		}
	}
	
	
	public function getAuditUids($status){
		$userName ="";
		switch($status){
			case '30':
			    $userName = "总经办";
				break;
			case '40':
			    $userName = "人事";
				break;
			default: 
				break;
		}
		$uids = $userName?AuthAssignment::getUserId($userName):'';
		return $uids;
	}


}
