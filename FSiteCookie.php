<?php

/**
 * User indepent cookie for storing various sistem wide states
 */
class FSiteCookie extends CApplicationComponent
{
    public $timeOut = 0;
    public $httpOnly = true;
    public $cookieName = 'ux';

    public function init()
    {
        parent::init();
        if (isset(Yii::app()->request->cookies[$this->cookieName])) {
            return;
        }

        $exp = time() + $this->timeOut;
        $this->create(array('exp' => $exp), $exp);
    }

    public function get($key)
    {
        if (isset(Yii::app()->request->cookies[$this->cookieName]->value[$key])) {
            return Yii::app()->request->cookies[$this->cookieName]->value[$key];
        }

        return false;
    }

    public function set($key, $val, $refreshExpiration = false)
    {
        $cookie = Yii::app()->request->cookies[$this->cookieName];
        $exp = ($refreshExpiration) ? time() + $this->timeOut : $cookie->value['exp'];
        $cookie->value[$key] = $val;
        $cookie->value['exp'] = $exp;

        $this->create($cookie->value, $exp);
    }

    public function create($values, $exp)
    {
        Yii::app()->request->cookies[$this->cookieName] = new CHttpCookie(
            $this->cookieName,
            $values,
            array(
                'expire' => $exp,
                'httpOnly' => $this->httpOnly,
                'domain' => Yii::app()->session->cookieParams['domain'],
            )
        );
    }

    public function delete()
    {
        unset(Yii::app()->request->cookies[$this->cookieName]);
    }
}
