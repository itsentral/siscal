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
					<th class="text-center">No Invoice</th>
					<th class="text-center">Date</th>
					<th class="text-center">Customer</th>
					<th class="text-center">DPP</th>
					<th class="text-center">PPN</th>
					<th class="text-center">PPH 23</th>
					<th class="text-center">Total Invoice</th>
					<th class="text-center">Total Payment</th>
					<th class="text-center">Piutang</th>
					<th class="text-center">Plan Bayar</th>
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
	var active_controller	= 'Laporan_inv_plan_payment';
	$(document).ready(function(){
		data_display();
		$('#btn-excel').click(function(){
			var Links		= base_url+active_controller+'/excel_laporan_inv_payment';
			//alert(Links);
			window.open(Links,'_blank');
		});
	});
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
				{"data":"invoice_no"},
				{"data":"datet","sClass":"text-center"},
				{"data":"customer_name","sClass":"text-left"},
				{"data":"total_dpp","sClass":"text-right"},				
				{"data":"ppn","sClass":"text-right"},
				{"data":"pph23","sClass":"text-right"},
				{"data":"grand_tot","sClass":"text-right"},
				{"data":"total_payment","sClass":"text-right"},
				{"data":"grand_tot","sClass":"text-right"},
				{"data":"plan_payment","sClass":"text-center"},
			],
			"rowCallback": function(row,data,index,iDisplayIndexFull){
				var total_bayar		= parseFloat(data.total_payment.replace(/\,/g,''));	
				var total_quot		= parseFloat(data.grand_tot.replace(/\,/g,''));				
				var total_piutang	= total_quot - total_bayar;
				
				$('td:eq(8)',row).html(total_piutang.format(0,3,','));
				
			},
			"order": [[1,"desc"]]
		});
		
	}
	
	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>
