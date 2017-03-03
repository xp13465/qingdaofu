<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Album;
use app\models\AlbumContent;
use app\models\Tag;
use app\models\TagData;

/**
 * AlbumSearch represents the model behind the search form about `app\models\Album`.
 */
class AlbumSearch extends Album
{
	public $create_time = '';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'catalog_id', 'special_id', 'view_count', 'favorite_count', 'attention_count', 'reply_count', 'sort_order',],'integer'],
            [['title', 'title_second', 'title_style', 'copy_from', 'copy_url', 'redirect_url', 'tags', 'commend', 'attach_file', 'attach_thumb', 'top_line', 'reply_allow', 'status','create_time'], 'safe'],
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
        $query = Album::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'create_time' => SORT_DESC,            
				]
			],
			'pagination' => [
                'pagesize' => '10',
			]
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
            'user_id' => $this->user_id,
            'catalog_id' => $this->catalog_id,
            'special_id' => $this->special_id,
            'view_count' => $this->view_count,
            'favorite_count' => $this->favorite_count,
            'attention_count' => $this->attention_count,
            'reply_count' => $this->reply_count,
            'sort_order' => $this->sort_order,
            'update_time' => $this->update_time,
        ]);
		$startTime = isset($this->create_time)&&!$this->create_time?$this->create_time:strtotime($this->create_time .'00:00');
		$endTime = isset($this->create_time)&&!$this->create_time?$this->create_time:strtotime($this->create_time .'23:59');
		$query->andFilterWhere(['between','create_time',$startTime,$endTime]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'title_second', $this->title_second])
            ->andFilterWhere(['like', 'title_style', $this->title_style])
            ->andFilterWhere(['like', 'copy_from', $this->copy_from])
            ->andFilterWhere(['like', 'copy_url', $this->copy_url])
            ->andFilterWhere(['like', 'redirect_url', $this->redirect_url])
            ->andFilterWhere(['like', 'commend', $this->commend])
            ->andFilterWhere(['like', 'attach_file', $this->attach_file])
            ->andFilterWhere(['like', 'attach_thumb', $this->attach_thumb])
            ->andFilterWhere(['like', 'top_line', $this->top_line])
            ->andFilterWhere(['like', 'reply_allow', $this->reply_allow])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
	
	public function  createContent($data = [],$AlbumId){
		if(isset($data['album_id'])&&$data['album_id']){
			$model = AlbumContent::findOne(['album_id'=>$data['album_id']]);
			$model->attributes = $data;
			$model->album_id = $AlbumId;
			if($model->update()){
				return OK;
			}else{
				return MODELDATASAVE;
			}
			
		}else{
			$model = new AlbumContent();
			$model->attributes = $data;
			$model->album_id = $AlbumId;
			if($model->save()){
				return OK;
			}else{
				return $model->errors;
			};
		}
			
	}
	
	public function createTag($data){
		$tag_name = explode(',',$data);
		$tag = Tag::find()->where(['or like','tag_name',array_filter($tag_name)])->asArray()->all();
		$model = new Tag();
		$stauts = '';
		if($tag){
			$tagData = [];
			foreach($tag as $value){
				$tagData[] = $value['tag_name'];
			}
			$tagNew = array_diff($tag_name,$tagData);
			$data = array_intersect($tagData,$tag_name);
			if($data){
				$tags = Tag::updateAllCounters(['data_count'=>1],['or like','tag_name',array_filter($data)]);
			}
			if($tagNew){
				foreach($tagNew as $attributes){
					$model->isNewRecord = true;  
					$model->setAttributes($tag_name); 
					$model->data_count = 1;
					$model->tag_name = $attributes;
					if($model->save()){
						$model->id = 0;
						$status = "OK";
					}else{
						$status = "MODELDATASAVE";
					};					
				}
				return $status;
			}
	 }else{
		 foreach($tag_name as $attributes){
			$model->isNewRecord = true;  
			$model->setAttributes($tag_name); 
			$model->data_count = 1;
			$model->tag_name = $attributes;
			if($model->save()){
				$model->id = 0;
				$status = "OK";
			}else{
				$status = "MODELDATASAVE";
			};
		  }
		 return $status;
	 }
	}
	
	public function createTagDdata($data,$AlbumId='',$status="Y"){
		$stautsTagData = '';
		$tag_name = explode(',',$data);
		$model = new TagData();
		$tag = Tag::find()->where(['or like','tag_name',array_filter($tag_name)])->asArray()->all();
		foreach($tag as $value){
			    $model->isNewRecord = true;
				$model->tag_id = $value['id'];
				$model->content_id = $AlbumId;
				$model->type = 1;
				$model->status = $status;
				if($model->save()){
					$model->tag_id = 0;
					$stautsTagData = "OK";
				}else{
					$stautsTagData = "MODELDATASAVE";
				};
		}
		return $stautsTagData;
	}
	
	/**
	 * 切割utf-8格式的字符串(一个汉字或者字符占一个字节)       
	 */
	public static function truncate_utf8_string($tstring, $length, $etc = '...') {
		$res = '';
		$string = html_entity_decode ( trim ( strip_tags ( $tstring ) ), ENT_QUOTES, 'UTF-8' );
		$strlen = strlen ( $string );
		for($i = 0; (($i < $strlen) && ($length > 0)); $i ++) {
			if ($number = strpos ( str_pad ( decbin ( ord ( substr ( $string, $i, 1 ) ) ), 8, '0', STR_PAD_LEFT ), '0' )) {
				if ($length < 1.0) {
					break;
				}
				$res .= substr ( $string, $i, $number );
				$length -= 1.0;
				$i += $number - 1;
			} else {
				$res .= substr ( $string, $i, 1 );
				$length -= 0.5;
			}
		}
		$result = htmlspecialchars ( $res, ENT_QUOTES, 'UTF-8' );
		if ($i < $strlen) {
			$result .= $etc;
		}
		return $result;
	}
	
}
