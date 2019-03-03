<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 06.11.2018
 * Time: 9:51
 */

namespace backend\tests\unit\tags;


use backend\models\Posts;
use backend\models\PostsTags;
use Codeception\Test\Unit;
use common\fixtures\CategoriesFixture;
use common\fixtures\PostsFixture;
use common\models\LoginForm;
use common\services\models\TagsService;

use common\fixtures\UserFixture;

/**
 * Class NodeTest
 *
 * @property \common\services\models\TagsService $service
 * @package backend\tests\unit\tags
 */

class NodeTest extends Unit
{
    public $service;

    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        $codeceptDir = codecept_data_dir();

        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => "{$codeceptDir}tester_login_data.php"
            ],
            'categories' => [
                'class' => CategoriesFixture::class,
                'dataFile' => "{$codeceptDir}category.php"
            ],
            'posts' => [
                'class' => PostsFixture::class,
                'dataFile' => "{$codeceptDir}post.php"
            ],
        ];
    }

    public function setUp()
    {
        $this->service = new TagsService();

        $login = new LoginForm();
        $login->load(['LoginForm' => ['username' => 'tester', 'password' => 'testertester']]);
        $login->login();

        return parent::setUp();
    }

    public function testCreateNew()
    {
        $model = $this->tester->grabFixture('posts', 0);

        $this->service->operation($model, '');
        expect($this->service->getAll())->count(3);
        expect($this->service->getByName('новых'))->notNull();
    }

    public function testReferenceUp()
    {
        $model = $this->tester->grabFixture('posts', 0);

        $this->service->operation($model, '');
        $tag = $this->service->getByName('новых');
        $reference = PostsTags::find()->where(['tag_id' => $tag->id])->one();

        expect($reference->post->title)->equals($model->title);
    }

    public function testReferenceDown()
    {
        $model = $this->tester->grabFixture('posts', 0);

        $this->service->operation($model, '');

        $post = Posts::findOne($model->id);
        $oldTags = $post->tags;
        $oldTagsArray = explode(',', $oldTags);
        $post->tags = $oldTagsArray[0];

        $this->service->operation($post, $oldTags);
        expect($this->service->getByName($oldTagsArray[1]))->null();
        expect($this->service->getByName($oldTagsArray[0]))->notNull();
    }

    public function testFrequencyUp(){

        $model = $this->tester->grabFixture('posts', 0);
        $this->service->operation($model, '');

        $model = new Posts();
        $model->title = 'new Post 2';
        $model->slug = 'new_post2';
        $model->tags = 'новых';
        $model->category_id = 1;
        $model->save();

        $tag = $this->service->getByName('новых');
        expect($tag->frequency)->equals(2);
    }

    public function testFrequencyDown(){

        $postOne = $this->tester->grabFixture('posts', 0);
        $this->service->operation($postOne, '');

        $model = new Posts();
        $model->title = 'new Post 2';
        $model->slug = 'new_post2';
        $model->tags = 'новых';
        $model->category_id = 1;
        $model->save();

        $postOne = Posts::findOne($postOne->id);
        $postOne->tags = 'add, tags';
        $this->service->operation($postOne, 'add, новых, tags');

        $tag = $this->service->getByName('новых');
        expect($tag->frequency)->equals(1);
    }
}