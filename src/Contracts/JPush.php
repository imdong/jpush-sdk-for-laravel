<?php
/**
 * Class JPush
 *
 * @author  ImDong (www@qs5.org)
 * @created 2021-03-23 11:07
 */

namespace ImDong\JPush\Contracts;

use ImDong\JPush\Payloads\PushPayload;
use JPush\Client;

/**
 * Class JPush
 *
 * @package ImDong\JPush\Contracts
 *
 * @author  ImDong (www@qs5.org)
 * @created 2021-03-25 15:10
 */
class JPush extends Client
{
    /**
     * 是否为批量单推
     *
     * @var bool $batch
     */
    protected bool $batch = false;

    /**
     * 用于批量单推的列表
     *
     * @var array batch_list
     */
    protected array $batch_list = [];

    /**
     * 事件回调
     *
     * @var callable|null before
     */
    protected $before = null;

    /**
     * 开启批量单推模式
     *
     *
     * @return self
     * @author  ImDong (www@qs5.org)
     * @created 2021-03-23 10:23
     */
    public function batchStart(): self
    {
        $this->batch = true;
        return $this;
    }

    /**
     * 是否批量模式
     *
     * @return bool
     * @author  ImDong (www@qs5.org)
     * @created 2021-03-23 14:02
     */
    public function isBatch(): bool
    {
        return $this->batch;
    }

    /**
     * 增加一个任务到队列
     *
     * @param PushPayload|\JPush\PushPayload $push
     * @return void
     * @author  ImDong (www@qs5.org)
     * @created 2021-03-25 11:55
     */
    public function addToBatch(PushPayload $push)
    {
        $this->batch_list[] = $push->build();
    }

    /**
     * 获取推送列表
     *
     * @param bool $clean
     * @return array
     * @author  ImDong (www@qs5.org)
     * @created 2021-03-25 15:12
     */
    public function BatchGetList(bool $clean = true): array
    {
        $list = $this->batch_list;

        if ($clean) {
            $this->batch_list = [];
        }

        return $list;
    }

    /**
     * 将数据推送出去
     *
     * @author  ImDong (www@qs5.org)
     * @created 2021-03-25 14:12
     */
    public function batchSend(): bool
    {
        // 分出的数据
        $alias_list  = [];
        $reg_id_list = [];
        foreach ($this->batch_list as $push) {
            // 消除字段
            $data = array_diff_key($push, ['audience']);

            // 使用设备的单推
            if (!empty($push['audience']['registration_id'])) {
                foreach ($push['audience']['registration_id'] as $registration_id) {
                    $data['target'] = $registration_id;
                    $reg_id_list[]  = $data;
                }
            }

            // 使用别名的
            if (!empty($push['audience']['alias'])) {
                foreach ($push['audience']['alias'] as $alias) {
                    $data['target'] = $alias;
                    $alias_list[]   = $data;
                }
            }
        }

        if (!empty($alias_list)) {
            $this->push()->batchPushByAlias($alias_list);
        }

        if (!empty($reg_id_list)) {
            $this->push()->batchPushByRegid($reg_id_list);
        }

        return true;
    }

    /**
     * 获取推送对象
     *
     * @return PushPayload|\JPush\PushPayload
     * @throws \ImDong\JPush\Exceptions\JPushLaravelException
     * @author        ImDong (www@qs5.org)
     * @created       2021-03-25 11:43
     */
    public function push(): PushPayload
    {
        return new PushPayload($this);
    }

    /**
     * 设置预处理
     *
     * send 前自动调用
     *
     * @param callable $callable 接收两个参数 callable(string $type, PushPayload $data): ?array
     * @author  ImDong (www@qs5.org)
     * @created 2021-03-25 15:14
     */
    public function setBefore(callable $callable)
    {
        $this->before = $callable;
    }

    /**
     * 回调参数
     *
     * @param string      $type
     * @param PushPayload $push_payload
     * @return bool
     * @author  ImDong (www@qs5.org)
     * @created 2021-03-30 16:00
     */
    public function callBefore(string $type, PushPayload $push_payload): bool
    {
        if (is_null($this->before)) {
            return true;
        }

        return call_user_func($this->before, $type, $push_payload);
    }
}
