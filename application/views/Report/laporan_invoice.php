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
			<table id="my-grid" class="table table-bordered table-striped" style="overflow-x:scroll !important;">
				<thead>
					<tr class="bg-blue">
						<th class="text-center" rowspan="2">No Invoice</th>
						<th class="text-center" rowspan="2">Date</th>
						<th class="text-center" rowspan="2">No Faktur</th>
						<th class="text-center" rowspan="2">Customer</th>
						<th class="text-center" rowspan="2">No PO</th>
						<th class="text-center" rowspan="2">No SO</th>
						<th class="text-center" rowspan="2">DPP</th>
						<th class="text-center" rowspan="2">PPN</th>						
						<th class="text-center" colspan="3">BUM</th>
						<th class="text-center" colspan="2">PPN</th>
						<th class="text-center" colspan="2">PPH 23</th>
					</tr>
					<tr class="bg-blue">
						<th class="text-center">Tgl Bayar</th>
						<th class="text-center">Total</th>
						<th class="text-center">Bank</th>
						<th class="text-center">No Reff</th>
						<th class="text-center">Tgl Reff</th>
						<th class="text-center">No Reff</th>
						<th class="text-center">Tgl Reff</th>
					</tr>
				</thead>

				<tbody id="list_detail">
				<?php
					if($rows_data){
						foreach($rows_data as $key=>$vals){
							$No_Inv		= $vals['invoice_no'];
							$Tgl_Bayar	= $Bank	= '-';
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
							$PPH_No		= $PPH_Date = $PPN_No = $PPN_Date = '-';
							
							$Query_PPH	= "SELECT
											det_trans.jurnalid,
											det_trans.no_reff,
											det_trans.tgl_reff
										FROM
											trans_jurnal_details det_ar
										INNER JOIN trans_jurnal_headers det_trans ON det_ar.jurnalid = det_trans.jurnalid
										WHERE
											det_trans.tipe = 'CN'
										AND det_ar.accid = '1030-10-10'
										AND det_ar.no_reff = '".$No_Inv."'
										AND det_ar.kredit > 0 AND det_ar.keterangan LIKE '%pph%'
										GROUP BY
											det_ar.jurnalid";
							$det_PPH		= $this->db->query($Query_PPH)->result();
							if($det_PPH){
								$intI	= 0;
								$PPH_No		= $det_PPH[0]->no_reff;
								$PPH_Date	= $det_PPH[0]->tgl_reff;
								
							}
							
							$Query_PPN	= "SELECT
											det_trans.jurnalid,
											det_trans.no_reff,
											det_trans.tgl_reff
										FROM
											trans_jurnal_details det_ar
										INNER JOIN trans_jurnal_headers det_trans ON det_ar.jurnalid = det_trans.jurnalid
										WHERE
											det_trans.tipe = 'CN'
										AND det_ar.accid = '1030-10-10'
										AND det_ar.no_reff = '".$No_Inv."'
										AND det_ar.kredit > 0 AND det_ar.keterangan LIKE '%ppn%'
										GROUP BY
											det_ar.jurnalid";
							$det_PPN		= $this->db->query($Query_PPN)->result();
							if($det_PPN){
								$intI	= 0;
								$PPN_No		= $det_PPN[0]->no_reff;
								$PPN_Date	= $det_PPN[0]->tgl_reff;
								
							}
							
							echo"<tr>";
								echo"<td class='text-center'>".$No_Inv."</td>";
								echo"<td class='text-center'>".date('d M Y',strtotime($vals['datet']))."</td>";
								echo"<td class='text-center'>".$vals['no_faktur']."</td>";
								echo"<td class='text-left'>".$vals['customer_name']."</td>";
								echo"<td class='text-left'>".$vals['pono']."</td>";
								echo"<td class='text-left'>".$vals['no_so']."</td>";
								echo"<td class='text-right'>".number_format($vals['total_dpp'])."</td>";
								echo"<td class='text-right'>".number_format($vals['ppn'])."</td>";								
								echo"<td class='text-center'>".$Tgl_Bayar."</td>";
								echo"<td class='text-right'>".number_format($Jum_Bayar)."</td>";
								echo"<td class='text-left'>".$Bank."</td>";
								echo"<td class='text-center'>".$PPN_No."</td>";
								echo"<td class='text-center'>".$PPN_Date."</td>";
								echo"<td class='text-center'>".$PPH_No."</td>";
								echo"<td class='text-center'>".$PPH_Date."</td>";
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
