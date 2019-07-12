<?php

use blog\fileManager\entities\{Coords, ImagickSetUp, Dimension, Quality};
use blog\fileManager\entities\Path;

return [
    'adminEmail' => 'admin@example.com',
    'resizer' => [
        'paths' => [
            'save' => new Path('uploads/{:date[Y\m]}/{:salt[6]}_{width}_{height}.{format}'),
            'saveDraft' => new Path('uploads/draft/{:date[Y\m]}/resized_{:salt[12]}.{format}'),
            'original' => new Path('uploads/orig/{:date[Y\m]}/{:salt[6]}_{width}_{height}.{format}'),
            'originalDraft' => new Path('uploads/draft/{:date[Y\m]}/original_{:salt[12]}.{format}'),
            'uploadDir' => new Path('@frontend/web'),
        ],
        'patterns' => [
            'post' => new ImagickSetUp('jpeg', new Dimension(400, 280),  new Quality(), new Coords(), true),
            'imperavi' => new ImagickSetUp('jpeg', new Dimension(400, 280),  new Quality(), new Coords(), true),
        ],
    ]
];