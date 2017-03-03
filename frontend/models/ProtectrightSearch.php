<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Protectright;

/**
 * ProtectrightSearch represents the model behind the search form about `app\models\Protectright`.
 */
class ProtectrightSearch extends Protectright
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'cardNo', 'phone', 'jietiao', 'yinhang', 'danbao', 'caichan', 'other'], 'safe'],
            [['account'], 'number'],
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
        $query = Protectright::find();

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
            'account' => $this->account,
        ]);
		// $query->select("id,name,cardNo,phone");
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'cardNo', $this->cardNo])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'jietiao', $this->jietiao])
            ->andFilterWhere(['like', 'yinhang', $this->yinhang])
            ->andFilterWhere(['like', 'danbao', $this->danbao])
            ->andFilterWhere(['like', 'caichan', $this->caichan])
            ->andFilterWhere(['like', 'other', $this->other])
            ->andFilterWhere(['=', 'create_user', Yii::$app->user->getId()]);
		$query->orderBy("create_time desc");
        return $dataProvider;
    }
}
