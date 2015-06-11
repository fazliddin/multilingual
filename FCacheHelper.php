<?php

/**
 * This file is part of the Marvarid project.
 *
 * (c) Fazliddin Jo'raev
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

class FCacheHelper
{
    const AS_SECONDS = 1;
    const AS_MINUTES = 60;
    const AS_HOURS   = 3600;
    const AS_DAYS    = 86400;
    const AS_MONTH   = 2592000;

    public static function set($id, $data, $timeOut=0, $timeUnit = Utils::AS_HOURS)
    {
        if (($cache = Yii::app()->cache) !== null)
            $cache->set($id, $data, $timeOut*$timeUnit);
    }

    public static function delete($id)
    {
        if (($cache = Yii::app()->cache) !== null)
            $cache->delete($id);
    }

    public static function get($id)
    {
        if(($cache = Yii::app()->cache) !== null)
            return $cache->get($id);
        else
            return false;
    }
    
    public static function flush()
    {
        if(($cache = Yii::app()->cache) !== null)
            $cache->flush();
    }
}
