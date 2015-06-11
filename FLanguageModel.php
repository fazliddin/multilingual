<?php

/**
 * This is the model class for table "cms_languages".
 *
 * The followings are the available columns in table 'cms_languages':
 * @property string $code
 * @property string $native_name
 */
class FLanguageModel extends FLightModel
{
	public static function tableName()
	{
		return 'cms_languages';
	}
    
    public static function primaryKey()
    {
        return 'code';
    }

	public function getProcessedData()
	{
		if(($data = $this->getCache())!==false)
			return $data;
		
		$data = array();
		$langs = $this->
                select(['code', 'is_default', 'native_name'])->
                active()->
                all();
        
		foreach ($langs as $lang)
		{
			if($lang->is_default)
				$data['is_default'] = $lang->code;

			$data['data'][$lang->code] = $lang->native_name;
		}

		if(!empty($data))
			$this->setCache($data);

	return $data;
	}
}