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
				<label class='label-control col-sm-1'><b>Period <span class='text-red'>*</span></b></label> 
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
						<th class="text-center" rowspan='2'>Quotation</th>
						<th class="text-center" rowspan='2'>Date</th>
						<th class="text-center" rowspan='2'>Customer</th>
						<th class="text-center" rowspan='2'>No SO</th>
						<th class="text-center" rowspan='2'>Tools</th>
						<th class="text-center" rowspan='2'>Range</th>
						<th class="text-center" rowspan='2'>Qty</th>						
						<th class="text-center" rowspan='2'>Subcon Price</th>
						<th class="text-center" colspan='2'>Cust Price</th>
						<th class="text-center" rowspan='2'>Status</th>
					</tr>
					<tr class="bg-blue">
						<th class="text-center">Price</th>
						<th class="text-center">Disc (%)</th>
					</tr>
				</thead>

				<tbody id="list_detail">
				<?php
					if($records){
						foreach($records as $key=>$vals){
							
							$No_SO		='-';
							$Query_SO	="SELECT
												head_so.no_so
											FROM
												letter_orders head_so
											INNER JOIN letter_order_details det_so ON head_so.id = det_so.letter_order_id
											WHERE
												head_so.sts_so NOT IN ('CNC', 'REV')
											AND det_so.quotation_detail_id = '".$vals['id']."'
											GROUP BY
												head_so.id";
							$det_SO		= $this->db->query($Query_SO)->result();
							if($det_SO){
								$No_SO	='';
								foreach($det_SO as $keyS=>$valSO){
									if(!empty($No_SO))$No_SO	.=', ';
									$No_SO	.=$valSO->no_so;
								}
							}
							
							if($vals['status'] === 'OPN'){
								$Kelas		='bg-green';
								$Kets		= 'OPEN';
							}else if($vals['status'] === 'CNC'){
								$Kelas		='bg-yellow';
								$Kets		= 'CANCEL';
							}else if($vals['status'] === 'FAL'){
								$Kelas		='bg-red';
								$Kets		= 'FAIL';
							}else if($vals['status'] === 'REC'){
								$Kelas		='bg-blue';
								$Kets		= 'DEAL';
							}else if($vals['status'] === 'CLS'){
								$Kelas		='bg-gray';
								$Kets		= 'CLOSE';
							}
							
							echo"<tr>";
								echo"<td class='text-center'>".$vals['nomor']."</td>";
								echo"<td class='text-center'>".date('d M Y',strtotime($vals['datet']))."</td>";
								echo"<td class='text-left'>".$vals['customer_name']."</td>";
								echo"<td class='text-center'>".$No_SO."</td>";
								echo"<td class='text-left'>".$vals['cust_tool']."</td>";
								echo"<td class='text-center'>".$vals['range']." ".$vals['piece_id']."</td>";
								echo"<td class='text-right'>".number_format($vals['qty'])."</td>";
								echo"<td class='text-right'>".number_format($vals['hpp'])."</td>";								
								echo"<td class='text-right'>".number_format($vals['price'])."</td>";
								echo"<td class='text-right'>".$vals['discount']."</td>";
								echo"<td class='text-center'><span class='badge ".$Kelas."'>".$Kets."</span></td>";
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
