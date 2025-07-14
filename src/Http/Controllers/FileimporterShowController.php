<?php

namespace IlBronza\FileImporter\Http\Controllers;

use IlBronza\CRUD\CRUD;
use IlBronza\FileImporter\Models\Fileimportation;

class FileimporterShowController extends CRUD
{
    public $allowedMethods = [
        'popupRequestData',
    ];    

    public function popupRequestData(Fileimportation $fileimportation)
    {
        //$this->addExtraView('top', 'folder.subFolder.viewName', ['some' => $thing]);

        $value = $fileimportation->request_data;

        $prettyJson = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Escape per HTML
        $escaped = htmlspecialchars($prettyJson);

        // Inseriscilo in una <pre> per mantenere la formattazione
        return "<pre style='max-height:300px; overflow:auto; font-size:11px; margin:0'>" . $escaped . "</pre>";
    }
}

