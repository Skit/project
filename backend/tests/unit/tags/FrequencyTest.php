<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 04.11.2018
 * Time: 14:53
 */

namespace backend\tests\unit\tags;

use Codeception\Test\Unit;
use common\services\TagsService;

/**
 * Class FrequencyTest
 * @property \common\services\TagsService $service
 * @package backend\tests\unit\tags
 */
class FrequencyTest extends Unit
{
    public $service;

    public function setUp()
    {
        $this->service = new TagsService();

        $tags = $this->service->arrayFromString('первый,дерево,космос');
        $this->service->batchCreate($tags);

        return parent::setUp();
    }

    public function testUp(){

        $tags = $this->service->arrayFromString('дерево,космос');
        $existTags = $this->service->getExistTags($tags);

        $tags = $this->service->frequencyUp($existTags);
        expect($tags[0]->frequency)->equals(2);
    }

    public function testDown(){

        $tags = $this->service->arrayFromString('дерево,космос');
        $existTags = $this->service->getExistTags($tags);
        $tagsUp = $this->service->frequencyUp($existTags);

        $downTags = $this->service->arrayFromString('дерево,первый');
        $existTags = $this->service->getExistTags($downTags);
        $downTags = $this->service->frequencyDown($existTags);

        expect($downTags[0]->frequency)->equals(1);
        expect($tagsUp)->count(2);
        expect($downTags)->count(1);
        expect($this->service->getByName('первый'))->null();
    }
}