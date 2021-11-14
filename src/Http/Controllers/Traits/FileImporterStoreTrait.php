<?php

namespace IlBronza\FileImporter\Http\Controllers\Traits;

use PhpOffice\PhpSpreadsheet\IOFactory;
use IlBronza\FileImporter\Models\Fileimportation;
use Illuminate\Http\Request;
use Auth;

trait FileImporterStoreTrait
{
	public function addFileimportationParameters(array $parameters) : array
	{
		return $parameters;
	}

	private function setFileImportation(Request $request, $requestData)
	{
		$path = $request->file('file')->store('fileimportations');

		$this->fileimportation = Fileimportation::create([
			'user_id' => Auth::id() ?? null,
			'controller' => $this->getControllerName(),
			'filename' => $request->file('file')->getClientOriginalName(),
			'temporary_filepath' => $path,
			'request_data' => $requestData
		]);
	}

	public function getStoreView()
	{
		if($this->storeView)
			return $this->storeView;

		return 'fileimporter::store';
	}

	private function getIntestationFieldsCheck()
	{
		return $this->intestationFieldsCheck;
	}

	private function mustCheckIntestationValidity()
	{
		return !! $this->getIntestationFieldsCheck();
	}

	private function checkIntestationValidity()
	{
		$checkRowFields = $this->getIntestationFieldsCheck();

		foreach($checkRowFields as $index => $checkRowField)
		{
			if(count($checkRowField) == 0)
				continue;

			$givenIntestation = $this->intestation[$index];

			if(count($givenIntestation) != count($checkRowField))
				throw new \Exception('mancata corrispondenza nella conta dei campi in intestazione');

			$i = 0;

			foreach($givenIntestation as $letterIndex => $valueChecker)
			{
				if($valueChecker != $checkRowField[$i])
					throw new \Exception('mancata corrispondenza nell\' intestazione: ' . $valueChecker . ' vs ' . $checkRowField[$i]);

				$i ++;
			}
		}
	}

	private function getShiftedFirstRowIntestation()
	{
		$intestationRow = array_shift($this->sheetData);

		$result = [];

		foreach($intestationRow as $columnLetter => $cellValue)
			$result[$columnLetter] = trim($cellValue);

		return $result;
	}

	private function manageIntestation()
	{
		if($this->rowIntestationNumbers)
		{
			$this->intestation = [];

			if((is_int($this->rowIntestationNumbers))&&($this->rowIntestationNumbers > 1))
				for($i = 0; $i < $this->rowIntestationNumbers; $i++)
					$this->intestation[] = $this->getShiftedFirstRowIntestation();

			else
				$this->intestation[] = $this->getShiftedFirstRowIntestation();
		}

		if($this->mustCheckIntestationValidity())
			$this->checkIntestationValidity();
	}

	public function _prepareEnvironment(array $importing = [])
	{
		$this->importingFields = [];

		foreach($importing as $field => $value)
			$this->importingFields[$field] = $value;

		$data = IOFactory::load($this->fileimportation->getStorageTemporaryFilePath());

		$this->sheetData = $data->getActiveSheet()->toArray(null, true, true, true);

		$this->manageIntestation();		

		$this->fileimportation->rows_count = count($this->sheetData);
		$this->fileimportation->save();
	}

	public function mustStoreFilename()
	{
		return $this->storeFilename ?? false;
	}

	public function setFilename(Request $request)
	{
		if(! $this->mustStoreFilename())
			return;

		$this->filename = $request->file('file')->getClientOriginalName();
	}

	public function prepareEnvironment()
	{
		$requestExtraData = $this->getRequestExtraData();

		$this->_prepareEnvironment($requestExtraData);
	}

	public function getRequestExtraData()
	{
		return $this->fileimportation->getRequestExtraData();
	}

	protected function getCreateRowData(array $data)
	{
		// $row = array_merge(
		// 	$data,
		// 	$this->importingFields
		// );

		return [
			'fileimportation_id' => $this->fileimportation->getKey(),
			// 'data' => json_encode($row)
			'data' => json_encode($data)
		];
	}

	
}