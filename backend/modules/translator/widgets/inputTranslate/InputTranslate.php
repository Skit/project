<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 07.11.2018
 * Time: 20:57
 */

namespace backend\modules\translator\widgets\InputTranslate;

use yii\helpers\Html;
use yii\widgets\InputWidget;

class InputTranslate extends InputWidget
{
    const WIDGET_ID = 'inputTranslator';

    public
        /**
         * Class for bootstrap style
         * @var string
         */
        $class = 'form-control',
        /**
         * Button text
         * @var string
         */
        $text = 'Translate',
        /**
         * Loader element ID
         * @var string
         */
        $loaderId = 'input-translate-loader',
        /**
         * Click button ID
         * @var string
         */
        $buttonId = 'input-translate-button',
        /**
         * URL translate action
         * @var string
         */
        $url = '/translator/online-translate/get-slug',
        /**
         * Ajax loader image
         * @var string
         */
        $loaderImage = 'ajax-loader.gif',
        /**
         * Field options
         * @var array
         */
        $options = [],
        /**
         * Attribute name for translate output
         * @var string
         */
        $output = 'slug';

    /**
     * @var \yii\web\View
     */
    protected $view;

    /**
     * @throws \ErrorException
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        if (!function_exists('curl_init')) {
            throw new \ErrorException('Должен быть установлен CURL: ' . self::WIDGET_ID);
        }

        $this->options = array_merge($this->options, ['class'=>$this->class]);
        $this->view = $this->getView();
        $this->output = Html::getInputId($this->model, $this->output);

        parent::init();
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $this->view->registerJs("$(\"#{$this->options['id']}\").inputTranslate({
            'loaderId':'{$this->loaderId}',
            'inputId':'{$this->options['id']}',
            'url':'{$this->url}',
            'output':'{$this->output}'
        });");

        return self::getFields();
    }

    /**
     * @return string
     */
    protected function getFields(): string
    {

        $assetData = assetWidget::register($this->view);

        $result = Html::activeTextInput($this->model, $this->attribute, $this->options);
        // TODO как вариант сделать замену текста на лоадер при клике и возврат при ответе
        $result .= Html::img("{$assetData->baseUrl}/{$this->loaderImage}", [
            'id'=> $this->loaderId, 'alt' => $this->text, 'style' => 'display:none'
        ]);

        return $result;
    }
}