@extends('uikittemplate::app')
@section('content')

<!-- START \views\destinataries\_create.blade.php -->

<div class="uk-card uk-card-default">

	{{-- @include('utilities.cards.header') --}}

	<div class="uk-card-body">

		{!! $form->render() !!}

{{-- 		<form
			id="{{ $formId ?? 'form' . rand(1, 999999) }}"
			method="POST"
			role="form" 
			enctype="multipart/form-data" 
			class="uk-form uk-form-stacked" action="{{ $action?? Request::url() }}">

			{{ csrf_field() }}

			<input type="file" name="file" />

			@if ($errors->any())
			    <div class="alert alert-danger">
			        <ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
			    </div>
			@endif --}}

			<input class="uk-button uk-button-secondary" type="submit" value="save" name="{{ trans('generals.save') }}" />
		</form>
	</div>
</div>

<!-- END \views\destinataries\_create.blade.php -->
@endsection

