<?php

namespace app\controllers;

use app\models\Auth;
use Google_Client;
use Google_Service_Drive;
use GuzzleHttp\Client;
use Yii;
use yii\bootstrap\Html;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\components\AuthHandler;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @param $client
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionGoogleDrive()
    {
//        $content = Yii::$app->googleDrive->listContents('', true);
        $content = 'Отключено';

        $client = '';

        return $this->render('gdrive', [
            'content' => $content,
            'client' => $client,
        ]);
    }

    public function actionCreateFile()
    {

        $content = Yii::$app->googleDrive->put(
            '1DVejnXOYtgvP-baMRmJhaEZuVatujvwT/testfile2.txt', //Запись в определенную папку
            'testtestetetfbfj222222222'
        );
        return $this->render('gdrive', [
            'content' => $content,
        ]);
    }

    public function actionCreateDir()
    {
        $content = Yii::$app->googleDrive->createDir('testDir');
        return $this->render('gdrive', [
            'content' => $content,
        ]);
    }

    public function actionDeleteFile()
    {
        $content = Yii::$app->googleDrive->delete('1BeCDyHa4que-oMiY-8QYr4i5SI2-Gg_ubciX8CbvFws');
        return $this->render('gdrive', [
            'content' => $content,
        ]);
    }

    public function actionDeleteDir()
    {
        $content = Yii::$app->googleDrive->deleteDir('1ZYjVRMH2mJEB0aImli6KTGi3DGDhraRr');
        return $this->render('gdrive', [
            'content' => $content,
        ]);
    }

    public function actionGetSize()
    {
        $content = Yii::$app->googleDrive->getSize('1DVejnXOYtgvP-baMRmJhaEZuVatujvwT');
        return $this->render('gdrive', [
            'content' => $content,
        ]);
    }

    public function actionGetTimestamp()
    {
        $content = Yii::$app->googleDrive->getTimestamp('1DVejnXOYtgvP-baMRmJhaEZuVatujvwT');
        $content = Yii::$app->formatter->asDatetime($content);
        return $this->render('gdrive', [
            'content' => $content,
        ]);
    }

    /**
     * @return string
     */
    public function actionUploadFile()
    {
//        $source  = Url::to(['@webroot/images/testfile.txt']);
//        $source  = 'C:\OSPanel\domains\task\web\images\testfile.txt';
//        $api_key = 'AIzaSyCgpeu9Y-LYQ4013Ll-fH1fJNodh29pMnY';
//        $curl_file = curl_file_create($source);
//        $post = [
//            'extra_info' => 'Uploaded txt file',
//            'file_contents'=> $curl_file
//        ];
//
//        $file_size = filesize($source);
//        Yii::info($file_size, 'test');
//        $source = 'images/testfile.txt';
//        $url = 'https://www.googleapis.com/drive/v3/files/FILE_ID?alt=media';
//        $url = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=resumable';

//        $content = file_get_contents('images/testfile.txt');

//        $authheaders = ["Authorization: Bearer " . $api_key];
//
//        $file = fopen($source, 'w');
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL,$url);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//        curl_setopt($ch, CURLOPT_FILE, $file);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $authheaders);
//        curl_setopt($ch, CURLINFO_CONTENT_LENGTH_UPLOAD,$file_size);
//        curl_setopt($ch, CURLOPT_USERPWD, "serdesh77@gmail.com:epvewrmorn123987654");
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $file);

