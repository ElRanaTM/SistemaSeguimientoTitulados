<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "opciones".
 *
 * @property string $Opcion
 * @property int $idPregunta
 * @property int $id
 *
 * @property Pregunta $idPregunta0
 */
class Opciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'opciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Opcion', 'idPregunta'], 'required'],
            [['Opcion'], 'string'],
            [['idPregunta'], 'integer'],
            [['idPregunta'], 'exist', 'skipOnError' => true, 'targetClass' => Pregunta::class, 'targetAttribute' => ['idPregunta' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Opcion' => 'Opcion',
            'idPregunta' => 'Id Pregunta',
            'id' => 'ID',
        ];
    }

    /**
     * Gets query for [[IdPregunta0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdPregunta0()
    {
        return $this->hasOne(Pregunta::class, ['id' => 'idPregunta']);
    }

    public static function getOptionsByIds($optionIds)
    {
        $optionIds = is_array($optionIds) ? $optionIds : explode(',', $optionIds);

        $options = static::find()->where(['id' => $optionIds])->all();

        return $options;
    }

    public function countRespuestas()
    {
        $respuestas = Respuesta::find()
            ->where(['like', 'TextoRespuesta', $this->id])
            ->all();

        return count($respuestas);
    }
}
