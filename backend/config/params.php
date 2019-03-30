<?php
use blog\fileManager\entities\{ImagickSetUp, Dimension, Quality};
use blog\fileManager\entities\Paths;

return [
    'adminEmail' => 'admin@example.com',
    'resizer' => [
        'paths' => new Paths(
            'uploads/{:date[Y\m]}/{:salt[6]}_{width}_{height}.{format}',
            'uploads/draft/{:date[Y\m]}/{:salt[12]}.{format}',
            'uploads/orig/{:date[Y\m]}/{:salt[6]}_{width}_{height}.{format}',
            'uploads/draft/{:date[Y\m]}/{:salt[12]}.{format}',
            '@frontend/web'
        ),
        'patterns' => [
            'post' => new ImagickSetUp('jpeg', new Dimension(400, 280),  new Quality()),
            'imperavi' => new ImagickSetUp('jpeg', new Dimension(400, 280),  new Quality()),
        ],
    ]
];