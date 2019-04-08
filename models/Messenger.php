<?php

namespace app\models;

use yii\db\ActiveRecord;

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
     * @return \app\models\query\MessengerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\MessengerQuery(get_called_class());
    }
}
