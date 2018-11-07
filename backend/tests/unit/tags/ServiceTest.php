<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 04.11.2018
 * Time: 14:20
 */

namespace backend\tests\unit\tags;

use Codeception\Test\Unit;
use common\fixtures\TagsFixture;
use common\services\TagsService;
use yii\base\DynamicModel;
use yii\helpers\Inflector;

/**
 * Class ServiceTest
 *
 * @property \common\services\TagsService $service
 * @package backend\tests\unit\tags
 */
class ServiceTest extends Unit
{
    public $service;

    public function _before()
    {
        $this->tester->haveFixtures([
            'tags' => [
                'class' => TagsFixture::class,
                'dataFile' => codecept_data_dir() . 'new_tags.php'
            ]
        ]);
        $this->service = new TagsService();
    }

    public function testStringToArraySuccess(){

        expect($this->service->arrayFromString('молоко'))->equals(['молоко']);

    }

    public function testSetSlugToTagsArray(){

        $tags = $this->service->arrayFromString('молоко');
        expect($this->service->setTagsSlug($tags))
            ->equals([
                [
                    'name'=>'молоко',
                    'slug'=>'moloko'
                ]
            ]);

        $tags = $this->service->arrayFromString('молоко, яблоко,  банан ');
        expect($this->service->setTagsSlug($tags))
            ->equals([
                [
                    'name'=>'молоко',
                    'slug'=>Inflector::slug('молоко')
                ],
                [
                    'name'=>'яблоко',
                    'slug'=>Inflector::slug('яблоко')
                ],       [
                    'name'=>'банан',
                    'slug'=>Inflector::slug('банан')
                ],
            ]);
    }

    public function testGetExistTags() {

        $exist = $this->tester->grabFixture('tags')->data;

        $service = new TagsService();
        $existTags = $service->getExistTags($exist);

        expect($existTags)->count(count($exist));
        expect($existTags[1])->isInstanceOf('backend\models\Tags');
        expect($existTags[1]->name)->equals('tag2');
    }

    public function testDeleteFromString(){

        $service = new TagsService();
        $model = new DynamicModel([
            'oldAttributes' => (object)['tags'=> 'земляника,банан,кунжут']
        ]);
        $old = $service->arrayFromString($model->oldAttributes->tags);

        $new = $service->arrayFromString('земляника,банан');
        $delete = $service->getDelete($new,$old);
        expect($delete)->equals([2=>'кунжут']);

        $new = $service->arrayFromString('земляника,кунжут');
        $delete = $service->getDelete($new,$old);
        expect($delete)->equals([1=>'банан']);

        $new = $service->arrayFromString('кунжут');
        $delete = $service->getDelete($new,$old);
        expect($delete)->equals(['земляника','банан']);

        $new = $service->arrayFromString('');
        $delete = $service->getDelete($new,$old);
        expect($delete)->equals(['земляника','банан','кунжут']);
    }

    public function testNewTagsFromString(){

        $service = new TagsService();
        $model = new DynamicModel([
            'oldAttributes' => (object)['tags'=> 'земляника,банан,кунжут']
        ]);
        $old = $service->arrayFromString($model->oldAttributes->tags);

        $new = $service->arrayFromString('земляника,банан,кунжут,мыло');
        $delete = $service->getAdded($new,$old);
        expect($delete)->equals([3=>'мыло']);

        $new = $service->arrayFromString('земляника,банан,мыло');
        $delete = $service->getAdded($new,$old);
        expect($delete)->equals([2=>'мыло']);
    }
}