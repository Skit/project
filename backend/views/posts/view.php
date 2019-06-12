<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Posts */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if ($model->is_highlight) {
    nezhelskoy\highlight\HighlightAsset::register($this);
}
?>
<div class="posts-view">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'slug',
            'image_url:url',
            'video_url:url',
            'content:html',
            'preview:ntext',
            'tags',
            'creator_id',
            'category_id',
            'created_at:datetime',
            'published_at:datetime',
            'updated_at:datetime',
            'count_view',
            'is_highlight',
            'is_active',
        ],
    ]) ?>

</div>
