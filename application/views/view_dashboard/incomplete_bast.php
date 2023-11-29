<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Incomplete BAST</h3>
		
    </div>
    <div class="box-body" style='overflow-x:scroll'>
        <table class="table table-bordered table-striped" id='table_late'>
            <thead>
                <tr class='bg-blue'>
					<th class="text-center">No</th>
					<th class="text-center">Kode Alat</th>
					<th class="text-center">Nama Alat</th>
					<th class="text-center">Qty Sisa</th>	
					<th class="text-center">BAST</th>
					<th class="text-center">No Quotation</th>
					<th class="text-center">No SO</th>
					<th class="text-center">Tipe</th>
					<th class="text-center">Cust / Subcon</th>
					<th class="text-center">Kategori</th>
					<th class="text-center">Keterangan</th>				
                </tr>
            </thead>
            <tbody>
            <?php
               if($results){
					$intL	=0;
					foreach($results as $key=>$val){
						$intL++;
						if($val['flag_type']=='CUST'){
							$template	= '<span class="badge bg-maroon">Customer</span>';
						}else{
							$template	= '<span class="badge bg-aqua">Subcon</span>';
						}
						
						if($val['type_bast']=='REC'){
							$template2	= '<span class="badge bg-purple">Penerimaan</span>';
						}else{
							$template2	= '<span class="badge bg-green">Pengiriman</span>';
						}
						echo"<tr>";
							echo"<td class='text-center'>".$intL."</td>";
							echo"<td class='text-left'>".$val['tool_id']."</td>";
							echo"<td class='text-left'>".$val['tool_name']."</td>";
							echo"<td class='text-center'>".number_format($val['qty_sisa'])."</td>";
							echo"<td class='text-left'>".$val['nomor']."</td>";
							echo"<td class='text-left'>".$val['quotation_nomor']."</td>";
							echo"<td class='text-left'>".$val['no_so']."</td>";
							echo"<td class='text-center'>".$template."</td>";
							echo"<td class='text-left'>".$val['name']."</td>";
							echo"<td class='text-center'>".$template2."</td>";
							echo"<td class='text-left'>".$val['descr']."</td>";
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
								title: 'Dashboard Incomplete - Data BAST Bulan '+formattedToday,
								messageTop: 'Report Data BAST',
								exportOptions: {
								columns: [0,1,2,3,4,5,6,7,8,9,10]
								}
							},

						],
		});
	});
</script>
