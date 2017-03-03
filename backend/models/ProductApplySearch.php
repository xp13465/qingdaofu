<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProductApply;

/**
 * ProductApplySearch represents the model behind the search form about `app\models\ProductApply`.
 */
class ProductApplySearch extends ProductApply
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applyid', 'productid'], 'integer'],
            [['status', 'validflag','mobile', 'create_at'], 'safe'],
        ];
    }
    
	public $mobile='';
	public $applyStatus='';
	public $validflags = '';
	public $create_at = '';
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ProductApply::find();
        $query->alias('apply');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
		$startTime = isset($this->create_at)&&!$this->create_at?$this->create_at:strtotime($this->create_at .'00:00');
		$endTime = isset($this->create_at)&&!$this->create_at?$this->create_at:strtotime($this->create_at .'23:59');
		$query->andFilterWhere(['between','apply.create_at',$startTime,$endTime]);
        $query->andFilterWhere([
            'apply.applyid' => $this->applyid,
            'apply.productid' => $this->productid,
        ]);

        // $query->andFilterWhere(['like', 'status', $this->status])
            // ->andFilterWhere(['like', 'validflag', $this->validflag]);
        $query->joinWith(['product','user'=>function($query){
			$query->andFilterWhere(["user.mobile"=>$this->mobile]);
		}]);
		$this->applyStatus = $this->status;
		if(isset($this->applyStatus)&&$this->applyStatus){
				switch($this->applyStatus){
				case '1':
					$query->andFilterWhere(['apply.status'=>'10']);
				break;       
				case '2':   
					$query->andFilterWhere(['apply.status'=>'20']);
				break;       
				case '3':   
					$query->andFilterWhere(['apply.status'=>'30']);
				break;       
				case '4':   
					$query->andFilterWhere(['not between','product.status','30','40']);
					$query->andFilterWhere(['apply.status'=>'40']);
				break;       
				case '5':   
					$query->andFilterWhere(['apply.status'=>'50']);
				break;
				case '6':   
					$query->andFilterWhere(['apply.status'=>'60']);
				break;
				case '7':   
					$query->andFilterWhere(['apply.status'=>'40','product.status'=>'30']);
				break;
				case '8':   
					$query->andFilterWhere(['apply.status'=>'40','product.status'=>'40']);
				break;				
				}
			}
		$this->validflags = $this->validflag;
		if(isset($this->validflags)&&$this->validflags){
			switch($this->validflags){
			case '1':
				$query->andFilterWhere(['apply.validflag'=>'0']);
			break;       
			case '2':   
				$query->andFilterWhere(['apply.validflag'=>'1']);
			break;       	
			}
		}
		
        return $dataProvider;
    }
	public function addressAll($data,$status){
		foreach ($data as $key=>$value){
			if(isset($value['type'])&&$value['type'] == '1'){
				$data[$key]['addressLabel']= '';
				$data[$key]['addressLabel'].= isset($value['provincename'])&&$value['provincename']?$value['provincename']['province']:"";
				$data[$key]['addressLabel'].= isset($value['cityname'])&&$value['cityname']&&!in_array($value['cityname']['city'],["市辖区","县","崇明县"])?$value['cityname']['city']:"";
				$data[$key]['addressLabel'].= isset($value['areaname'])&&$value['areaname']?$value['areaname']['area']:"";
				$data[$key]['addressLabel'].= isset($value['relation_desc'])&&$value['relation_desc']?$value['relation_desc']:"";
				$data[$key]['addressLabel'] = trim(str_replace("　","",$data[$key]['addressLabel']));
				if($value['create_by'] != Yii::$app->user->getId() && in_array($status,['0','10'])){
					$data[$key]['addressLabel'] = \frontend\services\Func::getSubstrs($data[$key]['addressLabel']);
				}
			}
			if(isset($value['type'])&&$value['type'] == '2'){
				$data[$key]['brandLabel'] = '';
				$data[$key]['brandLabel'].=isset($value['brand'])&&$value['brand']?$value['brand']['name'].'-':'';
				$data[$key]['brandLabel'].=isset($value['audi'])&&$value['audi']?$value['audi']['name'].'-':'';
				$data[$key]['brandLabel'].= isset($value['relation_3'])&&$value['relation_3']=='1'?'沪牌':'非沪牌';
			}
			if(isset($value['type'])&&$value['type'] == '3'){
				$data[$key]['contractLabel'] = isset($value['relation_1'])&&$value['relation_1']?ProductMortgage::$contract[$value['relation_1']]:'';
			}
		}
		return $data;
	}
	
	/**
	* 删除接单
	*/
	public function applyDel($applyid){
		$Apply = ProductApply::findOne(['applyid'=>$applyid]);
		if(!$Apply)return PARAMSCHECK;
		$status = $Apply->applyDel($applyid);
		if($status){
			return OK;
		}else{
			$this->errors = $Order->errors;
			return MODELDATASAVE;
		}
		
	}
	
}
