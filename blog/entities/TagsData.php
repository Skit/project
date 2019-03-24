<?php


namespace blog\entities;


class TagsData
{
    public
        $existTag = [],
        $savedTag = [],
        $toDelete = [];

    public function __construct($existTag, $savedTag, $toDelete)
    {
        $this->existTag = $existTag;
        $this->savedTag = $savedTag;
        $this->toDelete = $toDelete;
    }
}