<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 07.11.2018
 * Time: 20:57
 */

namespace backend\modules\resizer\widgets\Cropper;

use Yii;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class Cropper extends InputWidget
{
    const WIDGET_ID = 'widget_cropper';

    public
        /**
         * Hide field label
         * @var bool
         */
        $label = false,
        /**
         * Class for bootstrap style
         * @var string
         */
        $class = 'form-control',
        /**
         * URL translate action
         * @var string
         */
        $url = '/resizer/resize/cropper',
        /**
         * Set css with and height
         * @var array
         */
        $canvasSize,
        /**
         * Field options
         * @var array
         */
        $options = [],
        /**
         * Cropper(options)
         * @var array
         */
        $cropperOptions = [],
        /**
         * Id image tag for state cropper
         * @var string
         */
        $imageId = 'cropper-image',
        /**
         * Canvas class
         * @var string
         */
        $canvasClass = 'img-container';


    protected
        /**
         * @var \yii\web\View
         */
        $view,
        /**
         * For event croppers
         * @var string
         */
        $cropperButtonsClass = 'cropper-buttons',
        /**
         * Yii stile fields id
         * @var string
         */
        $fileInputId,
        /**
         * @var string
         */
        $currentInputId,
        /**
         * For file input
         * @var string
         */
        $fileAttribute = 'cropperFile',
        /**
         * Check if option widget cropperButtonId is different with cropperSendId
         * @var bool
         */
        $isCropperSendButton,
        /**
         * Cropper(options)
         * @var array
         */
        $defaultCropperOptions = [
            'dragMode' => 'move',
            'viewMode' => 3,
            'autoCropArea' => 1,
            'autoCrop' => false,
            'cropBoxResizable' => false,
            'toggleDragModeOnDblclick' => false,
        ],
        /**
         * Accept attribute file input from config module
         * @var string
         */
        $acceptExtensions,
        /**
         * @var string model->formName()
         */
        $formName;

        /**
         * If equals with $cropperSendId displays cropper send button, else by ID
         * @var bool
         */
        public $cropperButtonId = 'cropper-send';
        /**
         * Default button to send cropper data
         * @var string
         */
        protected $cropperSendId = 'cropper-send';

    /**
     * @throws \ErrorException
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        if (!class_exists(\Imagick::class)) {
            throw new \ErrorException('Должен быть установлен Imagick: ' . self::WIDGET_ID);
        }

        if($this->label === false){
            $this->field->parts['{label}'] = '';
        }

        $this->isCropperSendButton = !!($this->cropperButtonId === $this->cropperSendId);

        $this->options = array_merge($this->options, []);
        $this->cropperOptions = array_merge($this->defaultCropperOptions, $this->cropperOptions);
        $this->view = $this->getView();
        $this->fileInputId = Html::getInputId($this->model, $this->fileAttribute);
        $this->currentInputId = Html::getInputId($this->model, $this->attribute);

        $this->formName = $this->model->formName();
        $moduleParams = Yii::$app->getModule('resizer')->clientSettings($this->formName);

        $this->canvasSize = $this->canvasSize ?? $moduleParams['size'];
        $this->acceptExtensions = $moduleParams['allowExtensions'];
        $this->acceptExtensions = '.' . str_replace([',', ' '], [',.', ''], $this->acceptExtensions);

        parent::init();
    }

    /**
     * @return string
     */
    public function run(): string
    {
        assetWidget::register($this->view);

        $script = $this->scriptCanvasSize();
        $script .= $this->scriptCropperInit();
        $script .= $this->scriptImageLoad();
        $script .= $this->scriptMethodButtons();
        $script .= $this->scriptSendCropperData();

        $this->view->registerJs($script);

        return self::getFields();
    }

    /**
    * @return string
    */
    protected function getFields(): string
    {
        $result = Html::activeHiddenInput($this->model, $this->attribute, $this->options)
                . Html::fileInput($this->fileAttribute, null,
                    ['id' => $this->fileInputId, 'accept' => $this->acceptExtensions]);

        $result .= Html::beginTag('div', ['class'=> $this->canvasClass])
                    . Html::img(null, ['id' => $this->imageId, 'style'=>'display:none'])
                . Html::endTag('div');

        $result .= Html::beginTag('div', ['class'=>$this->cropperButtonsClass])
                    . Html::button( Html::tag('span', '', ['class' => 'glyphicon glyphicon-resize-horizontal']),
                        [
                            'data-method' => 'scaleX',
                            'data-option' => '-1',
                            'class' => 'btn btn-primary',
                        ])
                    . '&nbsp'
                    . Html::button( Html::tag('span', '', ['class' => 'glyphicon glyphicon-resize-vertical']),
                        [
                            'data-method' => 'scaleY',
                            'data-option' => '-1',
                            'class' => 'btn btn-primary',
                        ]);

        if($this->isCropperSendButton){
            $result .= '&nbsp' . Html::button( Html::tag('span', '', ['class' => 'glyphicon glyphicon-floppy-disk']),
                    ['class' => 'btn btn-success']);
        }

        $result .= Html::endTag('div');

        return $result;
    }

    /**
     * @return string
     */
    protected function scriptCropperInit(): string
    {
        $script = "let \$image = $('#{$this->imageId}'), uploadedImageURL, 
        URL = window.URL || window.webkitURL,options = {";

        foreach ($this->cropperOptions as $option => $value){
            $script .= "{$option}: '{$value}',";
        }

        $script = rtrim($script, ',');
        $script .= '};$image.cropper(options);';

        return $script;
    }

    /**
     * @return string
     */
    public function scriptCanvasSize(): string
    {
        return "$('.{$this->canvasClass}').css({
            'width': '{$this->canvasSize['width']}',
            'height': '{$this->canvasSize['height']}'
        });";
    }

    /**
     * @return string
     */
    protected function scriptImageLoad(): string
    {
        return "
            var \$inputImage = $('#{$this->fileInputId}');
            if (URL) {
                \$inputImage.change(function () {
                    var files = this.files;
                    var file;
                
                    if (!\$image.data('cropper')) {
                        return;
                    }
                
                    if (files && files.length) {
                        file = files[0];
                    
                        if (/^image\/\w+$/.test(file.type)) {
                            uploadedImageName = file.name;
                            uploadedImageType = file.type;
                            
                            if (uploadedImageURL) {
                                URL.revokeObjectURL(uploadedImageURL);
                            }
                            
                            uploadedImageURL = URL.createObjectURL(file);
                            \$image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
                            \$inputImage.val('');
                        } else {
                            window.alert('Please choose an image file.');
                        }
                    }
                });
            } else {
                \$inputImage.prop('disabled', true).parent().addClass('disabled');
            }";
    }

    /**
     * @return string
     */
    protected function scriptMethodButtons(): string
    {
        return "
            $('.{$this->cropperButtonsClass}').on('click', '[data-method]', function () {
                let \$this = $(this),
                    data = \$this.data(),
                    cropper = \$image.data('cropper'),
                    cropped,
                    \$target,
                    result;

                if (cropper && data.method) {
                  data = $.extend({}, data);
            
                  if (typeof data.target !== 'undefined') {
                    \$target = $(data.target);
            
                    if (typeof data.option === 'undefined') {
                      try {
                        data.option = JSON.parse(\$target.val());
                      } catch (e) {
                        console.log(e.message);
                      }
                    }
                  }
                  cropped = cropper.cropped;
            
                  switch (data.method) {
                    case 'rotate':
                      if (cropped && options.viewMode > 0) {
                        \$image.cropper('clear');
                      }
                      break;
                  }
                  result = \$image.cropper(data.method, data.option, data.secondOption);
            
                  switch (data.method) {
                    case 'rotate':
                      if (cropped && options.viewMode > 0) {
                        \$image.cropper('crop');
                      }
                      break;
            
                    case 'scaleX':
                    case 'scaleY':
                      $(this).data('option', -data.option);
                      break;
                  }
            
                  if ($.isPlainObject(result) && \$target) {
                    try {
                      \$target.val(JSON.stringify(result));
                    } catch (e) {
                      console.log(e.message);
                    }
                  }
                }
        });";
    }

    /**
     * @return string
     */
    protected function scriptSendCropperData(): string
    {
        $script = "$('#{$this->cropperButtonId}').bind('click', function(e){";

        if(!$this->isCropperSendButton){
            $script .= 'e.preventDefault(); let $this = $(this), removeClass="send-data";';
            $script .= '$this.addClass(removeClass)';
        }

        $script .="
        if (\$image.cropper('getCroppedCanvas') === null){
            \$this.removeClass(removeClass)
            \$this.submit();
        }
            \$image.cropper('getCroppedCanvas').toBlob(function(blob){
                const formData = new FormData();
                formData.append('image', blob.slice(0, 104857600, {type: \"application/octet-stream\"}));
                formData.append('canvasData', JSON.stringify(\$image.cropper('getCanvasData')));
                formData.append('fileName', uploadedImageName);
                
                $.ajax('{$this->url}', {
                    method: \"POST\",
                    processData: false,
                    contentType: false,
                    data: formData
                }).done(function(data){
                    $('#{$this->currentInputId}').val(data);
                    \$this.submit();
                }).fail(function(error, message){
                    alert(error.responseText);
                }).always(function(){
                    \$this.removeClass(removeClass)
                });
                
	        });
        });";

        return $script;
    }

}