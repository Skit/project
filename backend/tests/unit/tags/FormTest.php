<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 04.11.2018
 * Time: 10:25
 */

namespace backend\tests\unit\tags;

use backend\forms\TagsForm;
use Codeception\Test\Unit;
use common\services\models\TagsService;

class FormTest extends Unit
{
    public function testsSuccessTagCreate(){

        $form = new TagsForm([
            'name' => 'ложка',
            'slug' => 'spoon'
        ]);

        $service = new TagsService();
        $tag = $service->createFromForm($form);

        expect($tag)->isInstanceOf('backend\models\Tags');
        expect($form->name)->equals($tag->name);
    }

    public function testNotCorrectValidate(){

        $form = new TagsForm([
            'name' => 123,
            'slug' => 'spoonspoonspoonspoonspoonspoonspoonspoonspoonspoonspoon'
        ]);

        expect_not($form->validate());
        expect_that($form->getErrors('name'));
        expect_that($form->getErrors('slug'));

        expect($form->getFirstError('name'))
            ->equals('Name must be a string.');
        expect($form->getFirstError('slug'))
            ->equals('Slug should contain at most 50 characters.');
    }
}