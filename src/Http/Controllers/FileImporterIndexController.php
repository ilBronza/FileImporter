<?php

namespace IlBronza\FileImporter\Http\Controllers;

use IlBronza\CRUD\CRUD;
use IlBronza\CRUD\Traits\CRUDBelongsToManyTrait;
use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDDeleteTrait;
use IlBronza\CRUD\Traits\CRUDDestroyTrait;
use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;
use IlBronza\CRUD\Traits\CRUDUpdateEditorTrait;
use IlBronza\FileImporter\Models\Fileimportation;
use Illuminate\Http\Request;

class FileImporterIndexController extends CRUD
{
    // use CRUDShowTrait;
    use CRUDIndexTrait;
    use CRUDPlainIndexTrait;
    // use CRUDEditUpdateTrait;
    // use CRUDUpdateEditorTrait;
    // use CRUDCreateStoreTrait;

    // use CRUDDeleteTrait;
    // use CRUDDestroyTrait;

    // use CRUDRelationshipTrait;
    // use CRUDBelongsToManyTrait;

    public static $tables = [

        'index' => [
            'fields' => 
            [
                'id' => [
                    'view' => 'flat',
                    'width' => '35px',
                    'order' => [
                        'type' => 'desc'
                    ]
                    //'visible' => false
                ],
                'mySelfShow' => 'links.see',
                'mySelfImportation' => '_fn_getImportationType',
                'user_id' => 'users.name',
                'filename' => 'flat',
                'rows_count' => 'flat',
                'mySelfErrors.fileimportationrows_count' => 'flat',
                'mySelfTotal.all_fileimportationrows_count' => 'flat',
                'storing_started_at' => 'dates.date',
                'parsing_ended_at' => 'dates.date',
            ]
        ]
    ];

    /**
     * subject model class full path
     **/
    public $modelClass = Fileimportation::class;

    /**
     * http methods allowed. remove non existing methods to get a 403
     **/
    public $allowedMethods = [
        'index',
        // 'show',
        // 'edit',
        // 'update',
        // 'create',
        // 'store',
        // 'destroy',
        // 'deleted',
        // 'archived',
        // 'reorder',
        // 'storeReorder'
    ];

    /**
     * to override show view use full view name
     **/
    //public $showView = 'products.showPartial';

    // public $guardedEditDBFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
    // public $guardedCreateDBFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
    // public $guardedShowDBFields = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * relations called to be automatically shown on 'show' method
     **/
    //public $showMethodRelationships = ['posts', 'users', 'operations'];

    /**
        protected $relationshipsControllers = [
        'permissions' => '\IlBronza\AccountManager\Http\Controllers\PermissionController'
    ];
    **/


    /**
     * getter method for 'index' method.
     *
     * is declared here to force the developer to rationally choose which elements to be shown
     *
     * @return Collection
     **/

    public function getIndexElements()
    {
        return Fileimportation::withCount('fileimportationrows', 'allFileimportationrows')->get();
    }


    /**
     * parameter that decides which fields to use inside index table
     **/
    //  public $indexFieldsGroups = ['index'];

    /**
     * parameter that decides if create button is available
     **/
     public $avoidCreateButton = true;

    // /**
    //  * START base methods declared in extended controller to correctly perform dependency injection
    //  *
    //  * these methods are compulsorily needed to execute CRUD base functions
    //  **/
    // public function show(Fileimportation $fileimportation)
    // {
    //     //$this->addExtraView('top', 'folder.subFolder.viewName', ['some' => $thing]);

    //     return $this->_show($fileimportation);
    // }

    // public function edit(Fileimportation $fileimportation)
    // {
    //     return $this->_edit($fileimportation);
    // }

    // public function update(Request $request, Fileimportation $fileimportation)
    // {
    //     return $this->_update($request, $fileimportation);
    // }

    // public function destroy(Fileimportation $fileimportation)
    // {
    //     return $this->_destroy($fileimportation);
    // }

    // /**
    //  * END base methods
    //  **/



}

