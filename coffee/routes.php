<?php

$GLOBALS['routes'] = [
    ['method' => 'get',     'path' => '/products',            'comp' => './products/index'  ],
    ['method' => 'get',     'path' => '/products/:id',        'comp' => './products/show'   ],
    ['method' => 'get',     'path' => '/products/:id/new',    'comp' => './products/create' ],
    ['method' => 'post',    'path' => '/products',            'comp' => './products/create' ],
    ['method' => 'get',     'path' => '/products/:id/edit',   'comp' => './products/update' ],
    ['method' => 'put',     'path' => '/products/:id',        'comp' => './products/update' ],
    ['method' => 'delete',  'path' => '/products/id/destroy', 'comp' => './products/destroy']
];

