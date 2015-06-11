<?php

/**
 * Multilingual trait for FLightModel and FActiveRecord
 */
trait FMultiLingualModelTrait
{
    /**
     * Returns array of abstract multilungual field name: ['name', 'description']
     * @return array
     */
    public static function languageFields()
    {
        return [];
    }
    
    /**
     * Assigns current language version value to abstract language fields. If current
     * language is en:  
     * 
     * $this->name = $this->name_en
     * 
     * This method must be called in @afterFind() method. For this method worked
     * correctly, current and default language versions of abstract field must be
     * fetched (selected).
     * 
     * If current language version of field is not fetched or it is empty, then
     * default language value is assigned to abstract field.
     * 
     * Class must have writable properties with those field names.
     * 
     * @param arrar $fields Multilingual field list. If empty, @languageFields()
     * is used
     */
    public function normalizeLanguageFields($fields = [])
    {
        if(empty($fields))
            $fields = static::languageFields();
        
        if(empty($fields))
            return;
        
        foreach ($fields as $field)
        {
            if(empty($this->{FLanguageHelper::getDefField($field)}))
                continue;
            
            if(FLanguageHelper::isDifferent() &&
                !empty($this->{FLanguageHelper::getCurField($field)}))
            {
                $this->{$field} = $this->{FLanguageHelper::getCurField($field)};
            }
            else
                $this->{$field} = $this->{FLanguageHelper::getDefField($field)};
        }
    }
    
    /**
     * Generate optimal column list adding multilingual columns for selecting.
     * Selecting unused columns may degrade performance
     *
     * @param array $fields Name of multilingual fields. If you want to use
     * alias, then use key/value. Example:
     *  'name'; ['name','desc']; ['b.name' => 'brandname', 'desc']
     * 
     * @param array $columns Other fields to merge with result.
     * 
     * @param boolean $withAllLanguages If true field name will have all language
     *
     * @return array of language fields and other fields. Example:
     *      getMSelect(['name']);
     *      // [name_en, name_ru]
     *    
     *      getMSelect(['name','description'])
     *      // [name_en, name_ru, description_en, description_ru]
     *               
     *      getMSelect('name', ['id','ksu']);
     *      // [id, ksu, name_en, name_ru]
     *      
     *      getMSelect('name', ['id','ksu'], true);
     *      // [id, ksu, name_en, name_ru, name_uz]
     */
    public static function getMSelect($columns, $languageFields = [], $withAllLanguages = false)
    {   
        if(empty($languageFields))
            $languageFields = static::languageFields();
        
        $codes = $withAllLanguages ? FLanguageHelper::getIndices() : 
            self::optimalLanguageCodes();
        
        foreach ($languageFields as $key => $field)
        {
            foreach($codes as $code)
            {
                if(is_string($key))
                    $columns[] = FLanguageHelper::getField($key, $code).' '.
                        FLanguageHelper::getField($field, $code);
                else
                    $columns[] = FLanguageHelper::getField($field, $code);
            }
        }
        
    return $columns;
    }
    
    /**
     * returns default or/and current language codes
     * return array
     */
    public static function optimalLanguageCodes()
    {
        return FLanguageHelper::isDifferent() ? 
            [Yii::app()->language, Yii::app()->defaultLanguage] : 
                [Yii::app()->defaultLanguage];
    }
}