<?php

/**
 * JPush Configs
 */
return [
    /**
     * AppKey
     *
     * AppKey 为极光平台应用的唯一标识。
     */
    'app_key'         => env('JPUSH_APP_KEY'),

    /**
     * Master Secret
     *
     * 用于服务器端 API 调用时与 AppKey 配合使用达到鉴权的目的，请保管好 Master Secret 防止外泄。
     */
    'master_secret'   => env('JPUSH_MASTER_SECRET'),

    /**
     * 接口请求日志文件
     *
     * 为 null 不记录日志
     */
    'log_file'        => env('JPUSH_LOG_FILE', storage_path(sprintf('logs/jpush-%s.log', date('Y-m-d')))),
];
