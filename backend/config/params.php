<?php
use blog\fileManager\entities\JpegSetUp;
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
            'imperavi' => new JpegSetUp(400, 280, 85, 1),
        ],
    ]
];