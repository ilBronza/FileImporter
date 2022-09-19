@extends('app')
@section('content')

<!-- START \views\destinataries\_create.blade.php -->

<script type="text/javascript">


	jQuery(document).ready(function($)
	{
		setInterval(function()
		{
			$.ajax({
				url : '{{ route('fileimportations.storeProgressbar', [$fileimportation]) }}',
				type : 'POST',
				success : function(result)
				{
					if(result.success == true)
					{
						if(typeof result.redirect !== 'undefined')
							window.location.href = result.redirect;

						$('#progressbar').attr('value', result.percentage);
						$('#managed').html(result.managed);
					}

					console.log(result);
					// else
						// location.reload();
				},
				error: function (result, textStatus, message)
				{
					if(typeof result.responseJSON.message !== 'undefined')
						alert(result.responseJSON.message);

					else alert(message);
				}
			});
		}, 500);

	});
</script>

<div class="uk-card uk-card-default">

	<div class="uk-card-header">
		Importazione del file {{ $fileimportation->filename }}
	</div>
	<div class="uk-card-body">
		<progress id="progressbar" class="uk-progress" value="0" max="100"></progress>
	</div>
	<div class="uk-card-footer">
		<span>Righe inserite: <span id="managed"></span></span>
	</div>
</div>

<!-- END \views\destinataries\_create.blade.php -->
@endsection

