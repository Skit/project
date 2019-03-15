<?php
/**
 * Created by PhpStorm.
 * User: Thomas White
 * Date: 02.02.2019
 * Time: 15:13
 */

namespace backend\tests\functional\post;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;

class tagsCreateCest
{
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ]
        ];
    }

}