<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "experiencia".
 *
 * @property int $id
 * @property int $EstadoActivo
 * @property int $Tipo
 * @property string $Sector
 * @property string $TipoSector
 * @property int $EstadoRelacionLaboralCarrera
 * @property string $NombreInstitucion
 * @property string $Cargo
 * @property string|null $RangoSalarial
 * @property string|null $PeriodoTiempo
 * @property string $FechaIngreso
 * @property string $FechaActualizacion
 * @property string|null $CI
 *
 * @property AreaDesempenio[] $areaDesempenios
 * @property Titulado $cI
 */
class Experiencia extends \yii\db\ActiveRecord
{

    const EVENT_AFTER_SAVE = 'afterSave';
    const EVENT_AFTER_UPDATE = 'afterUpdate';
    const EVENT_AFTER_DELETE = 'afterDelete';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'experiencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['EstadoActivo', 'Tipo', 'Sector', 'TipoSector', 'EstadoRelacionLaboralCarrera', 'NombreInstitucion', 'Cargo', 'FechaIngreso', 'FechaActualizacion'], 'required'],
            [['EstadoActivo', 'Tipo', 'EstadoRelacionLaboralCarrera'], 'integer'],
            [['FechaIngreso', 'FechaActualizacion'], 'safe'],
            [['Sector', 'Cargo'], 'string', 'max' => 100],
            [['TipoSector'], 'string', 'max' => 40],
            [['NombreInstitucion'], 'string', 'max' => 200],
            [['RangoSalarial', 'PeriodoTiempo'], 'string', 'max' => 1],
            [['CI'], 'string', 'max' => 30],
            //[['id'], 'unique'],
            [['CI'], 'exist', 'skipOnError' => true, 'targetClass' => Titulado::class, 'targetAttribute' => ['CI' => 'CI']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'EstadoActivo' => 'Estado',
            'Tipo' => 'Tipo de Negocio',
            'Sector' => 'Sector Productivo',
            'TipoSector' => 'Sector de la Economía',
            'EstadoRelacionLaboralCarrera' => '¿Relacionado a la Carrera?',
            'NombreInstitucion' => 'Nombre de la Institucion',
            'Cargo' => 'Cargo en la Institución',
            'RangoSalarial' => 'Rango Salarial',
            'PeriodoTiempo' => 'Periodo de Tiempo',
            'FechaIngreso' => 'Fecha de Ingreso',
            'FechaActualizacion' => 'Fecha de Actualizacion',
            'CI' => 'Cédula de identidad',
        ];
    }

    /**
     * Gets query for [[AreaDesempenios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany(Area::class, ['idExperienciaLaboral' => 'id']);
    }

    /**
     * Gets query for [[CI]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTitulado()
    {
        return $this->hasOne(Titulado::class, ['CI' => 'CI']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->titulado->updateEstadoFromExperiencias();
    }

    public function afterUpdate()
    {
        parent::afterUpdate();

        $this->titulado->updateEstadoFromExperiencias();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $this->titulado->updateEstadoFromExperiencias();
    }


}
