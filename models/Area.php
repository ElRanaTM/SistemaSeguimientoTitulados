<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "area".
 *
 * @property int $id
 * @property string $NombreArea
 * @property string $GradoRequerido
 * @property int $EstadoConocimientos
 * @property int $RelacionCarrera
 * @property int|null $idExperienciaLaboral
 * @property int $idCarrera
 *
 * @property Conocimientos[] $conocimientos
 * @property Carrera $idCarrera0
 * @property Experiencia $idExperienciaLaboral0
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NombreArea', 'GradoRequerido', 'EstadoConocimientos', 'RelacionCarrera', 'idCarrera'], 'required'],
            [['EstadoConocimientos', 'RelacionCarrera', 'idExperienciaLaboral', 'idCarrera'], 'integer'],
            [['NombreArea'], 'string', 'max' => 100],
            [['GradoRequerido'], 'string', 'max' => 40],
            [['idCarrera'], 'exist', 'skipOnError' => true, 'targetClass' => Carrera::class, 'targetAttribute' => ['idCarrera' => 'id']],
            [['idExperienciaLaboral'], 'exist', 'skipOnError' => true, 'targetClass' => Experiencia::class, 'targetAttribute' => ['idExperienciaLaboral' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'NombreArea' => 'Area de desempeño',
            'GradoRequerido' => 'Grado Requerido',
            'EstadoConocimientos' => 'Conocimientos Universitarios ¿son útiles?',
            'RelacionCarrera' => 'Carrera ¿está relacionada?',
            'idExperienciaLaboral' => 'Id Experiencia Laboral',
            'idCarrera' => 'Carrera',
        ];
    }

    /**
     * Gets query for [[Conocimientos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConocimientos()
    {
        return $this->hasMany(Conocimientos::class, ['idAreaDesempenio' => 'id']);
    }

    /**
     * Gets query for [[IdCarrera0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdCarrera0()
    {
        return $this->hasOne(Carrera::class, ['id' => 'idCarrera']);
    }

    /**
     * Gets query for [[IdExperienciaLaboral0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdExperienciaLaboral0()
    {
        return $this->hasOne(Experiencia::class, ['id' => 'idExperienciaLaboral']);
    }
}
