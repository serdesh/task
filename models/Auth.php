<?php

namespace app\models;

use app\models\query\AuthQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "auth".
 *
 * @property int $id
 * @property int $user_id
 * @property string $source
 * @property string $source_id
 * @property string $google_refresh_token
 *
 * @property User $user
 */
class Auth extends ActiveRecord
{
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
            [['user_id', 'source', 'source_id'], 'required'],
            [['user_id'], 'integer'],
            [['source', 'source_id'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'source_id' => 'Source ID',
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
     * Get Google Refresh Token for User
     */
    public static function getGoogleRefreshToken()
    {
        return self::find()->andWhere(['user_id' => Yii::$app->user->id])->one()->google_refresh_token ?? null;
    }

    public static function setRefreshToken($token)
    {
        $model = self::find()->where([['user_id' => Yii::$app->user->id]])->one();

        $model->google_refresh_token = $token;

        if (!$model->save()){
            Yii::error($model->errors, __METHOD__);
            Yii::$app->session->setFlash('error', 'Ошибка сохранения Refresh Token');
        }
    }
}
