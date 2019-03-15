<?php


namespace blog\entities;

class MetaTags
{
    public
        $title,
        $keywords,
        $description;

    public function __construct(string $title, string $keywords, string $description)
    {
        $this->title = $title;
        $this->keywords = $keywords;
        $this->description = $description;
    }
}