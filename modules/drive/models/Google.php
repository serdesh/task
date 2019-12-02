<?php

namespace app\modules\drive\models;


use app\models\Auth;
use Google_Exception;
use Google_Service_Drive;

class Google
{

    public $client;


    /**
     * Google constructor.
     * @throws Google_Exception
     */
    public function __construct()
    {
        $this->client = Auth::clientInit();
    }

    /**
     *
     * @param object $client Google_Client
     * @return Google_Service_Drive
     * @throws Google_Exception
     */
    public function getGoogleDriveService($client = null)
    {
        if (!$client){
            $client = Auth::clientInit();
        }

        $driveService = new Google_Service_Drive($client);

        return $driveService;
    }

    /**
     * @return \Google_Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Очистка гугло-корзины
     * @throws Google_Exception
     */
    public function emptyTrash()
   {
       $driveService = $this->getGoogleDriveService();

       $driveService->files->emptyTrash();
       return true;
   }
}