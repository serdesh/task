<?php


/**
 * Created by PhpStorm.
 * User: User
 * Date: 09.04.2019
 * Time: 13:19
 */

namespace app\modules\drive\models;

use Exception;
use Google_Client;
use Google_Exception;
use Google_Service_Drive;
use Yii;
use yii\helpers\Url;

class Drive
{
    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     * @throws Exception
     * @throws Google_Exception
     */
    public static function getClient()
    {

        $client = new Google_Client();
        $client->setApplicationName('Google Drive API PHP Test');
        $client->setScopes(Google_Service_Drive::DRIVE_FILE);
        $client->setAuthConfig(Url::to('@app/credentials.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        Yii::info($client->getLibraryVersion(), 'test');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = Url::to('@app/token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        } else {
            Yii::info('No accessToken file', 'test');
//
//            $client->setAccessToken([
//                'access_token' => 'ya29.GlzmBl7E09k3JAPvjnoqIhlY_lwPVY3I4trtVITuOiruvyIqUNgh-ihY84js_Q03p4Q53C53QQKPG_7hsG_wqrDmoQFpdddXu0nY6gGaGVYWFJA8PSfZkKt_CLaNLA',
//                'refresh_token' => '1/X2W97R6HWFgCpR9_xmVPTCFWQZhLgwyiTRMCbBqWy-w',
//                'expires_in' => 3000,
//                'created' => time(),
//            ]);
            Yii::info('Access Token' , 'test');
            Yii::info($client->getAccessToken(), 'test');
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            Yii::info('AccessToken is nothing or expired', 'test');

            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $client->setRedirectUri(Url::to('/drive', true));
                $client->setAccessType('offline');
                $client->setState(null);
                $client->setPrompt(null);
                $client->setApprovalPrompt('null');
                $accessToken = $client->fetchAccessTokenWithRefreshToken('1/X2W97R6HWFgCpR9_xmVPTCFWQZhLgwyiTRMCbBqWy-w');
                $authUrl = $client->createAuthUrl();

//                printf("Open the following link in your browser:\n%s\n", $authUrl);
//                echo 'Enter verification code: ';

                Yii::info('Auth Url: ' .  $authUrl, 'test');

//                $auth_response = file_get_contents($authUrl);

//                Yii::info('Auth response: ' . $auth_response, 'test');

//                $authCode = trim(fgets(STDIN));
//                $authCode = '';
                // Exchange authorization code for an access token.
//                Yii::info('AuthCode: ' . $authCode, 'test');
//                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        } else {
            Yii::info('AccessToken not expired', 'test');

        }
        return $client;
    }

}