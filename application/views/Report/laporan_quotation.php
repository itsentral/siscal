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
				<label class='label-control col-sm-1'><b>Periode <span class='text-red'>*</span></b></label> 
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
						if($akses_menu['download']=='1'){
							echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-danger','value'=>'download Excel','content'=>'Download Excel','id'=>'btn-excel'));
						}
					?>							
				</div>
			</div>
			<?php
			echo"<table class='table table-bordered table-striped' id='my-grid'>";
				echo"<thead>";
					echo"<tr class='bg-blue'>";
						echo"<td class='text-center'>Quotation No</td>";
						echo"<td class='text-center'>Quotation Date</td>";
						echo"<td class='text-center'>Customer</td>";
						echo"<td class='text-center'>Quotation Val</td>";
						echo"<td class='text-center'>Insitu</td>";
						echo"<td class='text-center'>Akomodasi</td>";
						echo"<td class='text-center'>Subcon</td>";
						echo"<td class='text-center'>Cust Fee</td>";
						echo"<td class='text-center'>Nett</td>";
						echo"<td class='text-center'>Sales</td>";
						echo"<td class='text-center'>Status</td>";
						echo"<td class='text-center'>Descr</td>";
						echo"<td class='text-center'>Reff By</td>";
					echo"</tr>";
				echo"</thead>";
				echo"<tbody>";
					if($records){
						foreach($records as $key=>$vals){
							$nilai_dpp		= $vals['grand_tot'] - $vals['ppn'];
							$nilai_Bersih	= $nilai_dpp - $vals['total_subcon'] - $vals['total_insitu'] - $vals['total_akomodasi'] - $vals['customer_fee'];
							$sts_quot		= $vals['status'];
							if($sts_quot=='OPN'){
								$status="<span class='badge bg-green'>OPEN</span>";
							}else if($sts_quot=='CNC'){
								$status="<span class='badge bg-yellow'>CANCEL</span>";
							}else if($sts_quot=='FAL'){
								$status="<span class='badge bg-red'>FAILED</span>";
							}if($sts_quot=='CLS'){
								$status="<span class='badge bg-puple'>CLOSE</span>";
							}else if($sts_quot=='REC'){
								$status="<span class='badge bg-maroon'>RECEIVE PO</span>";
							}
							
							echo"<tr>";
								echo"<td align='left'>".$vals['nomor']."</td>";
								echo"<td align='left'>".date('d M Y',strtotime($vals['datet']))."</td>";
								echo"<td align='left'>".$vals['customer_name']."</td>";
								echo"<td align='right'>".number_format($nilai_dpp)."</td>";
								echo"<td align='right'>".number_format($vals['total_insitu'])."</td>";
								echo"<td align='right'>".number_format($vals['total_akomodasi'])."</td>";
								echo"<td align='right'>".number_format($vals['total_subcon'])."</td>";
								echo"<td align='right'>".number_format($vals['customer_fee'])."</td>";
								echo"<td align='right'>".number_format($nilai_Bersih)."</td>";
								echo"<td align='left'>".$vals['member_name']."</td>";
								echo"<td class='text-center'>".$status."</td>";
								echo"<td align='left'>".$vals['reason']."</td>";
								echo"<td class='text-center'>".$vals['reference_by']."</td>";
							echo"</tr>";
						}
					}
				echo"</tbody>";
			echo"</table>";
		?>	
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
			var Links		= base_url+'index.php/'+active_controller+'/excel_laporan/'+tgl_awal+'/'+tgl_awkhir;
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
