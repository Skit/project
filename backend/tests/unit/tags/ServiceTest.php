<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 04.11.2018
 * Time: 14:20
 */

namespace backend\tests\unit\tags;

use backend\models\Tags;
use blog\services\TagService;
use Codeception\Test\Unit;

/**
 * Class ServiceTest
 *
 * @property \blog\services\TagService $service
 * @package backend\tests\unit\tags
 */
class ServiceTest extends Unit
{
    public $service;

    public function _before()
    {
        $this->service = new TagService();
    }

    public function testStringToArray()
    {
        $array = $this->service->arrayFromString('первый пошел, второй');
        expect($array)->equals(['первый пошел', 'второй']);
    }

    public function testEmptyStringToArray()
    {
        $array = $this->service->arrayFromString('');
        expect($array)->equals([]);
    }

    public function testStringNormalize()
    {
        $array = $this->service->arrayFromString('  первый пошел  ,   второй');
        expect($array)->equals(['первый пошел', 'второй']);
    }

    public function testGetAddArrayWithoutOld()
    {
        $toAdd = $this->service->getToAdd(['one', 'three', 'two', 'four'], []);
        expect($toAdd)->equals(['one', 'three', 'two', 'four']);
    }

    public function testGetAddArrayOldLess()
    {
        $toAdd = $this->service->getToAdd(['one', 'three', 'two', 'four'], ['one', 'two']);
        expect($toAdd)->equals([1 => 'three', 3 => 'four']);
    }

    public function testGetAddArrayOldMore()
    {
        $toAdd = $this->service->getToAdd(['one'], ['five', 'two']);
        expect($toAdd)->equals(['one']);
    }

    public function testGetAddArrayFromEmpty()
    {
        $toAdd = $this->service->getToAdd([], ['one', 'two']);
        expect($toAdd)->equals([]);
    }

    public function testGetToDeleteArray()
    {
        $toAdd = $this->service->getToDelete(['one', 'two'], ['one', 'three', 'two', 'four']);
        expect($toAdd)->equals([1 => 'three', 3 => 'four']);
    }

    public function testGetNotExistTags()
    {
        $notExist = $this->service->getNewTags(['one', 'two', 'существует'], [Tags::create('существует', 'exist')]);
        expect($notExist)->equals(['one', 'two']);
    }

    public function testAddSlugsToTags()
    {
        $tagsWithSlugs = $this->service->addedSlugsArray(['корова']);
        expect($tagsWithSlugs)->equals([['name' => 'корова', 'slug' => 'korova']]);
    }
}