<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Titulado;

/**
 * TituladoSearch represents the model behind the search form of `app\models\Titulado`.
 */
class TituladoSearch extends Titulado
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CI', 'Nombres', 'ApPaterno', 'ApMaterno', 'Foto', 'PaisActual', 'DepartamentoActual', 'CiudadActual', 'FechaActualizacion'], 'safe'],
            [['Celular', 'CodPaisCelular', 'EstadoLaboral', 'EstadoPostGrado'], 'integer'],
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
        $query = Titulado::find();

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
            'Celular' => $this->Celular,
            'CodPaisCelular' => $this->CodPaisCelular,
            'EstadoLaboral' => $this->EstadoLaboral,
            'EstadoPostGrado' => $this->EstadoPostGrado,
            'FechaActualizacion' => $this->FechaActualizacion,
        ]);

        $query->andFilterWhere(['like', 'CI', $this->CI])
            ->andFilterWhere(['like', 'Nombres', $this->Nombres])
            ->andFilterWhere(['like', 'ApPaterno', $this->ApPaterno])
            ->andFilterWhere(['like', 'ApMaterno', $this->ApMaterno])
            ->andFilterWhere(['like', 'Foto', $this->Foto])
            ->andFilterWhere(['like', 'PaisActual', $this->PaisActual])
            ->andFilterWhere(['like', 'DepartamentoActual', $this->DepartamentoActual])
            ->andFilterWhere(['like', 'CiudadActual', $this->CiudadActual]);

        return $dataProvider;
    }
}
