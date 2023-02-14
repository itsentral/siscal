<?php
$this->load->view('include/side_menu'); 
?> 
<div class="box box-warning">
	<div class="box-header">
		<h3 class="box-title">
			<i class="fa fa-money"></i> <?php echo('<span class="important">'.$title.'</span>'); ?>
		</h3>
		<div class="box-titles pull-right">
			<?php
				if($akses_menu['download']=='1'){
			?>
				<button type="button" class="btn btn-md btn-success" id="btn-excel"><i class="fa fa-file"></i> Download Excel</button>
			<?php
				}
			?>
		</div>
	</div>
	<div class="box-body">
		<table id="my-grid" class="table table-bordered table-striped">
			<thead>
				<tr class="bg-blue">
					<th class="text-center">No SO</th>
					<th class="text-center">Date</th>
					<th class="text-center">Customer</th>
					<th class="text-center">Quotation</th>
					<th class="text-center">No PO</th>
					<th class="text-center">DPP SO</th>
					<th class="text-center">Insitu</th>
					<th class="text-center">Akomodasi</th>
					<th class="text-center">Total DPP</th>
					<th class="text-center">PPN</th>
					<th class="text-center">Grand Total</th>
					<th class="text-center">Marketing</th>
				</tr>
			</thead>

			<tbody id="list_detail">
		   
			</tbody>
			
		</table>
				
	</div>		
</div>

<?php $this->load->view('include/footer'); ?>
<script>
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= 'Laporan_uninvoice';
	$(document).ready(function(){
		data_display();
		$('#btn-excel').click(function(){
			var Links		= base_url+'index.php/'+active_controller+'/excel_laporan_uninvoice';
			//alert(Links);
			window.open(Links,'_blank');
		});
	});
	/*
	function data_display(){
		var table_data = $('#my-grid').dataTable( {
			"paging"	: true,
			"processing": true,
			"serverSide": true,
			'destroy'	: true,
			"ajax": {
				"url"	:  base_url + active_controller+'/get_data_display',
				"type"	: "POST"
							
			},		 
			"columns": [
				{"data":"no_so"},
				{"data":"tgl_so","sClass":"text-center"},
				{"data":"customer_name","sClass":"text-left"},
				{"data":"quotation_nomor","sClass":"text-left"},
				{"data":"pono","sClass":"text-center"},
				{"data":"total_so","sClass":"text-right"},				
				{"data":"tot_insitu","sClass":"text-right"},
				{"data":"total_akomodasi","sClass":"text-right"},
				{"data":"quot_net","sClass":"text-right"},
				{"data":"ppn","sClass":"text-right"},
				{"data":"grand_tot","sClass":"text-right"},
				{"data":"member_name","sClass":"text-center"},
			],
			"rowCallback": function(row,data,index,iDisplayIndexFull){
				var tot_so		= parseFloat(data.total_so.replace(/\,/g,''));	
				var total_quot	= parseFloat(data.grand_tot.replace(/\,/g,''));
				var total_insitu= parseFloat(data.tot_insitu.replace(/\,/g,''));
				var total_akom	= parseFloat(data.total_akomodasi.replace(/\,/g,''));
				var total_ppn	= parseFloat(data.ppn.replace(/\,/g,''));
				var total_dpp	= total_quot - total_ppn - total_akom - total_insitu;
				var nomor_so	= data.no_so;
				var nomor_first	= data.first_so;
				var nilai_akom 	= 0;
				var nilai_insitu= 0;
				if(nomor_first == nomor_so || total_dpp == tot_so){
					nilai_akom	= total_akom;
					if(data.flag_so_insitu =='Y'){
						nilai_insitu	= total_insitu;
					}
				}
				var nilai_total	= tot_so + nilai_akom + nilai_insitu;
				var  ppn_so	=0;
				if(total_ppn > 0){
					ppn_so	= Math.round(nilai_total * 0.1);
				}
				var grand_so		= nilai_total + ppn_so;
				
				$('td:eq(6)',row).html(nilai_insitu.format(0,3,','));
				$('td:eq(7)',row).html(nilai_akom.format(0,3,','));
				$('td:eq(8)',row).html(nilai_total.format(0,3,','));
				$('td:eq(9)',row).html(ppn_so.format(0,3,','));
				$('td:eq(10)',row).html(grand_so.format(0,3,','));
				
			},
			"order": [[1,"desc"]]
		});
		
	}
	*/
	
	function data_display(){
		
		let table_data 		= $('#my-grid').dataTable({
			"serverSide": true,
			"destroy"	: true,
			"stateSave" : false,
			"bAutoWidth": false,
			"oLanguage": {
				"sSearch": "<b>Live Search : </b>",
				"sLengthMenu": "_MENU_ &nbsp;&nbsp;<b>Records Per Page</b>&nbsp;&nbsp;",
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ entries",
				"sInfoFiltered": "(filtered from _MAX_ total entries)", 
				"sZeroRecords": "No matching records found", 
				"sEmptyTable": "No data available in table", 
				"sLoadingRecords": "Please wait - loading...", 
				"oPaginate": {
					"sPrevious": "Prev",
					"sNext": "Next"
				}
			},
			"aaSorting": [[ 1, "asc" ]],
			"columnDefs": [ {
				"targets": 'no-sort',
				"orderable": false,
			}],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 10,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'index.php/'+ active_controller+'/get_data_display',
				type	: "post",
				cache	: false,
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="13">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>
