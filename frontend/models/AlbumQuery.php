<?php

namespace app\models;
use yii\data\ActiveDataProvider;

/**
 * This is the ActiveQuery class for [[Album]].
 *
 * @see Album
 */
class AlbumQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Album[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Album|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
	
	public function search($params=[],$with=[],$limit="6",$orderType="",$isObj=true){
		$this->alias("t");
		$this->andWhere(["validflag"=>"1","status"=>"Y"]);
		
		$this->select("t.title, t.id, t.catalog_id, t.tags, t.attach_thumb,  t.copy_from, t.copy_url, t.create_time, t.update_time, t.view_count,t.attach_file");
		if($with)$this->joinWith($with);
		// $this->joinWith(["content"]);
		if(!$isObj)$this->asArray();
		if(isset($params["category"])&&in_array($params["category"],[1,2,3,4])){
			$this->andWhere(["catalog_id"=>$params["category"]]);
		}else{
			$this->andWhere("catalog_id != 0");
		}
		
		
		
		if($limit)$this->limit = $limit;
		if($orderType=="HOT"){
			$orderBy="`t`.`attention_count` desc,`t`.`favorite_count` desc,`t`.`view_count` desc,t.create_time desc";
		}else{
			$orderBy="`t`.`top_line` = 'Y' desc,`t`.`commend` = 'Y' desc,t.create_time desc";
		}
		
		
		
		
		$this->orderBy($orderBy);
		return $this->all();
	}
	
	public function getList($params=[],$with=[],$limit="6",$orderType="",$isObj=true){
		$this->search($params,$with,$limit,$orderType,$isObj);
		return $this->all();
	}
	public function getListProvider($params=[],$with=[],$orderType=""){
		
		$page = isset($params["page"])?$params["page"]:1;
		$limit = isset($params["limit"])?$params["limit"]:10;
		$this->search($params,$with,"",$orderType,true);
		$dataProvider = new ActiveDataProvider([
			'query' => $this,
			// 'sort' => [
				// 'defaultOrder' => [
					// 'create_at' => SORT_DESC,            
				// ]
			// ],
			'pagination' => [
                'pagesize' => $limit,
                'page' => $page-1,
			]
        ]);
		return $dataProvider;
	}
	
	public function detail($id,$isObj=false,&$brothers=false){
		$this->where(["id"=>$id,"validflag"=>"1","status"=>"Y"]);
		$this->joinWith(["albumcontent"]);
		if(!$isObj)$this->asArray();
		$data =  $this->one();
		if($brothers){
			$brothers=[];
			if($data){
				$prev = \app\models\Album::find()->andWhere(['<', 't.create_time', $data["create_time"]])->getList(["category"=>$data["catalog_id"]],[],"1","",false);
				$next = \app\models\Album::find()->andWhere(['>', 't.create_time', $data["create_time"]])->getList(["category"=>$data["catalog_id"]],[],"1","",false);
				$brothers["prev"] = $prev?$prev[0]["id"]:0;
				$brothers["next"] = $next?$next[0]["id"]:0;
				// var_dump($abc);
			}else{
				$brothers["prev"]="0";
				$brothers["next"]="0";
			}
		}
		return $data;
	}
}
