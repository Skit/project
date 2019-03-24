<?php

namespace backend\modules\resizer;

use RuntimeException;
/**
 * translator module definition class
 */
class Module extends \common\components\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\resizer\controllers';

    public
        /**
         * @var array
         */
        $size = ['width'=>10, 'height'=>10],
        /**
         * @var array
         */
        $clients = [],
        /**
         * @var string
         */
        $allowExtensions = 'jpg, gif, png',
        /**
         * @var string
         */
        $outputFormat = 'jpeg',
        /**
         * @var string
         */
        $uploadPath = '@frontend/web',
        /**
         * @var string
         */
        $relativeResizePath = 'uploads/{client}/{:date}',
        /**
         * @var string
         */
        $relativeOriginalPath = 'uploads/orig/{:date}',
        /**
         * @var string
         */
        $relativeUnsafePath = 'uploads/unsafe/{:time}',
        /**
         * @var int
         */
        $quality = 85,
        /**
         * @var float
         */
        $blur = 0.9,
        /**
         * @var bool
         */
        $bestfit = true,
        /**
         * @var bool
         */
        $saveOriginal = true;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @param string $client
     * @return array
     */
    public function clientSettings(string $client): array
    {
        if (empty($this->clients)) {
            throw new RuntimeException('Module attribute "clients" must be set');
        }

        if (in_array($client, $this->clients)) {
            return $this->moduleParams;
        }
        elseif (key_exists($client, $this->clients)) {
            return array_merge($this->moduleParams, $this->clients[$client]);
        }
        else {
            throw new RuntimeException('Client is not found!');
        }
    }
}
