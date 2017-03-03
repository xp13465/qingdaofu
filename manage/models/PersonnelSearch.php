<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Personnel;
use app\models\Department;
use app\models\Post;

/**
 * PersonnelSearch represents the model behind the search form about `app\models\Personnel`.
 */
class PersonnelSearch extends Personnel 
{ 
	public $created_at = '';
    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [ 
            [['id', 'headimg', 'parentid', 'organization_id', 'department_pid', 'department_id', 'post_id', 'updated_at', 'created_by', 'modifyd_by'], 'integer'],
            [['name', 'job', 'employeeid', 'mobile', 'tel', 'email', 'address', 'office', 'validflag', 'created_at'], 'safe'],
        ]; 
    } 

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
        $query = Personnel::find(); 

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
        $query->andFilterWhere([
            'id' => $this->id,
            'headimg' => $this->headimg,
            'parentid' => $this->parentid,
            'organization_id' => $this->organization_id,
            'department_pid' => $this->department_pid,
            'department_id' => $this->department_id,
            'post_id' => $this->post_id,
			//'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'modifyd_by' => $this->modifyd_by,
        ]);
		$startTime = isset($this->created_at)&&!$this->created_at?$this->created_at:strtotime($this->created_at .'00:00');
		$endTime = isset($this->created_at)&&!$this->created_at?$this->created_at:strtotime($this->created_at .'23:59');
	    $query->andFilterWhere(['between','created_at',$startTime,$endTime]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'job', $this->job])
            ->andFilterWhere(['like', 'employeeid', $this->employeeid])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'office', $this->office])
            ->andFilterWhere(['like', 'validflag', $this->validflag]);

        return $dataProvider; 
    } 
	//部门数据查询
	public function department($organization_id,$department_id,$types){
			$html = [];
		    if(isset($department_id)&&$department_id){
				$data = Department::find()->where(['status'=>'1','validflag'=>'1','pid'=>$department_id,'organization_id'=>$organization_id])->select("id,pid,name")->asArray()->all();
				$Post = Post::find()->where(['status'=>'1','validflag'=>'1','department_id'=>[$department_id,'0']])->select("id,name")->asArray()->all();
					foreach($Post as $value){
						$posts[]= "<option value='".$value['id']."'>".$value['name']."</option>";
					}
				if($data){
					foreach($data as $value){
						$html[] = "<option value='".$value['id']."' data-id='".$value['pid']."'>".$value['name']."</option>";
					}
					return \yii\helpers\Json::encode(['code'=>'pid','data'=>$html,'post'=>$posts]);die;
				}else{
					return \yii\helpers\Json::encode(['code'=>'post','data'=>$posts]);die;
				}
			}else if($types){
				$data = Department::find()->where(['status'=>'1','validflag'=>'1','organization_id'=>$organization_id,'pid'=>'0'])->select("id,name")->asArray()->all();
				foreach($data as $value){
						$html[] = "<option value='".$value['id']."'>".$value['name']."</option>";
				}
				return \yii\helpers\Json::encode(['code'=>'0000','data'=>$html]);die;
			}
	}
	
	//岗位数据查询
	public function post($id){
		$post = Post::find()->where(['status'=>'1','validflag'=>'1','department_id'=>[$id,'0']])->select("id,name")->asArray()->all();
	     if($post){
			$html = \yii\helpers\Html::dropDownList('','',\yii\helpers\ArrayHelper::map($post,'id','name'));
			$html = trim(str_replace('<select name="">','',$html));
			$html = str_replace('</select>','',$html);
			return $html;die; 
		 }	
	}
}
