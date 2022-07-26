<?php

namespace IlBronza\FileImporter\Http\Controllers\Traits;

use IlBronza\FileImporter\Models\Fileimportation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait FileImporterParseTrait
{
	public function getParsingSliceSize()
	{
		return $this->parsingSliceSize;
	}

	public function setSlice()
	{
		$parsingSliceLimit = $this->getParsingSliceSize();

		$this->rows = $this->fileimportation
			->fileimportationrows()
			->toParse()
			->take($parsingSliceLimit)
			->get();

		$this->lastSlice = (count($this->rows) < $parsingSliceLimit);
	}

	private function getBindingModelFields()
	{
		return $this->bindingModelFields;
	}

	private function getColumnValue($data, $column, $field)
	{
		$methodName = 'get' . Str::studly($field) . 'ImportingValue';

		if(method_exists($this, $methodName))
			return $this->{$methodName}($data, $column);

		return $data->{$column};
	}

	private function bind($data, $model)
	{
		$bindingFields = $this->getBindingModelFields();

		foreach($bindingFields as $column => $field)
		{
			$value = $this->getColumnValue($data, $column, $field);

			if($value)
				$value = trim($value);

			$model->{$field} = $value;
		}

		return $model;
	}

	public function manageModel($data, $model)
	{
		return $model;
	}

	public function insertEntity($data)
	{
		$model = $this->getModel($data);

		$this->bind($data, $model);

		$model = $this->manageModel($data, $model);

		if(! $model)
			return false;

		return !! $model->save();
	}

	public function getRowData($fileimportationrow)
	{
		$rowData = json_decode($fileimportationrow->data);

		$requestData = $this->getRequestExtraData();

		foreach($requestData as $name => $value)
			$rowData->{$name} = $value;

		return $rowData;
	}

	public function storeRow($fileimportationrow)
	{
		$data = $this->getRowData($fileimportationrow);

		if($this->debug)
		{
			if(($message = $this->insertEntity($data)) === true)
				return $fileimportationrow->delete();

			dd($message);
		}
		else
		{
			try
			{
				if(($message = $this->insertEntity($data)) === true)
					return $fileimportationrow->delete();
			}
			catch(\Exception $e)
			{
				return $fileimportationrow->setParsed($e->getMessage(), $e->getCode());
			}

			return $fileimportationrow->setParsed($message);			
		}

	}

	public function _parseSlice()
	{
		foreach($this->rows as $row)
		{
			if($this->debug)
				$this->storeRow($row);

			else
				try
				{
					$this->storeRow($row);
				}
				catch(\Exception $e)
				{
					$row->setParsed($e->getMessage(), $e->getCode());
				}
		}
	}

}