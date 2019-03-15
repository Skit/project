<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Posts;

/**
 * PostsSearch represents the model behind the search form of `backend\models\Posts`.
 */
class PostsSearch extends Posts
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'creator_id', 'category_id', 'count_view', 'is_highlight', 'is_active'], 'integer'],
            [['title', 'slug', 'image_url', 'video_url', 'content', 'preview', 'tags', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Posts::find();

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
            'creator_id' => $this->creator_id,
            'category_id' => $this->category_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'count_view' => $this->count_view,
            'is_highlight' => $this->is_highlight,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'image_url', $this->image_url])
            ->andFilterWhere(['like', 'video_url', $this->video_url])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'preview', $this->preview])
            ->andFilterWhere(['like', 'tags', $this->tags]);
    /*        ->andFilterWhere(['like', 'meta_desc', $this->meta_desc])
            ->andFilterWhere(['like', 'meta_key', $this->meta_key]);*/

        return $dataProvider;
    }
}
