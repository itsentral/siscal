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
				<button type="button" class="btn btn-md btn-primary" id="detail-excel"><i class="fa fa-file"></i> Download Detail Alat</button>	
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
					<th class="text-center">Total Certificate</th>
				</tr>
			</thead>

			<tbody id="list_detail">
				<?php
				if($rows_header){
					foreach($rows_header as $key=>$vals){
						echo"<tr>";						
							echo"<td class='text-left'>".$vals->no_so."</td>";
							echo"<td class='text-center'>".date('d M Y',strtotime($vals->tgl_so))."</td>";
							echo"<td class='text-left'>".$vals->customer_name."</td>";
							echo"<td class='text-left'>".$vals->quotation_nomor."</td>";
							echo"<td class='text-center'>".$vals->pono."</td>";
							echo"<td class='text-center'>".$vals->tot_qty."</td>";
						echo"</tr>";
					}
				}
				?>
			</tbody>
			
		</table>
				
	</div>		
</div>

<?php $this->load->view('include/footer'); ?>
<script>
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= 'Laporan_out_bast_certificate';
	$(document).ready(function(){
		$('#my-grid').dataTable();
		$('#btn-excel').click(function(){
			var Links		= base_url+active_controller+'/excel_letter';
			//alert(Links);
			window.open(Links,'_blank');
		});
		$('#detail-excel').click(function(){
			var Links		= base_url+active_controller+'/excel_detail';
			//alert(Links);
			window.open(Links,'_blank');
		});
	});
	
	
	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>
