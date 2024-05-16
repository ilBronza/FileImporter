<?php

namespace IlBronza\FileImporter\Http\Controllers;

use App\Http\Controllers\Controller;

use Carbon\Carbon;
use IlBronza\FileImporter\Http\Controllers\Traits\FileImporterFormTrait;
use IlBronza\FileImporter\Http\Controllers\Traits\FileImporterGettingModelTrait;
use IlBronza\FileImporter\Http\Controllers\Traits\FileImporterParseTrait;
use IlBronza\FileImporter\Http\Controllers\Traits\FileImporterRoutingTrait;
use IlBronza\FileImporter\Http\Controllers\Traits\FileImporterStoreTrait;
use IlBronza\FileImporter\Models\Fileimportation;
use IlBronza\FileImporter\Models\Fileimportationrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileImporterController extends Controller
{
	// use FileImporterSessionTrait;
	use FileImporterFormTrait;
	use FileImporterStoreTrait;
	use FileImporterParseTrait;
	use FileImporterRoutingTrait;
	use FileImporterGettingModelTrait;

	public $shouldRetrieveModel;
	public $mustRetrieveModel;

	public $baseRouteName;
	public $importingRoute;
	public $intestationFieldsCheck;

	public $formView;
	public $storeView;
	public $parseView;

	public $rowIntestationNumbers;

	public $createManyChunk = 200;
	public $parsingSliceSize = 100;
	public $parsingTimeoutSeconds = 60;
	public $fullModelClass;
	public $retrievingModelFields;
	public $parseInterval = 500;

	public $debug = false;

	public function form()
	{
		$form = $this->getForm();

		$formView = $this->getFormView();

		return view($formView, ['form' => $form]);
	}

	public function getParseInterval()
	{
		return $this->parseInterval;
	}

	// public function storeImportingrow(array $data)
	// {
	// 	return Fileimportationrow::create(
	// 		$this->getCreateRowData($data)
	// 	);
	// }

	private function getControllerName()
	{
		return get_class($this);
	}

	public function getParseView()
	{
		if($this->parseView)
			return $this->parseView;

		return 'fileimporter::parse';		
	}

	public function getValidationParameters()
	{
		$result = [
			'file' => 'required|file',
		];

		$extraFields = $this->getFormExtraFields();

		foreach($extraFields as $extraField)
			$result[$extraField->getName()] = $extraField->getRules();

		return $result;
	}

	public function performControllerOperations()
	{
		
	}

	public function performLateControllerOperations()
	{
		
	}

	public function import(Request $request)
	{
		ini_set('max_execution_time', "300");
		ini_set('memory_limit', "-1");

		$parameters = $request->validate(
			$this->getValidationParameters()
		);

		$this->performControllerOperations();

		$this->setFilename($request);

		$parameters = $this->addFileimportationParameters($parameters);

		$this->setFileImportation($request, $parameters);

		$this->performLateControllerOperations();

		return view($this->getStoreView(), ['fileimportation' => $this->fileimportation]);
	}

	public function store(Fileimportation $fileimportation)
	{
		ini_set('max_execution_time', "-1");
		ini_set('memory_limit', "-1");

		$this->fileimportation = $fileimportation;

		$this->fileimportation->setStoringStarted();

		$this->prepareEnvironment();

		$i = 0;
		$createManyPortion = [];

		foreach($this->sheetData as $data)
		{
			$createManyPortion[] = $this->getCreateRowData($data);
			// $this->storeImportingrow($data);

			$i ++;

			if($i >= $this->createManyChunk)
			{
				Fileimportationrow::insert($createManyPortion);

				$i = 0;
				$createManyPortion = [];

				usleep(10000);
			}
		}

		Fileimportationrow::insert($createManyPortion);

		$this->fileimportation->setStoringEnded();
	}

	public function getParsingTimeout()
	{
		return $this->parsingTimeoutSeconds;
	}

	public function storeEnded(Fileimportation $fileimportation)
	{
		return $this->redirectJavascript(
			$this->getParsingRoute($fileimportation)
		);
	}

	// private function checkNextOperation()
	// {
	// 	if($this->lastSlice)
	// 	{
	// 		$this->fileimportation->setImported();

	// 		if($afterParseRedirect = $this->getAfterParseRedirrect())
	// 			return redirect()->to($afterParseRedirect);

	// 		return 'imported';
	// 	}

	// 	return $this->goToParse();
	// }

	public function parse(Fileimportation $fileimportation)
	{
		$this->fileimportation = $fileimportation;

		return view($this->getParseView(), [
			'parseInterval' => $this->getParseInterval(),
			'fileimportation' => $fileimportation,
			'keepParsingInterval' => $this->getJavascriptKeepParsingTimeout(),
			'keepParsingRoute' => $this->getKeepParsingRoute(),
			'parseProgressbarRoute' => $this->getParseProgressbarRoute()
		]);
	}

	public function getJavascriptKeepParsingTimeout()
	{
		return $this->getParsingTimeout() * 1000 + 500;
	}

	public function getParsingLimit()
	{
		return Carbon::now()->subSeconds(
			$this->getParsingTimeout()
		);
	}

	public function startParsing(Fileimportation $fileimportation)
	{
		$fileimportation->startParsingIfNotJet();

		return $this->keepParsing($fileimportation);
	}

	// public function parseSlice(Fileimportation $fileimportation)
	public function keepParsing(Fileimportation $fileimportation)
	{
		$this->fileimportation = $fileimportation;

		$fileimportation->keepParsing();

		$this->setSlice();

		$this->_parseSlice();

		if($this->lastSlice)
			$fileimportation->setParsingEnded();
	}

	public function manageParsingEnding(Fileimportation $fileimportation)
	{
		return $this->redirectJavascript(
			$this->getParsingEndedRoute($fileimportation)
		);
	}

	// private function goToParse()
	// {
	// 	return redirect()->to(
	// 		$this->getParsingRoute()
	// 	);
	// }
}