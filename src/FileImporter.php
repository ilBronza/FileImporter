<?php

namespace IlBronza\FileImporter;

use IlBronza\FileImporter\Models\Fileimportationrow;

class FileImporter
{
	static function getRowsToAnalizeCount()
	{
		return cache()->remember(
			'fileimportationrowsToAnalize',
			180,
			function()
			{
				return Fileimportationrow::notParsed()->count();
			}
		);
	}
}