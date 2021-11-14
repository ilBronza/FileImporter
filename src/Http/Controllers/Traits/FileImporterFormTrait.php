<?php

namespace IlBronza\FileImporter\Http\Controllers\Traits;

use IlBronza\FormField\Fields\FileFormField;
use IlBronza\FormField\FormField;
use IlBronza\Form\Form;

trait FileImporterFormTrait
{
	public function getForm() : Form
	{
		$parameters['headerTitle'] = $this->getHeaderTitle();

		$form = Form::createFromArray([
			'action' => $this->getLoadFileAction(),
			'method' => 'POST'
		]);

		foreach($this->getFormFields() as $field)
			$form->addFormField(
				$field
				// FormField::createFromArray([
				// 	'name' => 'name',
				// 	'type' => 'text',
				// 	'required' => true
				// ])
			);

		return $form;
	}

	public function getFileField() : FileFormField
	{
		return FormField::createFromArray([
					'name' => 'file',
					'type' => 'file',
					'dropzone' => false,
					'required' => true
				]);
	}

	public function getFormExtraFields() : array
	{
		return [];
	}

	public function getFormFields() : array
	{
		$result = $this->getFormExtraFields();

		$result[] = $this->getFileField();

		return $result;
	}

	public function getFormViewParameters() : array
	{
		return [];
	}

	public function getHeaderTitle()
	{
		return __('fileimporters.' . $this->baseRouteName);
	}

	public function getFormView()
	{
		if($this->formView)
			return $this->formView;

		return 'fileimporter::form';
	}

	public function getLoadFileAction()
	{
		if($this->importingRoute ?? false)
			return route($this->importingRoute);

		return $this->getImportingRouteByType('import');
	}


}