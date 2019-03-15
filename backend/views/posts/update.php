<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $forms \blog\managers\FormsManager */
/* @var $categories \common\services\forms\CategoriesService */

$this->title = 'Update Posts: ' . $forms->PostsForm->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $forms->PostsForm->title, 'url' => ['view', 'id' => $forms->PostsForm->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="posts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'forms' => $forms,
        'categories' => $categories
    ]) ?>

</div>
