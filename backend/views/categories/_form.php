<?php

use backend\modules\translator\widgets\InputTranslate\InputTranslate;
use blog\helpers\FormHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-body">
                    <?= $form->field($forms->CategoriesForm, 'title')->widget(InputTranslate::class,[
                        'options'=> ['maxlength' => true]
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-body">
                    <?= $form->field($forms->CategoriesForm, 'slug')->textInput(['maxlength' => true]) ?>
                    <span>&nbsp;</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <?= $form->field($forms->CategoriesForm, 'description')->textarea(['rows' => 6]) ?>
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
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <?= $form->field($forms->CategoriesForm, 'is_active')->dropDownList(FormHelper::isActive()) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
