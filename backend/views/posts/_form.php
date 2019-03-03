<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\translator\widgets\InputTranslate\InputTranslate;
use backend\modules\resizer\widgets\Cropper\Cropper;

/* @var $this yii\web\View */
/* @var $model backend\models\Posts */
/* @var $categories \common\services\forms\CategoriesService */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="posts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category_id')->dropDownList($categories->dropDownList()) ?>

    <?= $form->field($model, 'title')->widget(InputTranslate::class, [
                'options'=>['maxlength'=>true]]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'image_url')->widget(Cropper::class, [
            'cropperButtonId' => 'saveButton',
    ]) ?>
    <?= $form->field($model, 'video_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'preview')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'meta_desc')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'meta_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'is_active')->dropDownList($model->isActive()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id'=> 'saveButton']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
