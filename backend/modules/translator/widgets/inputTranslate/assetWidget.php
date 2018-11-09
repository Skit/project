<?php

namespace backend\modules\translator\widgets\inputTranslate;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class assetWidget extends AssetBundle
{
    public $sourcePath = '@backend/modules/translator/widgets/inputTranslate/publish';
    public $css = ['inputTranslate.css'];
    public $js = ['inputTranslate.js'];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
