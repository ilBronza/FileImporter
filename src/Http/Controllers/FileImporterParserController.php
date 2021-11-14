<?php

namespace IlBronza\FileImporter\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use IlBronza\FileImporter\Http\Controllers\Traits\FileImporterSessionTrait;
use IlBronza\FileImporter\Models\Fileimportation;

class FileImporterParserController extends Controller
{
	use FileImporterSessionTrait;

	public function parse(Fileimportation $fileimportation)
	{
		return app($fileimportation->controller)->parse($fileimportation);
	}

	public function parseAll()
	{
		$fileimportation = Fileimportation::take(1)->inRandomOrder()->get()->first();

		return redirect()->route('fileimportations.parse', ['fileimportation' => $fileimportation->getKey()]);
	}

	public function startFileImportation(Fileimportation $fileimportation)
	{
		return app($fileimportation->controller)->store($fileimportation);
	}

	public function startFileParsing(Fileimportation $fileimportation)
	{
		return app($fileimportation->controller)->startParsing($fileimportation);		
	}

	public function keepFileParsing(Fileimportation $fileimportation)
	{
		return app($fileimportation->controller)->keepParsing($fileimportation);		
	}

	public function storeEnded(Fileimportation $fileimportation)
	{
		return app($fileimportation->controller)->storeEnded($fileimportation);		
	}

	public function storeProgressbar(Fileimportation $fileimportation)
	{
		if(! $fileimportation->hasStartedStoring())
			return $this->startFileImportation($fileimportation);

		if($fileimportation->hasEndedStoring())
			return $this->storeEnded($fileimportation);

		$this->fileimportation = $fileimportation;

		$inserted = $fileimportation->fileimportationrows()->count();

		if(($inserted == 0)||(empty($this->fileimportation->getRowsCount())))
			return [
				'success' => true,
				'total' => 0,
				'managed' => 'waiting',
				'percentage' => 0
			];

		return [
			'success' => true,
			'total' => $this->fileimportation->getRowsCount(),
			'managed' => $inserted,
			'percentage' => $inserted / $this->fileimportation->getRowsCount() * 100
		];
	}

	public function getParsingTimeout(Fileimportation $fileimportation)
	{
		return app($fileimportation->controller)->getParsingTimeout();		
	}

	public function keepParsing(Fileimportation $fileimportation)
	{
		return $this->keepFileParsing($fileimportation);
	}

	public function manageParsingEnding(Fileimportation $fileimportation)
	{
		return app($fileimportation->controller)->manageParsingEnding($fileimportation);
	}

	public function getTotalRowsCount()
	{
		$dedicatedController = app($this->fileimportation->controller);

		if(method_exists($dedicatedController, 'getTotalRowsCount'))
			return $dedicatedController->getTotalRowsCount($this->fileimportation);

		return $this->fileimportation->fileimportationrows()->withTrashed()->count();
	}

	public function getManagedRowsCount()
	{
		$dedicatedController = app($this->fileimportation->controller);

		if(method_exists($dedicatedController, 'getManagedRowsCount'))
			return $dedicatedController->getManagedRowsCount($this->fileimportation);

		return $this->fileimportation->fileimportationrows()->parsed()->withTrashed()->count();
	}

	public function parseProgressbar(Fileimportation $fileimportation)
	{
		if(! $fileimportation->hasStartedParsing())
			return $this->startFileParsing($fileimportation);

		if($fileimportation->hasEndedParsing())
			return $this->manageParsingEnding($fileimportation);

		$this->fileimportation = $fileimportation;

		$total = $this->getTotalRowsCount();

		$managed = $this->getManagedRowsCount();

		return [
			'success' => true,
			'total' => $total,
			'managed' => $managed,
			'percentage' => $managed / $total * 100
		];
	}
}