<?php
    return [
        'http'  => [
            'success'       => 200,
            'create'        => 201,
            'delete'        => 204,
            'param_error'   => 400,
            'no_access'     => 403,
            'not_found'     => 404,
            'server_error'  => 500
        ],
        'api'   => [
            'success'           => 1,   // 操作成功
            'un_error'          => -1,  // 未知错误
            'param_error'       => -2,  // 参数为空或错误
            'opera_error'       => -3,  // 操作失败
            'data_error'        => -4,  // 数据不存在或不匹配
        ],
    ];