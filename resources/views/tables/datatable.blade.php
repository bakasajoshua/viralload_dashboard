<table id="<?= (isset($div_name)) ? @$div_name : @'example'; ?>" cellspacing="1" cellpadding="3"
  class="tablehead table table-striped table-bordered" style="background:#CCC;">
  <thead>
    <?php if(isset($th)) { 
			echo $th; 
		} else {?>
    <tr class="colhead">
      <th rowspan="2">#</th>
      <th rowspan="2"><?= (isset($subcounty)) ? @'Sub-County' : @'County'; ?></th>
      <th rowspan="2"># Sites</th>
      <th rowspan="2">All Tests</th>
      <?php if (!isset($subcounty)) {?><th rowspan="2">PMTCT Need</th><?php } ?>
      <th rowspan="2">Actual Infants Tested</th>
      <th colspan="2">Initial PCR</th>
      <th colspan="2">2nd/3rd PCR</th>
      <th colspan="2">Confirmatory PCR</th>
      <th colspan="2">Infants &lt;2Weeks</th>
      <th colspan="2">Infants &lt;=2M</th>
      <th colspan="2">Infants &gt;=2M</th>
      <th rowspan="2">Median Age</th>
      <th rowspan="2">Rejected</th>
    </tr>
    <tr>
      <th>Tests</th>
      <th>Pos</th>
      <th>Tests</th>
      <th>Pos</th>
      <th>Tests</th>
      <th>Pos</th>
      <th>Tests</th>
      <th>Pos</th>
      <th>Tests</th>
      <th>Pos</th>
      <th>Tests</th>
      <th>Pos</th>
    </tr>
    <?php } ?>
  </thead>
  <tbody>
    {!!$outcomes!!}
  </tbody>
</table>

<script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
  	
  	// $('#example').DataTable();

  	$("#<?= (isset($div_name)) ? @$div_name : @'example'; ?>").DataTable({
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