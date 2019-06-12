<?php

/* @var $this yii\web\View */

$this->title = 'Create Categories';
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $forms->CategoriesForm->title;
?>
<div class="categories-create">

    <?= $this->render('_form', [
        'forms' => $forms,
    ]) ?>

</div>
