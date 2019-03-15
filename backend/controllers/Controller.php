<?php


namespace backend\controllers;

use StdClass;
use yii\web\Controller as WebController;

class Controller extends WebController
{
    private
        /*
        * @var StdClass
        */
        $_inject;

    /**
     * Controller constructor.
     * @param array $args
     * @param $id
     * @param $module
     * @param array $config
     */
    public function __construct(array $args, $id, $module, $config = [])
    {
        $this->inject($args);
        parent::__construct($id, $module, $config);
    }

    /**
     * @param array $args
     */
    protected function inject(array $args)
    {
        $this->_inject = new StdClass();
        foreach(array_slice($args, 2, -1) as $class) {
            $injectName = getClassName($class);
            $this->_inject->{$injectName} = $class;
        }
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        if (isset($this->_inject->{$name})) {
            return $this->_inject->{$name};
        }

        return parent::__get($name);
    }
}