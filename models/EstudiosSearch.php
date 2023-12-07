<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Estudios;

/**
 * EstudiosSearch represents the model behind the search form of `app\models\Estudios`.
 */
class EstudiosSearch extends Estudios
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'EstadoActivo'], 'integer'],
            [['NombreCurso', 'GradoAcademico', 'Universidad', 'FechaActualizacion', 'CI'], 'safe'],
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
        $query = Estudios::find();

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
            'FechaActualizacion' => $this->FechaActualizacion,
        ]);

        $query->andFilterWhere(['like', 'NombreCurso', $this->NombreCurso])
            ->andFilterWhere(['like', 'GradoAcademico', $this->GradoAcademico])
            ->andFilterWhere(['like', 'Universidad', $this->Universidad])
            ->andFilterWhere(['like', 'CI', $this->CI]);

        return $dataProvider;
    }
}
