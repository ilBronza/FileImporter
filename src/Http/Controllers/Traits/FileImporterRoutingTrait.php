<?php

namespace IlBronza\FileImporter\Http\Controllers\Traits;

use IlBronza\FileImporter\Models\Fileimportation;
use Illuminate\Support\Facades\Route;


trait FileImporterRoutingTrait
{
	public function getKeepParsingRoute()
	{
		return route('fileimportations.keepParsing', [$this->fileimportation]);
	}

	public function getParseProgressbarRoute()
	{
		return route('fileimportations.parseProgressbar', [$this->fileimportation]);
	}

	public function getRouteBasename()
	{
		if($this->baseRouteName)
			return $this->baseRouteName;

		$routePieces = explode(".", Route::currentRouteName());

		return array_shift($routePieces);
	}

	public function getRouteParameters()
	{
		return [];
	}

	public function getImportingRouteByType(string $type)
	{
		$routeBasename = $this->getRouteBasename();
		$routeParameters = $this->getRouteParameters();

		return route(implode(".", [$routeBasename, 'import', $type]), $routeParameters);
	}

	public function getParsingRoute(Fileimportation $fileimportation)
	{
		return route('fileimportations.parse', ['fileimportation' => $fileimportation->getKey()]);
	}

	// private function getAfterParseRedirrect() : ? string
	// {
	// 	if(isset($this->afterParseRoute))
	// 		return route($this->afterParseRoute);

	// 	return null;
	// }

	public function getSummaryRoute(Fileimportation $fileimportation)
	{
		return route('fileimportations.summary', ['fileimportation' => $fileimportation->getKey()]);
	}

	public function getParsingEndedRoute(Fileimportation $fileimportation)
	{
		if(isset($this->afterParseRoute))
			return route($this->afterParseRoute);

		return $this->getSummaryRoute($fileimportation);
	}

	public function redirectJavascript(string $href)
	{
		return [
			'success' => true,
			'redirect' => $href
		];
	}

}