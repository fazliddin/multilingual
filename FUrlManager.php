<?php

/**
* Custom url manager which adds language code to url
*/
class FUrlManager extends CUrlManager
{
    /**
     * init 
     *
     * Adding language prefixes to url rules
     */
    public function init()
    {
        if(FLanguageHelper::isMultiLingual())
        {
            $langPrefix = FLanguageHelper::getIndices();
            $langPrefix = '<lang:(' . implode('|', $langPrefix) . ')>/';
            $finalRurles[$langPrefix] = '/';

            foreach ($this->rules as $rule => $path)
            {
                $finalRurles[$langPrefix . ltrim($rule, '/')] = $path;
            }

            $this->rules = array_merge($finalRurles, $this->rules);
        }
        
    return parent::init();
    }
    
	public function createUrl($route,$params=array(),$ampersand='&')
	{
        if(FLanguageHelper::isMultiLingual())
        {
            if(isset($params['lang']) &&
                    ($params['lang']==Yii::app()->defaultLanguage))
                        unset($params['lang']);
            elseif(!isset($params['lang']) && (FLanguageHelper::isDifferent()))
                $params['lang'] = Yii::app()->language;
        }

	return parent::createUrl($route,$params,$ampersand);
	}
}