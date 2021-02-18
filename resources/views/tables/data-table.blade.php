<table id="{{ $div ?? 'example' }}" cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered" style="background:#CCC;">
	<thead>
		{!! $table_head ?? '' !!}
	</thead>
	<tbody>
		{!! $rows !!}
	</tbody>
</table>

<script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
  	
  	$("#{{ $div ?? 'example' }}").DataTable({
      dom: '<"btn "B>lTfgtip',
      responsive: false,
        buttons : [
            {
				text:  'Export to CSV',
				extend: 'csvHtml5',
				title: 'Download'
            },
            {
				text:  'Export to Excel',
				extend: 'excelHtml5',
				title: 'Download'
            }
          ]
  	});

    // $("table").tablecloth({
    //   theme: "paper",
    //   striped: true,
    //   sortable: true,
    //   condensed: true
    // });
   
  });
</script>