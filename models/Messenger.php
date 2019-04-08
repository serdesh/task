<?php

namespace app\models;

use app\models\query\MessengerQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "messenger".
 *
 * @property int $id
 * @property string $name
 *
 * @property Boss[] $bosses
 */
class Messenger extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messenger';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBosses()
    {
        return $this->hasMany(Boss::className(), ['messenger_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return MessengerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MessengerQuery(get_called_class());
    }

    public static function getList()
    {
        $messengers = self::find()->orderBy('name')->all();
        return ArrayHelper::map($messengers, 'id', 'name');
    }
}
