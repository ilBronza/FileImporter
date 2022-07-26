<?php

namespace IlBronza\FileImporter\Http\Controllers\Traits;

use Illuminate\Database\Eloquent\Model;

trait FileImporterGettingModelTrait
{
	private function shouldRetrieveModel()
	{
		return $this->shouldRetrieveModel;
	}

	private function mustRetrieveModel()
	{
		return $this->mustRetrieveModel;
	}

	public function getGettingModelFields()
	{
		return $this->retrievingModelFields;
	}

	public function _getModel($data) : ? Model
	{
		$parameters = [];

		$fields = $this->getGettingModelFields();

		foreach($fields as $column => $field)
		{
			if(! $value = $this->getColumnValue($data, $column, $field))
				throw new \Exception("Campo chiave {$column} nullo ({$value}) per {$field}, impossibile cercare il model: " . json_encode($data));

			$parameters[$field] = $value;
		}

		return $this->fullModelClass::where($parameters)->first();
	}

	private function makeModel()
	{
		return $this->fullModelClass::make();
	}


	private function getModel($data) : ? Model
	{
		if($this->shouldRetrieveModel())
		{
			if($model = $this->_getModel($data))
				return $model;

			return $this->makeModel();
		}

		if($this->mustRetrieveModel())
		{
			if($model = $this->_getModel($data))
				return $model;

			throw new \Exception ('model not found');
			// return null;
		}
			mori('zxcvzx');

		return $this->makeModel();
	}
}