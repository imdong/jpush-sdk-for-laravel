<?php

namespace ImDong\JPush\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * JPush Facade
 *
 * @package ImDong\JPush\Facades
 *
 * @method static \JPush\PushPayload push()
 * @method static \JPush\ReportPayload report()
 * @method static \JPush\DevicePayload device()
 * @method static \JPush\SchedulePayload schedule()
 *
 * @author  ImDong (www@qs5.org)
 * @created 2021-01-06 10:39
 */
class JPush extends Facade
{
    /**
     *
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'JPush';
    }
}
