<?php

return [
    'group' => '设置',
    'resource' => [
        'id' => 'ID',
        'single' => '用户',
        'email_verified_at' => '邮箱已验证',
        'created_at' => '创建于',
        'updated_at' => '更新于',
        'verified' => '已验证',
        'unverified' => '未验证',
        'name' => '姓名',
        'email' => '邮箱',
        'password' => '密码',
        'roles' => '角色',
        'teams' => '团队',
        'label' => '用户',
        'title' => [
            'show' => '显示用户',
            'delete' => '删除用户',
            'impersonate' => '模拟用户',
            'create' => '创建用户',
            'edit' => '编辑用户',
            'list' => '用户列表',
            'home' => '用户',
        ],
        'notificaitons' => [
            'last' => [
                'title' => '错误',
                'body' => '您不能删除最后一个用户',
            ],
            'self' => [
                'title' => '错误',
                'body' => '您不能删除自己',
            ],
        ],
    ],
    'bulk' => [
        'teams' => '更新团队',
        'roles' => '更新角色',
    ],
    'team' => [
        'title' => '团队',
        'single' => '团队',
        'columns' => [
            'avatar' => '头像',
            'name' => '姓名',
            'owner' => '拥有者',
            'personal_team' => '个人团队',
        ],
    ],
];