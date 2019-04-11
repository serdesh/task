<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 09.04.2019
 * Time: 21:18
 */

namespace app\components;


use yii\authclient\OAuth2;

class MyOAuth2 extends OAuth2
{

    public $authUrl = 'https://accounts.google.com/o/oauth2/auth';
    public $tokenUrl = 'https://oauth2.googleapis.com/token';
    public $apiBaseUrl = 'https://www.googleapis.com/auth/drive.file';

    /**
     * @return array
     */
    public function initUserAttributes()
    {
        return [
            'clientId' => '667521552878-91u8dlqf19tgnbfhulohjmg3jmngvosg.apps.googleusercontent.com',
            'clientSecret' => 'QmYeNv5dHMAgVADUdterUrpb',
            'tokenUrl' => 'https://oauth2.googleapis.com/token',
        ];
    }

    protected function defaultName()
    {
        return 'my_auth_client';
    }

    protected function defaultTitle()
    {
        return 'My Auth Client';
    }

    protected function defaultViewOptions()
    {
        return [
            'popupWidth' => 800,
            'popupHeight' => 500,
        ];
    }
}