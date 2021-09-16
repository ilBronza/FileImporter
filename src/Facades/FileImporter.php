<?php

namespace IlBronza\FileImporter\Facades;

use Illuminate\Support\Facades\Facade;

class FileImporter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'fileimporter';
    }
}
