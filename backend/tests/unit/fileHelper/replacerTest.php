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
        $string = FileHelper::replacePattern('{:date}');
        expect($string)->equals(date('Y/m'));
    }

    public function testCorrectSetVariable(){
        $string = FileHelper::replacePattern('{:date}');
        expect($string)->equals(date('Y/m'));
    }

    public function testUndefinedVariableException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Undefined variable: {foo}, {bar}');
        FileHelper::replacePattern('{:date}/{foo}/{bar}');
    }

    public function testUndefinedFunctionException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Undefined function: foo');
        FileHelper::replacePattern('{:foo}');
    }
}