<?php

namespace app\models;

use app\models\query\AuthQuery;
use Google_Client;
use Google_Service_Drive;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * This is the model class for table "auth".
 *
 * @property int $id
 * @property int $user_id
 * @property string $source
 * @property string $google_refresh_token
 * @property array $token
 * @property Google_Client $client
 *
 * @property User $user
 */
class Auth extends ActiveRecord
{
    const GOOGLE_TOKEN_PATH = '@app/token.json';
    const GOOGLE_CREDENTIALS_PATH = '@app/credentials.json'; //Файл можно скачать со страницы проекта google https://console.developers.google.com

    public $token;
    public $client;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'google_refresh_token'], 'required'],
            [['user_id'], 'integer'],
            [['source'], 'string', 'max' => 255],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']
            ],
            [['google_refresh_token'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'source' => 'Source',
            'google_refresh_token' => 'Google Refresh Token',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return AuthQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthQuery(get_called_class());
    }

    /**
     * @return Google_Client
     * @throws \Google_Exception
     */
    public static function clientInit()
    {
        $redirect_uri  = Url::to('/drive', true);

        Yii::info('Redirect URI: ' . $redirect_uri, 'test');

        $client = new Google_Client();
        $client->setAuthConfig(Url::to(self::GOOGLE_CREDENTIALS_PATH));
        $client->setRedirectUri($redirect_uri);
        $client->addScope(Google_Service_Drive::DRIVE_FILE);
        //В scopes можно добавить Google_Service_Drive::DRIVE работать будет после того,
        // как в консоли Google в учетных данных будет добавлена область действия "../auth/drive"
        $client->setAccessType('offline');
        $client->setIncludeGrantedScopes(true);   // incremental auth
        if (self::checkAccessToken()){
            self::setToken($client);
            if ($client->isAccessTokenExpired()){
                self::refreshToken($client);
            }
        }


        return $client;
    }

    public static function checkAccessToken()
    {
        if (file_exists(Url::to(self::GOOGLE_TOKEN_PATH))){
            return true;
        }
        return false;
    }

    /**
     * @param object $client Google client
     * @return bool
     */
    private static function setToken($client)
    {
        $content = file_get_contents(Url::to(self::GOOGLE_TOKEN_PATH));
        Yii::info($content, 'test');
        Yii::info(Json::decode($content), 'test');
        $access_token = Json::decode($content);
        $client->setAccessToken($access_token);
        return true;
    }

    /**
     * Обновляет устаревший access токен
     *
     * Отмена регистрации в приложении (для получения refresh_token) https://myaccount.google.com/u/0/permissions.
     * @param Google_client $client
     * @return object
     */
    private static function refreshToken($client)
    {
        // При обновлении токена refresh токен отсутствует
        // Поэтому сохраняем refresh токен в отдельную переменную
        $refresh_token = $client->getRefreshToken();
        if (!$refresh_token) {
            //Если refresh токена нет в файле token.json берем из настроек
            $refresh_token = self::find()->andWhere(['user_id' => Yii::$app->user->id])->one()->google_refresh_token;
        }

        if ($client->isAccessTokenExpired()){
            // Обновляем токен
            $client->fetchAccessTokenWithRefreshToken($refresh_token);
        }

        // Создаём новую переменную, в которую помещаем новый обновлённый токен
        $new_token = $client->getAccessToken();

        if (isset($new_token['refresh_token'])){
            // Если в новом access токене нет refresh токена - добавляем в новый access токен старый refresh токен
            $new_token['refresh_token'] = $refresh_token;
        }

        // Устанавливаем новый токен
        $client->setAccessToken($new_token);

        //Сохраняем в файл
        file_put_contents(Url::to(self::GOOGLE_TOKEN_PATH), json_encode($client->getAccessToken()));

        return $client;
    }

    /**
     * @param string $code Код для получения токена
     * @return Google_Client|null
     * @throws \Google_Exception
     */
    public function getTokenWithCode($code)
    {

        $token_path = Url::to(self::GOOGLE_TOKEN_PATH);
        $client = $this->clientInit();
        $access_token = $client->fetchAccessTokenWithAuthCode($code);

        Yii::info('Redirect URI: ' . $client->getRedirectUri(), 'test');

        if (array_key_exists('error', $access_token)){
            Yii::error($access_token, __METHOD__);
            Yii::$app->session->setFlash('error', 'Ошибка получения токена. ' . $access_token['error']);

            return null;
        }

        Yii::info($access_token, 'test');
        Yii::info('Code: ' . $code, 'test');

        file_put_contents($token_path, json_encode($client->getAccessToken()));

        $model = Settings::find()->where(['key' => 'google_refresh_token'])->one();

        if (isset($access_token['refresh_token'])){

            $model->value = $access_token['refresh_token'];

            Yii::info('Refresh Token: ' . $model->value, 'test');

            if (!$model->save()){
                Yii::error($model->errors, __METHOD__);
                Yii::$app->session->setFlash('error', 'Ошибка записи Refresh токена');

                return null;
            }
        }

        return $client;
    }

    public function getAccessToken()
    {
        $token_path = Url::to(self::GOOGLE_TOKEN_PATH);
        return json_decode(file_get_contents($token_path), true)['access_token'];
    }
}
