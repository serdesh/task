<?php

namespace app\models;

use app\models\query\BossQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "boss".
 *
 * @property int $id
 * @property string $name
 * @property int $messenger_id Наименование мессенджера
 * @property string $messenger_number Номер мессенджера
 * @property string $notes
 *
 * @property Messenger $messenger
 * @property Project[] $projects
 */
class Boss extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'boss';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['messenger_id'], 'integer'],
            [['notes'], 'string'],
            [['name', 'messenger_number'], 'string', 'max' => 255],
            [['messenger_id'], 'exist', 'skipOnError' => true, 'targetClass' => Messenger::className(), 'targetAttribute' => ['messenger_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'messenger_id' => 'ID месенджера',
            'messenger_number' => 'Номер месенджера',
            'notes' => 'Заметки',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessenger()
    {
        return $this->hasOne(Messenger::className(), ['id' => 'messenger_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['boss_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return BossQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BossQuery(get_called_class());
    }

    public static function getList()
    {
       $bosses = self::find()->orderBy(['name' => SORT_ASC])->all();

       return ArrayHelper::map($bosses, 'id', 'name');
    }
}
