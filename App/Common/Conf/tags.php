<?php
return array(
    'app_init' => array(
        'Com\Quyun\Api\Behavior\RouterBehavior', // 自定义路由
    ),
    'app_begin' => array(
        'Behavior\CheckLangBehavior',
    ),
);