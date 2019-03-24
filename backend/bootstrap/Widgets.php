<?php


namespace backend\bootstrap;


use Yii;
use yii\base\BootstrapInterface;
use vova07\imperavi\Widget as Imperavi;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use kartik\datetime\DateTimePicker;

class Widgets implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;

        $container->set(Imperavi::class, [
            'settings' => [
                'lang' => 'ru',
                'toolbarFixedTopOffset'=>60,
                'minHeight' => 300,
                'imageManagerJson' => '/posts/show-images',
                'imageUpload' => '/posts/upload-image',
                'plugins' => [
                    'clips',
                    'fullscreen',
                    'imagemanager',
                    'counter'
                ]
            ]
        ]);

        $container->set(DateTimePicker::class, [
            'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'dd.mm.yyyy hh:ii'
            ]
        ]);

        $container->set(AutoComplete::class, [
            'clientOptions' => [
                'minLength'=>'2',
                'delay'=>500,
                'source'=> new JsExpression("function(request, response) {
                    $.getJSON(\"/posts/auto-complete-tags\", {
                    term: extractLast(request.term)
                }, response);
                       function split(val) {
                            return val.split(/,\s*/);
                        }
                        function extractLast(term) {
                            return split(term).pop();
                        }
                 }"),
                'search' => new JsExpression("function() {
                    var term = extractLast(this.value);
                        if (term.length < 1) {
                            return false;
                        }
                        function split(val) {
                            return val.split(/,\s*/);
                        }
                        function extractLast(term) {
                            return split(term).pop();
                        }
                 }"),
                'select'=> new JsExpression("function( event, ui ) {
                    var terms = this.value.split(/,\s*/);
                    terms.pop();
                    terms.push(ui.item.value);
                    terms.push(\"\");
                    this.value = terms.join(\", \");
                    return false;
                 }"),
                'focus'=> new JsExpression("function() {
                    return false;
                 }"),
            ],
            'options'=>[
                'class' => 'form-control'
            ]
        ]);
    }
}
