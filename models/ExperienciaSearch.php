<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Experiencia;

/**
 * ExperienciaSearch represents the model behind the search form of `app\models\Experiencia`.
 */
class ExperienciaSearch extends Experiencia
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'EstadoActivo', 'Tipo', 'EstadoRelacionLaboralCarrera'], 'integer'],
            [['Sector', 'TipoSector', 'NombreInstitucion', 'Cargo', 'RangoSalarial', 'PeriodoTiempo', 'FechaIngreso', 'FechaActualizacion', 'CI'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Experiencia::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'EstadoActivo' => $this->EstadoActivo,
            'Tipo' => $this->Tipo,
            'EstadoRelacionLaboralCarrera' => $this->EstadoRelacionLaboralCarrera,
            'FechaIngreso' => $this->FechaIngreso,
            'FechaActualizacion' => $this->FechaActualizacion,
        ]);

        $query->andFilterWhere(['like', 'Sector', $this->Sector])
            ->andFilterWhere(['like', 'TipoSector', $this->TipoSector])
            ->andFilterWhere(['like', 'NombreInstitucion', $this->NombreInstitucion])
            ->andFilterWhere(['like', 'Cargo', $this->Cargo])
            ->andFilterWhere(['like', 'RangoSalarial', $this->RangoSalarial])
            ->andFilterWhere(['like', 'PeriodoTiempo', $this->PeriodoTiempo])
            ->andFilterWhere(['like', 'CI', $this->CI]);

        return $dataProvider;
    }
}
