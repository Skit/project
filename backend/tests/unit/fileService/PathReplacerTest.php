<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 17.11.2018
 * Time: 21:11
 */

namespace backend\tests\unit\fileService;

use blog\fileManager\services\FileService;
use Yii;
use  Codeception\Test\Unit;

/**
 * Class pathReplacerTest
 * @package backend\tests\unit\fileService
 * @property \blog\fileManager\services\FileService $service
 */
class PathReplacerTest extends Unit
{
    protected $service;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->service = Yii::$container->get(FileService::class);
        parent::__construct($name, $data, $dataName);
    }

    public function testFunctionWithoutParams()
    {
        $string = $this->service->pathReplacer('path/{:date}');
        expect($string)->equals('path');
    }

    public function testWithoutParams()
    {
        $string = $this->service->pathReplacer('path/{client}');
        expect($string)->equals('path');
    }

    public function testWrongParams()
    {
        $string = $this->service->pathReplacer('path/{:date}', [
            '{client}' => 'root',
            '{format}' => 'zip',
        ]);
        expect($string)->equals('path');
    }

    public function testCorrectSetVariable()
    {
        $string = $this->service->pathReplacer('path/{salt}', ['{salt}' => 'qwerty']);
        expect($string)->equals('path/qwerty');
    }

    public function testIncorrectSetFunction()
    {
        $string = $this->service->pathReplacer('{:date}', ['{:date}' => 'Y']);
        expect($string)->equals('');
    }

    public function testCorrectSetFunction()
    {
        $string = $this->service->pathReplacer('{:date[Y]}');
        expect($string)->equals(date('Y'));
    }

    public function testCorrectSetPathWithDateFunction()
    {
        $string = $this->service->pathReplacer('path/{:date[Y\m]}');
        expect($string)->equals('path/' . date('Y/m'));
    }

    public function testFilePathExample()
    {
        $string = $this->service->pathReplacer('{:date[Y\m]}/{:salt[6]}_{width}_{height}.{format}', [
            '{width}' => 640,
            '{height}' => 480,
            '{format}' => 'jpeg',
        ]);
        expect($string)->equals(date('Y/m/') . substr(md5(time()), 0 , 6) .'_640_480.jpeg');
    }

    public function testTimeFunction(){
        $string = $this->service->pathReplacer('{:time}');
        expect(strlen($string))->equals(strlen(time()));
    }

    public function testMixesSet(){
        $string = $this->service->pathReplacer('path/{:date[m]}/{:salt[12]}/{owner}', ['{owner}' => 'abc']);
        expect(strlen($string))->equals(24);
    }

    public function testReplaceNotAssociativeArrayWithTrash(){
        $string = $this->service->pathReplacer('path/{:date}/{date}/{salt}', ['m', '{date}' => date('Y'), 'qwerty']);
        expect($string)->equals('path/' . date('Y'));
    }


}