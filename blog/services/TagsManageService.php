<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 16:53
 */

namespace blog\services;

use blog\repositories\TagRepository;

class TagsManageService
{
    private $_tags;

    public function __construct(TagRepository $tags)
    {
    }
}