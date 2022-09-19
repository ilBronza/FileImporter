@extends('app')
@section('content')

<!-- START \views\destinataries\_create.blade.php -->

<script type="text/javascript">

	window.managedRows = -1;
	window.equalManagedRows = 0;

	function keepParsing()
	{
		$.ajax({
			url : '{{ $keepParsingRoute }}',
			type : 'POST'
		});
	}

	jQuery(document).ready(function($)
	{
		window.canOperate = true;

		// keepParsing();

		let parseInterval = setInterval(function()
		{
			if(window.canOperate == false)
				return false;

			$.ajax({
				url : '{{ $parseProgressbarRoute }}',
				type : 'POST',
				success : function(result)
				{
					console.log(result);
					if(result.success == true)
					{
						console.log('result successfull');

						if(result.managed == window.managedRows)
							window.equalManagedRows ++;

						window.managedRows = result.managed;

						if(window.equalManagedRows > 6)
						{
							console.log('keep parsing called');
							keepParsing();

							window.equalManagedRows = 0;
						}

						if(typeof result.redirect !== 'undefined')
						{
							clearInterval(parseInterval);
							window.location.href = result.redirect;
						}

						console.log('update');

						$('#progressbar').attr('value', result.percentage);
						$('#managed').html(result.managed);
					}

					// else
					// 	location.reload();
				},
				error: function (result, message)
				{
					console.log(result);
					alert(message);

					// location.reload();
				}
			});
		}, 500);

	});
</script>

<div class="uk-card uk-card-default">

	<div class="uk-card-header">
		Analisi del file {{ $fileimportation->filename }}
	</div>
	<div class="uk-card-body">
		<progress id="progressbar" class="uk-progress" value="0" max="100"></progress>
	</div>
	<div class="uk-card-footer">
		<span>Righe analizzate: <span id="managed"></span></span>
	</div>
</div>

<!-- END \views\destinataries\_create.blade.php -->
@endsection

