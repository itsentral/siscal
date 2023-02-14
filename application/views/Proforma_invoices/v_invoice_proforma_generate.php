<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-envelope"></i> <?php echo('<span class="important">'.$title.'</span>'); ?>
			</h3>
			
		</div>
		<div class="box-body">
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Nomor Invoice</label>
						<?php
							echo form_input(array('id'=>'nomor_inv','name'=>'nomor_inv','class'=>'form-control input-sm','disabled'=>true),'AUTOMATIC');						
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tanggal Invoice</label>
						<?php
							echo form_input(array('id'=>'tgl_invoice','name'=>'tgl_invoice','class'=>'form-control input-sm','readOnly'=>true),date('Y-m-d'));						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Customer</label>
						<?php
							echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_cust[0]->name);
							echo form_input(array('id'=>'customer_id','name'=>'customer_id','type'=>'hidden'),$rows_cust[0]->id);
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Alamat</label>
						<?php
							echo form_textarea(array('id'=>'address', 'name'=>'address','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm'),$rows_cust[0]->npwp_address);						
						?>
					</div>
				</div>				
			</div>						
		</div>		
	</div>
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-refresh"></i> <?php echo('<span class="important">Data Detail</span>'); ?>
			</h3>			
		</div>
		<div class="box-body">
			<table class="table table-striped table-bordered" id="my-grid">
				<thead>
					<tr class="bg-blue">
						<th class='text-center'><b>Nama Alat</b></th>
						<th class='text-center'><b>No PO</b></th>
						<th class='text-center'><b>No SO</b></th>
						<th class='text-center'><b>Harga</b></th>
						<th class='text-center'><b>Qty</b></th>
						<th class='text-center'><b>DPP</b></th>
						<th class='text-center'><b>Diskon / Qty</b></th>
						<th class='text-center'><b>Total</b></th>
						<th class='text-center'><b>Aksi</b></th>
					</tr>
				</thead>
				<tbody id='list_item'>
					<?php
					$Flag_Insitu	= $Arr_Quot = array();
					if($rows_detail){
						$total_dpp		= 0;
						
						$intG			= 0;
						$Flg_PPN		= 0;
						foreach($rows_detail as $ketD=>$valD){
							$intG++;
							$Quot_Id			= $valD->quotation_id;
							$Letter_Id			= $valD->letter_order_id;
							$Det_Id				= $valD->quotation_detail_id;
							$Arr_Quot[$Quot_Id]	= $Letter_Id;
							if($valD->flag_so_insitu == 'Y'){
								$Flag_Insitu[$Quot_Id]	=$Letter_Id;
							}
							if($valD->exc_ppn == 'N'){
								$Flg_PPN=1;
							}
							
							$Qty_Proses		= $valD->qty;
							$harga_barang	= $valD->price * $Qty_Proses;
							$harga_discount	= round(($valD->discount * $harga_barang )/ 100);
							$harga_after	= $harga_barang - $harga_discount;
							$total_dpp		+= $harga_after;
							
							
							echo"<tr id='tr_".$intG."'>";
								echo"<input type='hidden' name='InvoiceDetail[".$intG."][tool_id]'  id='tool_id_".$intG."' value='".$valD->tool_id."'>";
								echo"<input type='hidden' name='InvoiceDetail[".$intG."][range]'  id='range_".$intG."' value='".$valD->range."'>";
								echo"<input type='hidden' name='InvoiceDetail[".$intG."][price]'  id='price_".$intG."' value='".$valD->price."'>";
								echo"<input type='hidden' name='InvoiceDetail[".$intG."][hpp]'  id='hpp_".$intG."' value='".$valD->hpp."'>";
								echo"<input type='hidden' name='InvoiceDetail[".$intG."][piece_id]'  id='piece_id_".$intG."' value='".$valD->piece_id."'>";
								echo"<input type='hidden' name='InvoiceDetail[".$intG."][tipe]'  id='tipe_".$intG."' value='T'>";
								echo"<input type='hidden' name='InvoiceDetail[".$intG."][detail_id]'  id='detail_id_".$intG."' value='".$valD->quotation_detail_id."'>";
								echo"<input type='hidden' name='InvoiceDetail[".$intG."][discount]'  id='discount_".$intG."' value='".$valD->discount."'>";
								echo"<input type='hidden' name='InvoiceDetail[".$intG."][quotation_id]'  id='quotation_id_".$intG."' value='".$valD->quotation_id."'>";
								echo"<input type='hidden' name='InvoiceDetail[".$intG."][letter_order_id]'  id='letter_order_id".$intG."' value='".$valD->letter_order_id."'>";
								
								
								echo"<td class='text-left'>".form_input(array('id'=>'tool_name_'.$intG,'name'=>'InvoiceDetail['.$intG.'][tool_name]','class'=>'form-control input-sm','readOnly'=>true),$valD->tool_name)."</td>";
								echo"<td class='text-center'>".$valD->pono."</td>";
								echo"<td class='text-center'>".$valD->no_so."</td>";
								echo"<td class='text-right'>".number_format($valD->price)."</td>";
								echo"<td class='text-center'>".form_input(array('id'=>'qty_'.$intG,'name'=>'InvoiceDetail['.$intG.'][qty]','class'=>'form-control input-sm','readOnly'=>true,'size'=>'5px'),$valD->qty)."</td>";
								echo"<td class='text-right'>".form_input(array('id'=>'total_harga_'.$intG,'name'=>'InvoiceDetail['.$intG.'][total_harga]','class'=>'form-control input-sm','readOnly'=>true,'size'=>'25px'),number_format($harga_barang))."</td>";
								echo"<td class='text-center'>".number_format($valD->discount)."</td>";
								echo"<td class='text-right'>";
									echo form_input(array('id'=>'total_'.$intG,'name'=>'InvoiceDetail['.$intG.'][total]','class'=>'form-control input-sm','readOnly'=>true,'size'=>'25px'),number_format($harga_after));
								echo"</td>";
								echo"<td class='text-center'>-</td>";
							echo"</tr>";
						}
					}
					//echo"<pre>";print_r($Flag_Insitu);
					//echo"<pre>";print_r($Arr_Quot);
					if(!empty($Flag_Insitu)){
						
						foreach($Flag_Insitu as $keyI=>$valI){
							$Query_Insitu	= "SELECT
													delv_quotation.*, head_quotation.nomor AS quotation_nomor,
													head_quotation.datet AS quotation_date,
													head_quotation.pono,
													head_quotation.podate
												FROM
													quotation_deliveries delv_quotation
												INNER JOIN quotations head_quotation ON delv_quotation.quotation_id=head_quotation.id
												WHERE
													delv_quotation.quotation_id = '".$keyI."'
												AND (
													delv_quotation.`day` - delv_quotation.pros_proforma
												) > 0";
							//echo $Query_Insitu;exit;
							
							$Result_Insitu	= $this->db->query($Query_Insitu)->result();
							if($Result_Insitu){
								foreach($Result_Insitu as $keyRestI=>$valRestI){
									$intG++;
									$Qty_Proses		= $valRestI->day - $valRestI->pros_proforma;
									$Diskon_Ins		= round($valRestI->diskon / $valRestI->day);
									$harga_barang	= $valRestI->fee;										
									$harga_after	= $harga_barang * $Qty_Proses;									
									$total_dpp		+= ($harga_after - ($Qty_Proses * $Diskon_Ins));
									
									echo"<tr id='tr_".$intG."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][tool_id]'  id='tool_id_".$intG."' value='".$valRestI->delivery_id."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][range]'  id='range_".$intG."' value='-'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][price]'  id='price_".$intG."' value='".$harga_barang."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][hpp]'  id='hpp_".$intG."' value='".$harga_barang."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][piece_id]'  id='piece_id_".$intG."' value='-'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][tipe]'  id='tipe_".$intG."' value='I'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][detail_id]'  id='detail_id_".$intG."' value='".$valRestI->id."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][discount]'  id='discount_".$intG."' value='".$Diskon_Ins."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][quotation_id]'  id='quotation_id_".$intG."' value='".$keyI."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][letter_order_id]'  id='letter_order_id".$intG."' value='".$valI."'>";
										
										
										echo"<td class='text-left'>".form_input(array('id'=>'tool_name_'.$intG,'name'=>'InvoiceDetail['.$intG.'][tool_name]','class'=>'form-control input-sm','readOnly'=>true),$valRestI->delivery_name)."</td>";
										echo"<td class='text-center'>".$valD->pono."</td>";
										echo"<td class='text-center'>-</td>";
										echo"<td class='text-right'>".number_format($harga_barang)."</td>";
										echo"<td class='text-center'>".form_input(array('id'=>'qty_'.$intG,'name'=>'InvoiceDetail['.$intG.'][qty]','class'=>'form-control input-sm qty_insitu','readOnly'=>true,'size'=>'5px'),$Qty_Proses)."</td>";
										echo"<td class='text-right'>".form_input(array('id'=>'total_harga_'.$intG,'name'=>'InvoiceDetail['.$intG.'][total_harga]','class'=>'form-control input-sm','readOnly'=>true,'size'=>'25px'),number_format($harga_after))."</td>";
										echo"<td class='text-center'>".number_format($Diskon_Ins)."</td>";
										echo"<td class='text-right'>";
											echo form_input(array('id'=>'total_'.$intG,'name'=>'InvoiceDetail['.$intG.'][total]','class'=>'form-control input-sm','readOnly'=>true,'size'=>'25px'),number_format($harga_after - ($Qty_Proses * $Diskon_Ins)));
										echo"</td>";
										echo"<td class='text-center'><button type='button' class='btn btn-sm btn-danger' title='Hapus Data' data-role='qtip' onClick='return DelItem(".$intG.");'><i class='fa fa-trash-o'></i></button></td>";
									echo"</tr>";
								}
							}
							
						}
						
					}
					if(!empty($Arr_Quot)){
						$Imp_Quot	= implode_data($Arr_Quot);
						$Query_Acc	= "SELECT
											delv_quotation.*, head_quotation.nomor AS quotation_nomor,
											head_quotation.datet AS quotation_date,
											head_quotation.pono,
											head_quotation.podate
										FROM
											quotation_accommodations delv_quotation
										INNER JOIN quotations head_quotation ON delv_quotation.quotation_id=head_quotation.id
										WHERE
											delv_quotation.quotation_id IN('".$Imp_Quot."')
										AND (
											delv_quotation.pros_proforma = 'N'
											OR delv_quotation.pros_proforma IS NULL
											OR delv_quotation.pros_proforma = ''
										)";
						//echo"<pre>";print_r($Query_Acc);
						$Result_Acc	= $this->db->query($Query_Acc)->result();
						
						if($Result_Acc){
							foreach($Result_Acc as $keyA=>$valA){
								$intG++;
								$Qty_Proses		= 1;
								$Diskon_Ins		= round($valA->diskon);
								$harga_barang	= $valA->nilai;										
								$harga_after	= $harga_barang * $Qty_Proses;
								$total_dpp		+= $valA->total;
								$Code_Quot		= $valA->quotation_id;
								$Code_Letter	= $Arr_Quot[$Code_Quot];
								
								echo"<tr id='tr_".$intG."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][tool_id]'  id='tool_id_".$intG."' value='".$valA->accommodation_id."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][range]'  id='range_".$intG."' value='-'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][price]'  id='price_".$intG."' value='".$valA->nilai."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][hpp]'  id='hpp_".$intG."' value='".$valA->nilai."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][piece_id]'  id='piece_id_".$intG."' value='-'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][tipe]'  id='tipe_".$intG."' value='A'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][detail_id]'  id='detail_id_".$intG."' value='".$valA->id."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][discount]'  id='discount_".$intG."' value='".$Diskon_Ins."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][quotation_id]'  id='quotation_id_".$intG."' value='".$valA->quotation_id."'>";
										echo"<input type='hidden' name='InvoiceDetail[".$intG."][letter_order_id]'  id='letter_order_id".$intG."' value='".$Code_Letter."'>";
										
										
										echo"<td class='text-left'>".form_input(array('id'=>'tool_name_'.$intG,'name'=>'InvoiceDetail['.$intG.'][tool_name]','class'=>'form-control input-sm','readOnly'=>true),$valA->accommodation_name)."</td>";
										echo"<td class='text-center'>".$valA->pono."</td>";
										echo"<td class='text-center'>-</td>";
										echo"<td class='text-right'>".number_format($valA->nilai)."</td>";
										echo"<td class='text-center'>".form_input(array('id'=>'qty_'.$intG,'name'=>'InvoiceDetail['.$intG.'][qty]','class'=>'form-control input-sm','readOnly'=>true,'size'=>'5px'),$Qty_Proses)."</td>";
										echo"<td class='text-right'>".form_input(array('id'=>'total_harga_'.$intG,'name'=>'InvoiceDetail['.$intG.'][total_harga]','class'=>'form-control input-sm','readOnly'=>true,'size'=>'25px'),number_format($harga_after))."</td>";
										echo"<td class='text-center'>".number_format($Diskon_Ins)."</td>";
										echo"<td class='text-right'>";
											echo form_input(array('id'=>'total_'.$intG,'name'=>'InvoiceDetail['.$intG.'][total]','class'=>'form-control input-sm','readOnly'=>true,'size'=>'25px'),number_format($valA->total));
										echo"</td>";
										echo"<td class='text-center'><button type='button' class='btn btn-sm btn-danger' title='Hapus Data' data-role='qtip' onClick='return DelItem(".$intG.");'><i class='fa fa-trash-o'></i></button></td>";
									echo"</tr>";
							}
						}
						
					}
					
				echo"</tbody>";
				
				?>
				<tfoot class='bg-gray'>
					<tr>
						<td class='text-right' colspan='7'><b>Sub Total</b></td>
						<td class='text-right' colspan ='2'>
							<?php 
							echo form_input(array('id'=>'dpp','name'=>'dpp','class'=>'form-control input-sm','readOnly'=>true),number_format($total_dpp));
							
							?>
						</td>
						
					</tr>
					<?php 
					
					
					$ppn		= 0;
					$Exc_PPN	= 'Y';
					if($Flg_PPN==1){
						$ppn		= round($total_dpp * 0.1);
						$Exc_PPN	= 'N';
					}
					$grand_tot	= $total_dpp + $ppn;
					
					?>
					<tr>
						<td class='text-right' colspan='7'><b>PPN</b></td>
						<td class='text-right' colspan ='2'>
							<?php 
							echo form_input(array('id'=>'ppn','name'=>'ppn','class'=>'form-control input-sm','readOnly'=>true),number_format($ppn));
							echo form_input(array('id'=>'exc_ppn','name'=>'exc_ppn','type'=>'hidden'),$Exc_PPN);
							echo form_input(array('id'=>'prosen_ppn','name'=>'prosen_ppn','type'=>'hidden'),10);
							?>
						</td>
						
					</tr>
					<tr>
						<td class='text-right' colspan='7'><b>Total</b></td>
						<td class='text-right' colspan ='2'>
							<?php 
							echo form_input(array('id'=>'grand_tot','name'=>'grand_tot','class'=>'form-control input-sm','readOnly'=>true),number_format($grand_tot));
							
							?>
						</td>
						
					</tr>
					
				</tfoot>
			</table>
		</div>
		<div class="box-footer">
			<?php
				echo"<button type='button' class='btn btn-md btn-danger' id='btn-back'> <i class='fa fa-angle-double-left'></i> BACK </button>&nbsp;&nbsp;&nbsp;";	
				echo"<button type='button' class='btn btn-md btn-success' id='btn-save'>SAVE PROCESS <i class='fa fa-refresh'></i>  </button>";	
				
			?>
		</div>
	</div>
