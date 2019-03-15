<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 20.11.2018
 * Time: 22:03
 */

namespace common\components;


class Module extends \yii\base\Module
{
    /**
     * Collecting public params module
     *
     * @return array
     */
    public function getModuleParams(): array
    {
        foreach (get_class_vars(get_class($this)) as $param => $value) {
            // exclude parent object params
            if (!empty($value) && ($param != 'defaultRoute') && ($param != 'controllerNamespace')) {
                // Set overridden params
                $params[$param] = $this->{$param};
            }
        }
        return $params;
    }
}