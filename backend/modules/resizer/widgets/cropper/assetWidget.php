<?php

namespace backend\modules\resizer\widgets\cropper;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class assetWidget extends AssetBundle
{
    public $sourcePath = '@backend/modules/resizer/widgets/cropper/publish';
    public $css = ['css/cropper.css', 'css/main.css'];
    public $js = ['vendor/cropper/cropper.js'];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
