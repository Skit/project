<?php

use yii\helpers\Html;
use blog\helpers\FormHelper;
use yii\widgets\ActiveForm;
use backend\modules\translator\widgets\InputTranslate\InputTranslate;
use backend\modules\resizer\widgets\Cropper\Cropper;
use yii\jui\AutoComplete;
use kartik\datetime\DateTimePicker;
use vova07\imperavi\Widget as Imperavi;

/* @var $this yii\web\View */
/* @var $forms->PostsForm backend\models\Posts */
/* @var $categories \common\services\forms\CategoriesService */
/* @var $form yii\widgets\ActiveForm */

?>
    <div class="posts-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-body">
                    <?= $form->field($forms->CategoryForm, 'category_id')->dropDownList($categories->dropDownList()) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-body">
                    <?= $form->field($forms->PostsForm, 'is_active')->dropDownList(FormHelper::isActive()) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-body">
                    <?= $form->field($forms->PostsForm, 'title')->widget(InputTranslate::class, [
                        'options'=>['maxlength'=>true]]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-body">
                    <?= $form->field($forms->PostsForm, 'slug')->textInput(['maxlength' => true]) ?>
                    <div>&nbsp;</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <?php $post = Yii::$app->params['resizer']['patterns']['post']->dimension; ?>
                    <?= $form->field($forms->PostsForm, 'image_url')->widget(Cropper::class, [
                        'cropperButtonId' => 'saveButton',
                        'canvasSize' => ['width' => $post->width, 'height' => $post->height]
                    ])?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <?= $form->field($forms->PostsForm, 'video_url')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <?= $form->field($forms->PostsForm, 'content')->textarea(['rows' => 6])->widget(Imperavi::class) ?>
                    <?= $form->field($forms->PostsForm, 'preview')->textarea(['rows' => 6]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <?= $form->field($forms->MetaTagsForm, 'title')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($forms->MetaTagsForm, 'keywords')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($forms->MetaTagsForm, 'description')->textarea(['rows' => 6]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-body">
                    <?= $form->field($forms->TagsForm, 'tags')->textInput(['maxlength' => true])->widget(AutoComplete::class) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-body">
                    <?= $form->field($forms->PostsForm, 'published_at')->textInput(['maxlength' => true])->widget(DateTimePicker::class) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id'=> 'saveButton']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

