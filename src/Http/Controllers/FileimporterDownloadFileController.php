<?php

namespace IlBronza\FileImporter\Http\Controllers;

use IlBronza\CRUD\CRUD;
use IlBronza\FileImporter\Models\Fileimportation;
use Illuminate\Support\Facades\Storage;

class FileimporterDownloadFileController extends CRUD
{
    public $allowedMethods = [
        'download',
    ];    

    public function download(Fileimportation $fileimportation)
    {
        //occhio che sta roba è creata per Idealpack, bisogna mettere il file nella config e tutto e poi spostare nel progetto. Dei

        if(! stripos($fileimportation->controller, 'ReadOrderFileController'))
            dd('creare lo script generico');

        $content = Storage::disk('XmlArchive')->get($fileimportation->filename);

        return response($content)
            ->header('Content-Type', Storage::disk('XmlArchive')->mimeType($fileimportation->filename))
            ->header('Content-Disposition', 'attachment; filename="' . basename($fileimportation->filename) . '"');

        }
}

