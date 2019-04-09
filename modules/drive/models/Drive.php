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

        define('STDIN', fopen("php://stdin", "r"));

        $client = new Google_Client();
        $client->setApplicationName('Google Drive API PHP Quickstart');
        $client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
        $client->setAuthConfig('../credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
//        $tokenPath = '../token.json';
//        if (file_exists($tokenPath)) {
//            $accessToken = json_decode(file_get_contents($tokenPath), true);
//            $client->setAccessToken($accessToken);
//        }

        // If there is no previous token or it's expired.
//        if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
//            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
//            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
//            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
//            if (array_key_exists('error', $accessToken)) {
//                throw new Exception(join(', ', $accessToken));
//            }
        }
        // Save the token to a file.
//        if (!file_exists(dirname($tokenPath))) {
//            mkdir(dirname($tokenPath), 0700, true);
//        }
//        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
//        }
        return $client;
    }
}