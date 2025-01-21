<?php 

return [ 
    '测试'=>[
        '列表'=>[
            '/api/v1/test/index', 
        ],
        '添加'=>[
            '/api/v1/test/save', 
        ],
    ],
    '文章'=>[
        '管理'=>[
            '/api/v1/admin/article/index', 
            '/api/v1/admin/article/show', 
            '/api/v1/admin/article/save', 
            '/api/v1/admin/article/delete',
        ],
        '查看' => [
            '/api/v1/admin/article/index', 
            '/api/v1/admin/article/show', 
        ], 
    ],
    '文章类型'=>[
        '管理'=>[
            '/api/v1/admin/article-type/index', 
            '/api/v1/admin/article-type/list', 
            '/api/v1/admin/article-type/show', 
            '/api/v1/admin/article-type/save', 
            '/api/v1/admin/article-type/delete',
        ],
        '查看' => [
            '/api/v1/admin/article-type/index', 
            '/api/v1/admin/article-type/show', 
            '/api/v1/admin/article-type/list', 
        ], 
    ],
];