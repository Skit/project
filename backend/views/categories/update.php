<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Categories */

$this->title = "Update Categories: {$forms->CategoriesForm->title}";
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $forms->CategoriesForm->title, 'url' => ['view', 'id' => $forms->CategoriesForm->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="categories-update">

    <?= $this->render('_form', [
        'forms' => $forms,
    ]) ?>

</div>
