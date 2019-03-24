<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $forms \blog\managers\FormsManager */
/* @var $categories \common\services\forms\Service */

$this->title = 'Create Posts';
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-create">
    <?= $this->render('_form', [
        'forms' => $forms,
        'categories'=> $categories
    ]) ?>
</div>
