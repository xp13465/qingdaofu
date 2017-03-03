<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

/**
 * productStatus represents the model behind the search form about `app\models\product`.
 */
class ProductStatus extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productid', 'overdue', 'province_id', 'city_id', 'district_id', 'browsenumber','create_by', 'modify_at', 'modify_by'], 'integer'],
            [['mobile','number','entrust', 'entrust_other', 'type', 'status', 'validflag' ,'create_at'], 'safe'],
            [['account'], 'number'],
        ];
    }
	
	public $params=[];
	public $mobile='';
	public $account = '';
	public $create_at = '';
	public $statuss = '';
	public $validflags = '';
    
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
    public function search($params,$data=[])
    {
        $query = product::find()->alias('product');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
                'defaultOrder' => [
                    'create_at' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'product.productid' => $this->productid,
            'product.account' => isset($this->account)&&!$this->account?$this->account:$this->account*10000,
            'product.typenum' => $this->typenum,
            'product.overdue' => $this->overdue,
            'product.province_id' => $this->province_id,
            'product.city_id' => $this->city_id,
            'product.district_id' => $this->district_id,
            'product.browsenumber' => $this->browsenumber,
            'product.create_by' => $this->create_by,
            'product.modify_at' => $this->modify_at,
            'product.modify_by' => $this->modify_by,
        ]);
		$startTime = isset($this->create_at)&&!$this->create_at?$this->create_at:strtotime($this->create_at .'00:00');
		$endTime = isset($this->create_at)&&!$this->create_at?$this->create_at:strtotime($this->create_at .'23:59');
		$query->andFilterWhere(['between','product.create_at',$startTime,$endTime]);
        $query->andFilterWhere(['like', 'number', $this->number])
			->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'category_other', $this->category_other])
            ->andFilterWhere(['like', 'entrust', $this->entrust])
            ->andFilterWhere(['like', 'entrust_other', $this->entrust_other])
            ->andFilterWhere(['like', 'type', $this->type]);
            //->andFilterWhere(['like', 'status', $this->status])
            //->andFilterWhere(['like', 'validflag', $this->validflag]);
		
		$this->statuss = $this->status;
		$query->joinWith(['userMobile'=>function($query){
			$query->andFilterWhere(["userMobile.mobile"=>$this->mobile]);
		}]);
		if(isset($this->statuss)&&$this->statuss=='3'){
			$query->joinWith(['productApplies'=>function($query){
					$query->andFilterWhere(["productApplies.status"=>'20']);
			}]);
		}
		if(isset($this->statuss)&&$this->statuss){
				switch($this->statuss){
				case '1':
					$query->andFilterWhere(['product.status'=>'0']);
				break;       
				case '2':   
					$query->andFilterWhere(['product.status'=>'10']);
				break;       
				case '4':   
					$query->andFilterWhere(['product.status'=>'20']);
				break;       
				case '5':   
					$query->andFilterWhere(['product.status'=>'30']);
				break;       
				case '6':   
					$query->andFilterWhere(['product.status'=>'40']);
				break;		
				}
			}
		$this->validflags = $this->validflag;
		if(isset($this->validflags)&&$this->validflags){
			switch($this->validflags){
			case '1':
				$query->andFilterWhere(['product.validflag'=>'0']);
			break;       
			case '2':   
				$query->andFilterWhere(['product.validflag'=>'1']);
			break;       	
			}
		}
			 
			if(isset($this->statuss)&&$this->statuss=='3'){
				$query->andFilterWhere(['product.status'=>'10']);
			}
        return $dataProvider;
    }
	
	public function excelData(){
		$query = product::find()->alias('product');
		
	}
	
	/**
	* 接单协议
	*/
	public function Agreement($params=[],$uid='',$isobj=true){
		$query  = Product::find();
		$query->alias('product');
		$query->select(['product.productid','product.status','product.create_by','product.number','product.account','product.overdue']);
		if(!$isobj){
			$query->asArray();
		}
		
		$query->andFilterWhere(['product.productid'=>$params['productid'],'product.validflag'=>['1','0']]);
		//var_dump($query->asArray()->one());die;
		$query->joinWith(['agreementApply']);
		$query->joinWith(['releaseUser','fabuuser']);
        if($uid){
			$query->andFilterWhere(['product.create_by' => $uid]);
		}	
		//var_dump($query->asArray()->one());die;
       return $query;		
	}
	
	
}
