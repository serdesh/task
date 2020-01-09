<?php

namespace app\models;

use app\models\query\SettingsQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property string $name
 * @property string $description
 */
class Settings extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['key', 'value', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'value' => 'Value',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    /**
     * {@inheritdoc}
     * @return SettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingsQuery(get_called_class());
    }

    public static function getValueByKey($key)
    {
        return self::find()->andWhere(['key' => $key])->one()->value ?? null;
    }
}