//        $content = curl_exec($ch);
//        $error = curl_error($ch);
//        $info = curl_getinfo($ch);
//        curl_close($ch);
//        fclose($file);

        return $this->render('gdrive', [
//            'content' => $content,
//            'error' => $error,
//            'info' => $info,
        ]);
    }

    public function actionGetToken()
    {
        $request = Yii::$app->request;
        $auth_code = $request->get('code');

        $redirect_uri = 'http://localhost/site/get-refresh-token';

        $client = new Google_client();
        $client->setAuthConfig(Url::to('@app/credentials.json'));
        $client->setRedirectUri($redirect_uri);
        $client->addScope(Google_Service_Drive::DRIVE_FILE);
        $client->setAccessType('offline');        // offline access
        $client->setIncludeGrantedScopes(true);   // incremental auth
        $token_path = Url::to('@app/token.json');

        if (file_exists($token_path)) {
            //Если есть файл токен доступа
            $access_token = Json::decode(file_get_contents($token_path));
            $client->setAccessToken($access_token);
        }
        elseif ($auth_code){
            //Если есть код доступа
            $access_token = $client->fetchAccessTokenWithAuthCode($auth_code);
            $client->setAccessToken($access_token);
            Auth::setRefreshToken($client->getRefreshToken());
        }
        else {
            //Если нет файла токена доступа
            $refresh_token= Auth::getGoogleRefreshToken(); //Берем Refresh ТОкен из базы

            if ($refresh_token){
                //Если Refresh Token найден получаем по нему новый Access Token
                $access_token = $client->fetchAccessTokenWithRefreshToken($refresh_token);
                $client->setAccessToken($access_token);
            } else {
                //Запрашиваем код доступа
                $data = [
                    'response_type' => 'code',
                    'redirect_uri' => $client->getRedirectUri(),
                    'client_id' => $client->getClientId(),
                    'scope' => Google_Service_Drive::DRIVE_FILE,
                    'access_type' => 'offline',
                    'approval_prompt' => 'auto',
                ];

                $get_data = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($data);

                Yii::info($get_data, 'test');

                $guzzle_client = new Client();
                $response = $guzzle_client->request('GET', $get_data)->getBody();

                Yii::info($response, 'test');
                //Открываем страницу с аутентификацией
                return $this->render('gdrive',[
                    'auth_form' => $response,
                ]);
            }
        }

        if ($client->isAccessTokenExpired()){
            //Если токен просрочен - получаем новый
            $access_token = $client->fetchAccessTokenWithRefreshToken();
            $client->setAccessToken($access_token);
        }

        // Check to see if there was an error.
        if (array_key_exists('error', $access_token)) {
            Yii::error(join(', ', $access_token), __METHOD__);
            Yii::$app->session->setFlash('error', 'Ошибка получения токена');
            return $this->render('gdrive');
        }

            if ($access_token) {
            //Сохраняем токен доступа
                if (!file_exists($token_path)) {
                    file_put_contents($token_path, json_encode($client->getAccessToken()));
                }
            return $this->render('gdrive', [
                'access_token' => $access_token,
            ]);
        }

//        if ($auth_code) {
//            Yii::info('Request Code: ' . $auth_code, 'test');
            //Для получения Refresh Token делаем пост запрос на 'https://accounts.google.com/o/oauth2/token'
//
//            $uri = 'https://accounts.google.com/o/oauth2/token';
//            $params = [
//                'client_id' => $client_id,
//                'client_secret' => $client_secret,
//                'redirect_uri' => $redirect_uri,
//                'grant_type' => 'authorization_code',
//                'code' => $request_code
//            ];
//
//            $post_client = new Client();
//            $post_response = $post_client->request('POST', $uri, $params)->getBody();
//
//
//            $access_token = $client->fetchAccessTokenWithAuthCode($request_code);
//
//            return $this->render('gdrive', [
//                'access_token' => $access_token,
//            ]);
//        }

        return $this->render('gdrive');
    }


    /**
     * Backup DataBase and directories (settings in config/web.php [backup])
     * @throws \yii\base\Exception
     */
    public function actionBackup()
    {
        if (!is_dir('../backups')) {
            mkdir('../backups', 755);
        }

        /** @var \demi\backup\Component $backup */
        $backup = \Yii::$app->backup;

        Yii::info(is_dir($backup->getBackupFolder()), 'test');


        $file = $backup->create();

        $request = Yii::$app->request;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => 'Бэкап проекта',
                'content' => 'Backup file created: ' . $file . PHP_EOL,
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        }
        Yii::$app->session->setFlash('success', 'Резервное копирование выполнено успешно! Создан файл: ' . $file . PHP_EOL);
        return $this->goHome();
    }
}
