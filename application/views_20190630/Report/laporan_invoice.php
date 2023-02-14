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
						echo form_button(array('type'=>'button','class'=>'btn btn-sm btn-danger','value'=>'download Excel','content'=>'Download Excel','id'=>'btn-excel'));
					?>							
				</div>
			</div>
			<table id="my-grid" class="table table-bordered table-striped" style="overflow-x:scroll !important;">
				<thead>
					<tr class="bg-blue">
						<th class="text-center" rowspan="2">No Invoice</th>
						<th class="text-center" rowspan="2">Date</th>
						<th class="text-center" rowspan="2">Customer</th>
						<th class="text-center" rowspan="2">Total Invoice</th>
						<th class="text-center" rowspan="2">No Faktur</th>
						<th class="text-center" rowspan="2">No SO</th>
						<th class="text-center" colspan="3">BUM</th>
						<th class="text-center" colspan="2">PPH 23/PPN</th>
					</tr>
					<tr class="bg-blue">
						<th class="text-center">Tgl Bayar</th>
						<th class="text-center">Total</th>
						<th class="text-center">Bank</th>
						<th class="text-center">No Reff</th>
						<th class="text-center">No Reff</th>
					</tr>
				</thead>

				<tbody id="list_detail">
				<?php
					if($rows_data){
						foreach($rows_data as $key=>$vals){
							$No_Inv		= $vals['invoice_no'];
							$Tgl_Bayar	= $Bank	= $Reff1 = $Reff2 = '-';
							$Jum_Bayar	= 0;
							## BUM ##
							$Query_Bum	= "SELECT (det_ar.kredit - det_ar.debet) as total_bayar,det_ar.tgl_jurnal, det_trans.accid FROM trans_ar_jurnals det_ar INNER JOIN trans_jurnal_headers det_trans ON det_ar.jurnalid=det_trans.jurnalid WHERE det_trans.tipe='BUM' AND det_trans.sts_batal='N' AND det_ar.invoice_no='$No_Inv'";
							$det_Bum	= $this->db->query($Query_Bum)->result();
							if($det_Bum){
								foreach($det_Bum as $ky=>$values){
									$Tot_Bayar	= $values->total_bayar;
									$Tgl_Bayar	= $values->tgl_jurnal;
									$Coa_Bayar	= $values->accid;
									
									$Jum_Bayar	+=$Tot_Bayar;
									
									## COA BANK ##
									if($Coa_Bayar !='-' && $Coa_Bayar !=''){
										$Query_Bank	= "SELECT CONCAT(bank,' ',norek) as nama_bank FROM coa_masters WHERE accid='$Coa_Bayar'";
										$det_Bank	= $this->db->query($Query_Bank)->result();
										if($det_Bank){
											$Bank	= $det_Bank[0]->nama_bank;
										}
									}
								}
							}
							
							## CN ##
							$Query_CN	= "SELECT det_trans.no_reff FROM trans_ar_jurnals det_ar INNER JOIN trans_jurnal_headers det_trans ON det_ar.jurnalid=det_trans.jurnalid WHERE det_trans.tipe='CN' AND det_trans.sts_batal='N' AND det_ar.invoice_no='$No_Inv' ORDER BY det_trans.tgl_jurnal DESC LIMIT 2";
							$det_CN		= $this->db->query($Query_CN)->result();
							if($det_CN){
								$intI	= 0;
								foreach($det_CN as $ks=>$values){
									$intI++;
									if($intI==1){
										$Reff1 	= $values->no_reff;
									}else{
										$Reff2 	= $values->no_reff;
									}
								}
							}
							
							echo"<tr>";
								echo"<td class='text-center'>".$No_Inv."</td>";
								echo"<td class='text-center'>".date('d M Y',strtotime($vals['datet']))."</td>";
								echo"<td class='text-left'>".$vals['customer_name']."</td>";
								echo"<td class='text-right'>".number_format($vals['grand_tot'])."</td>";
								echo"<td class='text-center'>".$vals['no_faktur']."</td>";
								echo"<td class='text-left'>".$vals['no_so']."</td>";
								echo"<td class='text-center'>".$Tgl_Bayar."</td>";
								echo"<td class='text-right'>".number_format($Jum_Bayar)."</td>";
								echo"<td class='text-left'>".$Bank."</td>";
								echo"<td class='text-center'>".$Reff1."</td>";
								echo"<td class='text-center'>".$Reff2."</td>";
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
	var active_controller	= 'Laporan_invoice';
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
			var Links		= base_url+active_controller+'/excel_laporan_invoice/'+tgl_awal+'/'+tgl_awkhir;
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
