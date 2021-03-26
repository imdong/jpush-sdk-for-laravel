<?php
/**
 * Class PushPayload
 *
 * @author  ImDong (www@qs5.org)
 * @created 2021-03-23 11:23
 */

namespace ImDong\JPush\Payloads;

use ImDong\JPush\Facades\JPush;

/**
 * Class PushPayload
 *
 * @package ImDong\JPush\Payloads
 *
 * @author  ImDong (www@qs5.org)
 * @created 2021-03-23 13:48
 */
class PushPayload extends PayloadAbstract
{
    /**
     * 真正的操作类
     *
     * @var \JPush\PushPayload payload
     */
    protected $payload;

    /**
     * push
     *
     * @author  ImDong (www@qs5.org)
     * @created 2021-03-23 13:50
     */
    public function send(): ?array
    {
        // 如果是批量推送
        if (JPush::isBatch()) {
            JPush::addToBatch($this);
            return null;
        }

        return $this->payload->send();
    }
}
