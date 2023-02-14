<?php
$this->load->view('include/side_menu'); 

?> 
<form action="<?php echo site_url(strtolower($this->uri->segment(1).'/'.$action))?>" method="POST" id="form-proses">
	<div class="box box-warning">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-money"></i> <?php echo('<span class="important">'.$title.'</span>'); ?>
			</h3>
			
		</div>
		<div class="box-body">
			<div class='form-group row'>			
				<label class='label-control col-sm-1'><b>Periode Bukti Potong <span class='text-red'>*</span></b></label> 
				<div class='col-sm-2'>
					<?php
						echo form_input(array('id'=>'periode_awal','name'=>'periode_awal','class'=>'form-control input-sm','readOnly'=>true,'data-role'=>'datepicker'),$periode_awal);
					?>							
				</div>
				<div class='col-sm-2'>
					<?php
						echo form_input(array('id'=>'periode_akhir','name'=>'periode_akhir','class'=>'form-control input-sm','readOnly'=>true,'data-role'=>'datepicker'),$periode_akhir);
					?>							
				</div>
				<div class='col-sm-2'>
					<?php
						echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-primary','value'=>'save','content'=>'Preview','id'=>'btn-preview')).' ';
						echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-danger','value'=>'download Excel','content'=>'Download Excel','id'=>'btn-excel'));
					?>							
				</div>
			</div>
			<table id="my-grid" class="table table-bordered table-striped" style="overflow-x:scroll !important;">
				<thead>
					<tr class="bg-blue">
						<th class="text-center">No Invoice</th>
						<th class="text-center">Tanggal</th>
						<th class="text-center">Customer</th>
						<th class="text-center">DPP</th>
						<th class="text-center">PPH</th>
						<th class="text-center">No Bukti Potong</th>
						<th class="text-center">Tgl Bukti Potong</th>						
						<th class="text-center">No Jurnal</th>
						<th class="text-center">Tgl Jurnal</th>
						<th class="text-center">Nil Jurnal</th>
					</tr>
					
				</thead>

				<tbody id="list_detail">
				<?php
					if($rows_data){
						foreach($rows_data as $key=>$vals){
							
							echo"<tr>";
								echo"<td class='text-center'>".$vals['no_reff']."</td>";
								echo"<td class='text-center'>".date('d M Y',strtotime($vals['inv_date']))."</td>";
								echo"<td class='text-left'>".$vals['customer']."</td>";
								echo"<td class='text-right'>".number_format($vals['dpp'])."</td>";
								echo"<td class='text-right'>".number_format($vals['pph'])."</td>";								
								echo"<td class='text-center'>".$vals['no_bukti_potong']."</td>";
								echo"<td class='text-center'>".date('d M Y',strtotime($vals['tgl_bukti_potong']))."</td>";
								echo"<td class='text-center'>".$vals['jurnalid']."</td>";
								echo"<td class='text-center'>".date('d M Y',strtotime($vals['tgl_jurnal']))."</td>";
								echo"<td class='text-right'>".number_format($vals['kredit'])."</td>";
							echo"</tr>";
							
						}
					}				
				?>
				</tbody>
				
			</table>
					
		</div>		
	</div>
</form>
<?php $this->load->view('include/footer'); ?>
<script>
	var base_url			= '<?php echo base_url(); ?>';
	$(document).ready(function(){
		$('#my-grid').dataTable();
		
		
		$('#btn-preview').click(function(){
			var tgl_awal	= $('#periode_awal').val();
			var tgl_awkhir	= $('#periode_akhir').val();
			if(tgl_awal > tgl_awkhir){
				swal({
				  title	: "Error Message!",
				  text	: 'Incorrect period Please input correct period.....',
				  type	: "warning"
				});
				return false;
			}
			loading_spinner_new();
			$('#form-proses').submit();
		});
		$('#btn-excel').click(function(){
			var tgl_awal	= $('#periode_awal').val();
			var tgl_awkhir	= $('#periode_akhir').val();
			if(tgl_awal > tgl_awkhir){
				swal({
				  title	: "Error Message!",
				  text	: 'Incorrect period Please input correct period.....',
				  type	: "warning"
				});
				return false;
			}
			var Links		= base_url+active_controller+'/excel_laporan/'+tgl_awal+'/'+tgl_awkhir;
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
