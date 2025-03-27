<?php

namespace IlBronza\FileImporter\Models;

use Carbon\Carbon;
use IlBronza\CRUD\Traits\Model\CRUDModelTrait;
use IlBronza\FileImporter\Models\Fileimportationrow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fileimportation extends Model
{
	// public function __construct(array $attributes = array())
	// {
	// 	parent::__construct($attributes);

	// 	$this->setConnection(env('IB_IMPORTATINROW_DATABASECONNECTION', env('DB_CONNECTION')));
	// }

	use CRUDModelTrait;
	use SoftDeletes;

	protected $fillable = [
		'user_id',
		'controller',
		'temporary_filepath',
		'filename',
		'request_data'
	];

	protected $casts = [
		'request_data' => 'array'
		'storing_started_at' => 'datetime',
		'storing_ended_at' => 'datetime',
		'parsing_started_at' => 'datetime',
		'parsing_ended_at' => 'datetime',
	];

	public function getImportationType()
	{
		return class_basename($this->controller);
	}

	public function hasStartedStoring()
	{
		return !! $this->storing_started_at;
	}

	public function hasEndedStoring()
	{
		return !! $this->storing_ended_at;
	}

	public function hasStartedParsing()
	{
		return !! $this->parsing_started_at;
	}

	public function hasEndedParsing()
	{
		return !! $this->parsing_ended_at;
	}

	public function fileimportationrows()
	{
		return $this->hasMany(Fileimportationrow::class);
	}

	public function allFileimportationrows()
	{
		return $this->hasMany(Fileimportationrow::class)->withTrashed();		
	}

	public function getRequestExtraData() : array
	{
		return $this->request_data;
	}

	public function setImported()
	{
		$this->delete();
	}

	public function getTemporaryFilePath()
	{
		return $this->temporary_filepath;
	}

	public function getStorageTemporaryFilePath()
	{
		return storage_path('app/' . $this->getTemporaryFilePath());
	}

	public function getRowsCount()
	{
		return $this->rows_count;
	}

	public function setStoringStarted()
	{
		$this->storing_started_at = Carbon::now();
		$this->save();
	}

	public function setStoringEnded()
	{
		$this->storing_ended_at = Carbon::now();
		$this->save();
	}

	public function setParsingEnded()
	{
		$this->parsing_ended_at = Carbon::now();
		$this->save();
	}

	public function isParsing()
	{
		return !! $this->parsing_started_at;
	}

	public function isParsingSince(Carbon $date)
	{
		return $this->parsing_keep_at < $date;
	}

	// public function getLastParsingStart()
	// {
	// 	return $this->parsing_keep_at;
	// }

	public function startParsingIfNotJet()
	{
		if($this->parsing_started_at)
			return null;

		$this->parsing_started_at = Carbon::now();
		$this->save();
	}

	public function keepParsing()
	{
		$this->parsing_keep_at = Carbon::now();
		$this->save();		
	}
}