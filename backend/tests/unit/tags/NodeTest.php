<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 06.11.2018
 * Time: 9:51
 */

namespace backend\tests\unit\tags;


use Codeception\Test\Unit;
use common\services\TagsService;
use yii\base\DynamicModel;

/**
 * Class NodeTest
 *
 * @property \common\services\TagsService $service
 * @package backend\tests\unit\tags
 */

class NodeTest extends Unit
{
    public $service;

    public function setUp()
    {
        $this->service = new TagsService();

        return parent::setUp();
    }

    public function testCreateNew() {

        $model = new DynamicModel([
            'oldAttributes' => (object)['tags'=> ''],
            'tags' =>'пара,новых,тегов'
        ]);
        expect($this->service->operation($model))->equals(3);
        expect($this->service->getByName('новых'))->notNull();
    }

    public function testFrequencyUp(){

        $this->service->batchCreate(['космос','гагарин']);

        $model = new DynamicModel([
            'oldAttributes' => (object)['tags'=> ''],
            'tags' =>'пара,космос,тегов'
        ]);
        $this->service->operation($model);
        $tag = $this->service->getByName('космос');
        expect($tag->frequency)->equals(2);
    }

    public function tetFrequencyDown(){

        $this->service->batchCreate(['космос','гагарин']);

        $model = new DynamicModel([
            'oldAttributes' => (object)['tags'=> 'космос,гагарин'],
            'tags' =>'гагарин'
        ]);
        $this->service->operation($model);
        expect($this->service->getByName('космос'))->null();
    }
}