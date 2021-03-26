<?php

namespace ImDong\JPush\Exceptions;

use Exception;

/**
 * Class JPushLaravelException
 *
 * @package ImDong\JPush\Exceptions
 *
 * @author  ImDong (www@qs5.org)
 * @created 2021-03-26 10:15
 */
class JPushLaravelException extends Exception
{
    /**
     * Payload 对象不可用
     */
    public const PAYLOAD_NOT_AVAILABLE = 401;

    /**
     * 错误消息描述
     *
     * @var array|string[] error_messages
     */
    public static array $error_messages = [
        self::PAYLOAD_NOT_AVAILABLE => 'Payload Class is not available'
    ];

}
