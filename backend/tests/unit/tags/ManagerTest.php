<?php


namespace backend\tests\unit\tags;


use backend\models\Tags;
use blog\managers\TagsManager;
use Codeception\Test\Unit;
use Yii;

/**
 * Class ManagerTest
 * @package backend\tests\unit\tags
 * @property \blog\managers\TagsManager $manager
 */
class ManagerTest extends Unit
{
    public $manager;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->manager = Yii::$container->get(TagsManager::class);
        parent::__construct($name, $data, $dataName);
    }

    public function testEmpty()
    {
        $tagsData = $this->manager->fromNewOldString('', '')->load();
        expect($tagsData->savedTag)->isEmpty();
        expect($tagsData->existTag)->isEmpty();
        expect($tagsData->toDelete)->isEmpty();
    }

    public function testCreate()
    {
        $tagsData = $this->manager->fromNewOldString('first, окно', '')->load();
        expect($tagsData->savedTag)->count(2);
        expect($tagsData->savedTag[0])->isInstanceOf(Tags::class);
        expect($tagsData->savedTag[1]->slug)->equals('okno');
        expect($tagsData->existTag)->isEmpty();
        expect($tagsData->toDelete)->isEmpty();
    }

    public function testGetDeleteIfTagNotExist()
    {
        $tagsData = $this->manager->fromNewOldString('first', 'first, не существует')->load();
        expect($tagsData->savedTag)->isEmpty();
        expect($tagsData->existTag)->isEmpty();
        expect($tagsData->toDelete)->isEmpty();
    }

    public function testGetToDeleteTest()
    {
        $tagsData = $this->manager->fromNewOldString('first, окно', '')->load();
        expect($tagsData->savedTag)->count(2);
        expect($tagsData->existTag)->isEmpty();
        expect($tagsData->toDelete)->isEmpty();

        $tagsData = $this->manager->fromNewOldString('first', 'first, окно')->load();
        expect($tagsData->savedTag)->isEmpty();
        expect($tagsData->existTag)->isEmpty();
        expect($tagsData->toDelete)->count(1);
        expect($tagsData->toDelete[0]->name)->equals('окно');
    }

    public function testGetExistTags()
    {
        $tagsData = $this->manager->fromNewOldString('first, окно', '')->load();
        expect($tagsData->savedTag)->count(2);
        expect($tagsData->existTag)->isEmpty();
        expect($tagsData->toDelete)->isEmpty();

        $tagsData = $this->manager->fromNewOldString('first', '')->load();
        expect($tagsData->savedTag)->isEmpty();
        expect($tagsData->existTag)->count(1);
        expect($tagsData->toDelete)->isEmpty();
    }

    public function testTagAutocomplete()
    {
        $this->manager->fromNewOldString('first, окно', '')->load();
        $tags = $this->manager->searchAutocomplete('first');
        expect($tags[0]['value'])->equals('first');
    }

    public function testTagAutocompleteResearch()
    {
        $this->manager->fromNewOldString('first, окно', '')->load();

        $tags = $this->manager->searchAutocomplete('ашк', true);
        expect($tags[0]['value'])->equals('first');

        $tags = $this->manager->searchAutocomplete('jry', true);
        expect($tags[0]['value'])->equals('окно');
    }

}
