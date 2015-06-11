<?php

/**
 * Helper class for easily working with system languages
 */
class FLanguageHelper
{
    /**
     * Returns language codes: ['en','ru',...]
     * @return array
     */
    public static function getIndices()
    {
        return array_keys(Yii::app()->languages);
    }
    
    /**
     * Whether there are more than one language in system
     * @return boolean
     */
    public static function isMultiLingual()
    {
        return count(Yii::app()->languages) > 1;
    }

    /**
     * Checks whether given code exists among system languages
     * @param string $code of language
     * @return boolean
     */
    public static function exist($code)
    {
        return array_key_exists($code, Yii::app()->languages);
    }

    /**
     * Check whether current language is different than default language
     * @return boolean
     */
    public static function isDifferent()
    {
        return (Yii::app()->language != Yii::app()->defaultLanguage) ? true : false;
    }
    
    /**
     * Converts abstract field into default language field: name -> name_en
     * @param string $field abstract field name
     * @return string
     */
    public static function getDefField($field)
    {
        return $field . '_' . Yii::app()->defaultLanguage;
    }
    
    /**
     * Converts abstract field into current language field: name -> name_en
     * @param string $field abstract field name
     * @return string
     */
    public static function getCurField($field)
    {
        return $field . '_' . Yii::app()->language;
    }
    
    /**
     * Converts abstract field into given language field: name -> name_en
     * @param string $field abstract field name
     * @param string $language code
     * @return string
     */
    public static function getField($field, $language)
    {
        return $field . '_' . $language;
    }
    
    /**
     * Initializes multilingual feature of the system. Called once in per request
     * by controller.
     */
    public static function init()
    {
        if(!FLanguageHelper::isMultiLingual())
        {
            Yii::app()->language = Yii::app()->defaultLanguage;
            return;
        }
            
        $cookLang = Yii::app()->sitecookie->get('lang');
      
        // if user visits default home page, but he chose other language before
        // we redirect him to his prefered language
        if(
                empty($_GET) && $cookLang && 
                !isset($_SERVER['HTTP_REFERER']) && 
                ($cookLang != Yii::app()->defaultLanguage)
        )
        {
            Yii::app()->request->redirect(Yii::app()->createUrl('', ['lang' => $cookLang]));
        }
        
        // If user chose language by select option
        elseif(isset($_POST['lang']) && FLanguageHelper::exist($_POST['lang']))
        {
            // we do nothing if he rechose his current language
            if($cookLang && ($cookLang == $_POST['lang']))
                Yii::app()->language = $_POST['lang'];
            else
                // we redirect him to chosen language url
                Yii::app()->request->redirect($_POST[$_POST['lang']]);
        }
        
        // if language code is already provided by GET
        elseif(isset($_GET['lang']) && FLanguageHelper::exist($_GET['lang']))
        {
            Yii::app()->language = $_GET['lang'];
        }
        else
            // we neither POST, nor GET and we are not in homepage
            Yii::app()->language = Yii::app()->defaultLanguage;

        if(Yii::app()->language != $cookLang)
            Yii::app()->sitecookie->set('lang', Yii::app()->language);
    }
}