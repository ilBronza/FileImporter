<?php 

Route::group([
	'middleware' => ['web', 'auth'],
	'namespace' => 'IlBronza\FileImporter\Http\Controllers'
	],
	function()
	{
		Route::get('file-fileimportations', 'FileImporterIndexController@index')->name('fileimportations.index');

		Route::get('file-fileimportation/{fileimportation}/parse', 'FileImporterParserController@parse')->name('fileimportations.parse');

		Route::get('file-fileimportation/{fileimportation}/summary', 'FileImporterSummaryController@index')->name('fileimportations.show');

		Route::post('file-fileimportation/{fileimportation}/store-progressbar', 'FileImporterParserController@storeProgressbar')->name('fileimportations.storeProgressbar');

		Route::post('file-fileimportation/{fileimportation}/parse-progressbar', 'FileImporterParserController@parseProgressbar')->name('fileimportations.parseProgressbar');

		Route::post('file-fileimportation/{fileimportation}/keep-parsing', 'FileImporterParserController@keepParsing')->name('fileimportations.keepParsing');



		Route::get('file-fileimportation/parse/all', 'FileImporterParserController@parseAll')->name('fileimportations.parseAll');

		
	});

