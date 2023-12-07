<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Encuesta;

/**
 * EncuestaSearch represents the model behind the search form of `app\models\Encuesta`.
 */
class EncuestaSearch extends Encuesta
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'Estado', 'user_id'], 'integer'],
            [['TituloEncuesta', 'Descripcion', 'FechaInicio', 'FechaFin', 'FechaCreacion', 'FechaEdicion'], 'safe'],
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
        $query = Encuesta::find();

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
            'FechaInicio' => $this->FechaInicio,
            'FechaFin' => $this->FechaFin,
            'Estado' => $this->Estado,
            'FechaCreacion' => $this->FechaCreacion,
            'FechaEdicion' => $this->FechaEdicion,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'TituloEncuesta', $this->TituloEncuesta])
            ->andFilterWhere(['like', 'Descripcion', $this->Descripcion]);

        if (!empty($this->FechaInicio)) {
            list($start_date, $end_date) = explode("' OR [FechaInicio]='", $this->FechaInicio);
            $query->andFilterWhere([
                'between',
                'encuesta.FechaInicio',
                date('Y-m-d', strtotime($start_date)),
                date('Y-m-d', strtotime($end_date)),
            ]);
        }

        return $dataProvider;
    }
}
