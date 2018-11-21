<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 17.11.2018
 * Time: 21:11
 */

namespace backend\tests\unit\fileHelper;


use  \Codeception\Test\Unit;
use common\helpers\FileHelper;

class replacerTest extends Unit
{

    public function testWithoutParams()
    {
        $string = FileHelper::replacer('{:date}');
        expect($string)->equals(date('Y/m'));
    }

    public function testCorrectSetVariable(){
        $string = FileHelper::replacer('{salt}', ['{salt}' => 'qwerty']);
        expect($string)->equals('d8578e');
    }

    public function testCorrectSetFunction(){
        $string = FileHelper::replacer('{:date}', ['{:date}' => 'Y']);
        expect($string)->equals(date('Y'));
    }

    public function testMixesSet(){
        $string = FileHelper::replacer('path/{:date}/{salt}', ['{salt}' => 'qwerty', '{:date}'=>'m']);
        expect($string)->equals('path/' . date('m') . '/d8578e');
    }

    public function testReplaceNotAssociativeArray(){
        $string = FileHelper::replacer('path/{:date}/{date}/{salt}', ['m', '{date}'=>date('H'), 'qwerty']);
        expect($string)->equals('path/' . date('m/') . date('H/') .'d8578e');
    }

    public function testUndefinedVariableException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Undefined variable: {foo}');
        FileHelper::replacer('{:date}/{foo}/{bar}');
    }

    public function testUndefinedFunctionException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Undefined function: {:foo}');
        FileHelper::replacer('{:foo}');
    }
}