</form>

<?php $this->load->view('include/footer'); ?>
<style>
	
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
	const GetProsenPPN = ()=>{
		let InvoiceDate	= $('#tgl_invoice').val();

		$.post(base_url+'/'+active_controller+'/GetMasterPPN',{'invoice_date':InvoiceDate}, function(response) {
		   const result = $.parseJSON(response);
		   $('#prosen_ppn').val(result.ppn);
		   CalcALL();
		});
	}
	
	$(document).ready(function(){
		GetProsenPPN();
		$('.harga').maskMoney();
		$("#tgl_invoice").datepicker({
			dateFormat	: 'yy-mm-dd',
			changeMonth	: true,
			changeYear	: true,
			maxDate		: "+0d",
			minDate		: '-1m',
			onClose: function () {
				GetProsenPPN();
			}
		});
		$('#btn-back').click(function(){			
			loading_spinner();
			window.location.href =  base_url+'/'+active_controller+'/outstanding_invoice';
		});
		
	});
	
	
	$(document).on('click','#btn-save',function(e){
		e.preventDefault();
		$('#btn-save, #btn-back').prop('disabled',true);
		swal({
			  title: "Are you sure?",
			  text: "You will not be able to process again this data!",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonClass: "btn-danger",
			  confirmButtonText: "Yes, Process it!",
			  cancelButtonText: "No, cancel process!",
			  closeOnConfirm: true,
			  closeOnCancel: false
			},
			function(isConfirm) {					
				if (isConfirm) {
					loading_spinner_new();
					var formData 	= new FormData($('#form-proses')[0]);
					var baseurl		= base_url +'/'+ active_controller+'/save_generate_invoice';
					$.ajax({
						url			: baseurl,
						type		: "POST",
						data		: formData,
						cache		: false,
						dataType	: 'json',
						processData	: false, 
						contentType	: false,				
						success		: function(data){
							close_spinner_new();
							if(data.status == 1){											
								swal({
									  title	: "Save Success!",
									  text	: data.pesan,
									  type	: "success"
									});
								window.location.href = base_url +'/'+ active_controller;
							}else{								
								swal({
								  title	: "Save Failed!",
								  text	: data.pesan,
								  type	: "warning"
								});									
								//alert(data.pesan);
								$('#btn-save, #btn-back').prop('disabled',false);
								return false;
								
							}
						},
						error: function() {
							close_spinner_new();
							swal({
							  title				: "Error Message !",
							  text				: 'An Error Occured During Process. Please try again..',						
							  type				: "warning"
							});
							$('#btn-save, #btn-back').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-save, #btn-back').prop('disabled',false);
					return false;
				}
		});		
	});
	
	function CalcALL(){
		let sub_tot		=0;
		
		let grand_tot	=0;
		let ket_ppn		= $('#exc_ppn').val();
		let Prosen_PPN	= $('#prosen_ppn').val();
		let dpp			= $('#dpp').val().replace(/\,/g,'');
		
		
		
		let ppn			=0;
		if(ket_ppn=='N'){
			ppn		= Math.floor(parseFloat(dpp) * parseFloat(Prosen_PPN) / 100);
		}
		grand_tot		= parseFloat(dpp) + parseFloat(ppn);
		
		
		$('#ppn').val(ppn.format(0,3,','));
		$('#grand_tot').val(grand_tot.format(0,3,','));
	}

	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>
