<?php

/**
 * This file is part of the Marvarid project.
 *
 * (c) Fazliddin Jo'raev
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

trait FSimpleModelCacheTrait
{
    public function setCache($data, $timeOut = 0, $timeUnit = FCacheHelper::AS_HOURS, $id = null)
    {
        FCacheHelper::set($this->tableName().$id, $data, $timeOut, $timeUnit);
    }

    public function deleteCache($id = null)
    {
        FCacheHelper::delete($this->tableName().$id);
    }

    public function getCache($id = null)
    {
        return FCacheHelper::get($this->tableName().$id);
    }
    
    /**
     * Deletes all caches. This method must be called after save() or delete().
     * Subclasses must implement this method.
     */
    public function deleteAllCache()
    {
        self::deleteCache();
    }
}