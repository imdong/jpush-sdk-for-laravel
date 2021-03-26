<?php

namespace ImDong\JPush\Payloads;

use ImDong\JPush\Contracts\JPush;
use ImDong\JPush\Exceptions\JPushLaravelException;

/**
 * Class PayloadAbstract
 *
 * @author  ImDong (www@qs5.org)
 * @created 2021-03-23 11:32
 */
abstract class PayloadAbstract
{

    /**
     * 真实的操作对象
     *
     * @var mixed payload
     */
    protected $payload;
    /**
     * __call 调用的返回值（最后一次请求会覆盖）
     *
     * @var null result
     */
    protected $result = null;
    /**
     * 客户端本身
     *
     * @var JPush client
     */
    private JPush $client;

    /**
     * PayloadAbstract constructor.
     *
     * @param JPush $client
     * @throws JPushLaravelException
     */
    public function __construct(JPush $client)
    {
        $payload = $this->getPayloadClass();
        if (is_object($payload)) {
            $this->payload = $payload;
        } else if (is_string($payload)) {
            $this->payload = new $payload($client);
        } else {
            throw new JPushLaravelException('Payload Class is not available', 401);
        }

        $this->client = $client;
    }

    /**
     * 返回真实的 Payload 类名（或类）
     *
     * @return string
     * @author  ImDong (www@qs5.org)
     * @created 2021-03-23 11:42
     */
    public function getPayloadClass(): string
    {
        $names = explode('\\', static::class);
        return sprintf('\JPush\\%s', array_pop($names));
    }

    /**
     * 获取上次执行的返回值
     *
     * @return null
     * @author  ImDong (www@qs5.org)
     * @created 2021-03-26 10:11
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * __call
     *
     * @param $name
     * @param $arguments
     * @return false|mixed
     * @author  ImDong (www@qs5.org)
     * @created 2021-03-23 11:33
     */
    public function __call($name, $arguments)
    {
        $this->result = call_user_func_array([$this->payload, $name], $arguments);

        return is_object($this->result) ? $this : $this->result;
    }
}
