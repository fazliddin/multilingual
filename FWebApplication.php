<?php
/**
 * Base class for our web application.
 *
 * Here will be the tweaks affecting the behavior of all entry points.
 *
 * @package oqila.core
 */
class FWebApplication extends CWebApplication
{
    /**
     *
     * @var string codes like en, ru or uz
     */
    private $_defaultLanguage = null;
    
    /**
     *
     * @var array of key-values: en=>English, ...
     */
    private $_languages = null;
    
    public function getDefaultLanguage()
    {
        if($this->_defaultLanguage === null)
            $this->initData();
        return $this->_defaultLanguage;
    }
    
    public function getLanguages()
    {
        if($this->_languages === null)
            $this->initData();
        return $this->_languages;
    }
    
    public function initData()
    {
        $data = FLanguageModel::find()->getProcessedData();
        if (empty($data))
            throw new CHttpException(404, Yii::t('core', 'no_language_found'));

        $this->_defaultLanguage = $data['is_default'];
        $this->_languages = $data['data'];
    }
}
