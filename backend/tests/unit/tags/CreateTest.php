<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 15:55
 */

namespace backend\tests\unit\tags;

use backend\models\Tags;
use common\services\models\TagsService;
use Codeception\Test\Unit;

/**
 * Class CreateTest
 *
 * @property \common\services\models\TagsService $service
 * @package backend\tests\unit\tags
 */
class CreateTest extends Unit
{
    public
        $tags,
        $service;

    public function setUp()
    {
        $this->tags = ['первый','конь','баян'];
        $this->service = new TagsService();
        return parent::setUp();
    }

    public function testSuccess(){

        $tag = $this->service->create('ложка', 'spoon');
        $tag = Tags::findOne($tag->id);

        expect($tag->frequency)->equals(1);
        expect($tag->is_active)->equals(1);

        $tag = $this->service->create('spoon', 'spoon');
        expect($tag)->isInstanceOf('backend\models\Tags');
        expect($tag->name)->equals('spoon');
    }

    public function testBatchSuccess(){

        expect($this->service->batchCreate($this->tags))->equals(count($this->tags));

        expect($tag = $this->service->getByName('конь'))->notNull();
        expect($tag->frequency)->equals(1);
        expect($tag->is_active)->equals(1);
    }

    public function testBatchCreateFromString(){

        $tags = $this->service->arrayFromString('первый,дерево');
        expect($this->service->batchCreate($tags))->equals(count($tags));
    }
}