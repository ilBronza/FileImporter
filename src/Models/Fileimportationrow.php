<?php

namespace IlBronza\FileImporter\Models;

use Carbon\Carbon;
use IlBronza\FileImporter\Models\Fileimportation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fileimportationrow extends Model
{
	// public function __construct(array $attributes = array())
	// {
	// 	parent::__construct($attributes);

	// 	$this->setConnection(env('IB_IMPORTATINROW_DATABASECONNECTION', env('DB_CONNECTION')));
	// }

	use SoftDeletes;

	protected $fillable = [
		'fileimportation_id',
		'data',
		'parsed',
		'parsing_notes'
	];

	public function fileimportation()
	{
		return $this->belongsTo(Fileimportation::class);
	}

	public function scopeNotParsed($query)
	{
		return $query->whereNull('parsed');
	}

	public function scopeToParse($query)
	{
		return $query->whereNull('parsed')->orWhere('parsed', false);
	}

	public function scopeParsed($query)
	{
		return $query->whereNotNull('parsed')->orWhereNotNull('deleted_at');
	}

	public function setParsed(string $message)
	{
		$this->parsed = true;
		$this->parsed_at = Carbon::now();
		$this->parsing_notes = $message;
		$this->save();

	}
}