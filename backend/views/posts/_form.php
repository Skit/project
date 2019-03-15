<?php

use yii\helpers\Html;
use blog\helpers\FormHelper;
use yii\widgets\ActiveForm;
use backend\modules\translator\widgets\InputTranslate\InputTranslate;
use backend\modules\resizer\widgets\Cropper\Cropper;

/* @var $this yii\web\View */
/* @var $forms->PostsForm backend\models\Posts */
/* @var $categories \common\services\forms\CategoriesService */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="posts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($forms->CategoryForm, 'category_id')->dropDownList($categories->dropDownList()) ?>

    <?= $form->field($forms->PostsForm, 'title')->widget(InputTranslate::class, [
                'options'=>['maxlength'=>true]]) ?>

    <?= $form->field($forms->PostsForm, 'slug')->textInput(['maxlength' => true]) ?>
    <?= $form->field($forms->PostsForm, 'image_url')->widget(Cropper::class, [
            'cropperButtonId' => 'saveButton',
    ]) ?>
    <?= $form->field($forms->PostsForm, 'video_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($forms->PostsForm, 'content')->textarea(['rows' => 6]) ?>
    <?= $form->field($forms->PostsForm, 'preview')->textarea(['rows' => 6]) ?>

    <?= $form->field($forms->MetaTagsForm, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($forms->MetaTagsForm, 'description')->textInput(['maxlength' => true]) ?>
    <?= $form->field($forms->MetaTagsForm, 'keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($forms->TagsForm, 'tags')->textInput(['maxlength' => true]) ?>
    <?= $form->field($forms->PostsForm, 'is_active')->dropDownList(FormHelper::isActive()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id'=> 'saveButton']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
