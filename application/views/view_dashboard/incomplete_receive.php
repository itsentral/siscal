<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Incomplete Receive</h3>
		
    </div>
    <div class="box-body" style='overflow-x:scroll'>
        <table class="table table-bordered table-striped" id='table_late'>
            <thead>
                <tr class='bg-blue'>
					<th class="text-center">No</th>
					<td align="center"><b>No Quotation</b></td>
					<td align="center"><b>Tgl Quotation</b></td>
					<td align="center"><b>Perusahaan</b></td>
					<td align="center"><b>No PO</b></td>
					<td align="center"><b>Marketing</b></td>			
                </tr>
            </thead>
            <tbody>
            <?php
               if($results){
					$intL	=0;
					foreach($results as $key=>$vals){
						$intL++;
						echo"<tr>";
							echo"<td class='text-center'>".$intL."</td>";
							echo"<td align='left'>".$vals['nomor']."</td>";
							echo"<td align='center'>".date('d M Y',strtotime($vals['datet']))."</td>";
							echo"<td align='left'>".$vals['customer_name']."</td>";	
							echo"<td align='center'>".$vals['pono']."</td>";
							echo"<td align='left'>".$vals['member_name']."</td>";
						echo"</tr>";
					}
				}	
            ?>
            </tbody>
        </table>
     
    </div><!-- /.box-body -->
</div><!-- /.box -->
<script>
	var base_url			= '<?php echo base_url(); ?>';
	//var active_controller	= 'Dashboard';

	const today = new Date();
	const yyyy = today.getFullYear();
	let mm = today.getMonth() + 1; // Months start at 0!
	let dd = today.getDate();

	//if (dd < 10) dd = '0' + dd;
	if (mm < 10) mm = '0' + mm;

	const formattedToday = mm + '-' + yyyy;

	$(document).ready(function(){
		$('#table_late').dataTable({
			order			: [],
			lengthMenu		: [[10, 25, 50, -1], [10, 25, 50, "All"]],
			iDisplayLength	: 10,
			dom				: 'Bfrtip', 
			buttons			: [
							{
								extend: 'pageLength',
								text:      '<i class="fa fa-list-ol"></i> <b>Show</b>',
								className: "Btntable"
							},
							
							{
								extend: 'excelHtml5',
								text:      '<i class="fa fa-file-excel-o"></i> <b>Download Excel</b>',
								titleAttr: 'Excel',
								className: "Btntable",
								title: 'Dashboard Incomplete - Data Receive Bulan '+formattedToday,
								messageTop: 'Report Data Receive',
								exportOptions: {
								columns: [0,1,2,3,4,5]
								}
							},

						],
		});
	});
</script>
