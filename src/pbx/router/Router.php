<?php


namespace app\src\pbx\router;


use app\src\pbx\Pbx;


/**
 * Class Router
 * @package app\src\pbx\router
 */
class Router
{
    /**
     *
     */
    const STEP = 'step';
    /**
     *
     */
    const END_STATUS = 'end';
    /**
     *
     */
    const VOICEMAIL_STATUS = 'voicemail';
    /**
     *
     */
    const NEXT_FLOW = 'next_flow';

    /**
     *
     */
    const GATHER_HANDLER = 'gather';

    /**
     * @param Pbx $pbx
     */
    public static function exec(Pbx $pbx): void
    {
        self::getRouterHandlerClass($pbx)->exec();
    }

    /**
     * @param Pbx $pbx
     * @return mixed
     */
    protected static function getRouterHandlerClass(Pbx $pbx)
    {
        $class = __NAMESPACE__ . '\strategy\\' . ucfirst($pbx->service) . 'Router';
        if (class_exists($class))
            return new $class($pbx);
    }
}