<?php


namespace blog\helpers;


class FormHelper
{
    public static function isActive(){

    return [
        ConstantsHelper::ACTIVE => 'Yes',
        ConstantsHelper::INACTIVE =>'No',
    ];
}

}