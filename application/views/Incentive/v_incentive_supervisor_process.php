<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form_generate_cpr" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5><?php echo $title;?></h5>
				</div>
				
			</div>
		
			<div class='row'>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>CPR NO <span class='text-red'>*</span></b></label>
						<div>
							<span class="badge bg-green-active">AUTOMATIC</span>
						</div>
						
					</div>
				</div>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>CPR Date <span class='text-red'>*</span></b></label>
						<?php
							echo form_input(array('id'=>'datet','name'=>'datet','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y'));						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>Supervisor <span class='text-red'>*</span></b></label>
						<?php
							echo form_input(array('id'=>'member_name','name'=>'member_name','class'=>'form-control input-sm','readOnly'=>true),$rows_teknisi->nama);	
							echo form_input(array('id'=>'member_id','name'=>'member_id','type'=>'hidden'),$rows_teknisi->id);							
							
						?>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>Description <span class='text-red'>*</span></b></label>
						<?php
							echo form_textarea(array('id'=>'descr','name'=>'descr','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75, 'rows'=>2),'Pembayaran Insentif Penyelia atas '.$rows_teknisi->nama);						
						?>
					</div>
				</div>				
			</div>
						
			
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL ITEM </h5>
				</div>
				
			</div>
			<div class="row">
				<div class="col-sm-12" style="overflow-x:scroll !important;">
					<table class="table table-striped table-bordered" id="my-grid">
						<thead>
							<tr class="bg-navy-active">
								<th class="text-center">Quotation</th>
								<th class="text-center">SO No</th>
								<th class="text-center">Customer</th>
								<th class="text-center">Code Tool</th>
								<th class="text-center">Name Tool</th>												
								<th class="text-center">Price</th>
								<th class="text-center">Qty</th>
								<th class="text-center">Discount<br>(%)</th>
								<th class="text-center">Total</th>
								<th class="text-center">Incentive<br>(%)</th>
								<th class="text-center">Total<br>Incentive</th>
								<th class="text-center">Dimention</th>
							</tr>
						</thead>
						<tbody id="list_detail">
							<?php
							
							$intL			= $Sub_Total = 0;
							if($rows_detail){
								foreach($rows_detail as $key=>$row){
									$intL++;
									
									$Code_Quot		= $row['quotation_id'];
									$Nomor_Quot		= $row['quotation_nomor'];
									$Code_Letter	= $row['letter_order_id'];
									$Nomor_Letter	= $row['no_so'];
									$Code_Sched		= $row['schedule_detail_id'];
									$Nomor_Sched	= $row['schedule_nomor'];
									$Code_Cust		= $row['customer_id'];
									$Name_Cust		= $row['customer_name'];
									$Code_Tool		= $row['tool_id'];
									$Name_Tool		= $row['tool_name'];
									$Code_Tech		= $row['supervisor_id'];
									$Name_Tech		= $row['supervisor_name'];
									$Qty_Tool		= $row['qty'];
									$HPP_Tool		= $row['hpp'];
									$Price_Tool		= $row['price'];
									$Disc_Tool		= ($row['diskon'] > 0)?$row['diskon']:0;
									$Code_Invoice	= $row['invoice_no'];
									$Nett_Tool		= round($Qty_Tool * $Price_Tool * (100 - $Disc_Tool) / 100);
									
									$Prosen_Inc	 	= 1;
									$Nil_Inc		= round($Nett_Tool * $Prosen_Inc / 100);
									$Sub_Total		+=$Nil_Inc;
									
									$Lingkup 		= $Instrument = '- ';
									$Query_Tool	= "SELECT
														head_tool.id,
														head_tool.`name` AS tool_name,
														head_ins.`name` AS instrument,
														head_dim.`name` AS dimention
													FROM
														tools head_tool
													INNER JOIN instruments head_ins ON head_tool.instrument_id = head_ins.id
													INNER JOIN dimentions head_dim ON head_tool.dimention_id = head_dim.id
													WHERE
														head_tool.id = '".$Code_Tool."'";
									$rows_Tool	= $this->db->query($Query_Tool)->row();
									if($rows_Tool){
										$Lingkup	= $rows_Tool->dimention;
										$Instrument	= $rows_Tool->instrument;
									}
									
									echo"<tr id='tr_row_".$intL."'>";
										echo'<input type="hidden" name="detDetail['.$intL.'][customer_id]" id="customer_id_'.$intL.'" value="'.$Code_Cust.'">';										
										echo'<input type="hidden" name="detDetail['.$intL.'][customer_name]" id="customer_name_'.$intL.'" value="'.$Name_Cust.'">';
										echo'<input type="hidden" name="detDetail['.$intL.'][invoice_no]" id="invoice_no_'.$intL.'" value="'.$Code_Invoice.'">';
										echo'<input type="hidden" name="detDetail['.$intL.'][quotation_nomor]" id="quotation_nomor_'.$intL.'" value="'.$Nomor_Quot.'">';
										echo'<input type="hidden" name="detDetail['.$intL.'][quotation_id]" id="quotation_id_'.$intL.'" value="'.$Code_Quot.'">';
										echo'<input type="hidden" name="detDetail['.$intL.'][no_so]" id="no_so_'.$intL.'" value="'.$Nomor_Letter.'">';
										echo'<input type="hidden" name="detDetail['.$intL.'][letter_order_id]" id="letter_order_id_'.$intL.'" value="'.$Code_Letter.'">';
										echo'<input type="hidden" name="detDetail['.$intL.'][tool_id]" id="tool_id_'.$intL.'" value="'.$Code_Tool.'">';
										echo'<input type="hidden" name="detDetail['.$intL.'][tool_name]" id="tool_name_'.$intL.'" value="'.$Name_Tool.'">';
										echo'<input type="hidden" name="detDetail['.$intL.'][qty]" id="qty_'.$intL.'" value="'.$Qty_Tool.'">';
										echo'<input type="hidden" name="detDetail['.$intL.'][price]" id="price_'.$intL.'" value="'.$Price_Tool.'">';
										echo'<input type="hidden" name="detDetail['.$intL.'][hpp]" id="hpp_'.$intL.'" value="'.$HPP_Tool.'">';
										echo'<input type="hidden" name="detDetail['.$intL.'][diskon]" id="diskon_'.$intL.'" value="'.$Disc_Tool.'">';
										echo'<input type="hidden" name="detDetail['.$intL.'][net_total]" id="net_total_'.$intL.'" value="'.$Nett_Tool.'">';
										
										
										echo"<td class='text-center'>".$Nomor_Quot."</td>";
										echo"<td class='text-center'>".$Nomor_Letter."</td>";
										echo"<td class='text-left text-wrap'>".$Name_Cust."</td>";	
										echo"<td class='text-center'>".$Code_Tool."</td>";
										echo"<td class='text-left text-wrap'>".$Name_Tool."</td>";	
										echo"<td class='text-right'>".number_format($Price_Tool)."</td>";
										echo"<td class='text-center'>".number_format($Qty_Tool)."</td>";
										echo"<td class='text-center'>".number_format($Disc_Tool)."</td>";
										echo"<td class='text-right'>".number_format($Nett_Tool)."</td>";
										echo"<td align='center'>";
											echo'<input type="text" name="detDetail['.$intL.'][nil_incentive]" id="nil_incentive_'.$intL.'" value="'.number_format($Prosen_Inc).'" class="form-control harga" size = "5px" onblur = "stopCalculation();" onfocus = "startCalculation(\''.$intL.'\');" data-decimal = "." data-thousand ="" data-prefix = "" data-precision = "0">';
										echo"</td>";
										echo"<td align='right'>";
											echo'<input type="text" name="detDetail['.$intL.'][tot_incentive]" id="tot_incentive_'.$intL.'" value="'.number_format($Nil_Inc).'" class="form-control input-sm" size = "25px" readOnly>';
										echo"</td>";
										
										echo"<td align='center'>".$Lingkup."</td>";
									echo"</tr>";
									
								}
							}
							
							
							
							?>
						</tbody>
						<tfoot class='bg-gray'>
							<tr>
								<td class='text-right' colspan='10'><b>Grand Total</b></td>
								<td class='text-right'>
									<?php 
									echo'<input type="text" name="total" id="total" value="'.number_format($Sub_Total).'" class="form-control input-sm" readOnly>';									
									?>
								</td>
								<td class='text-center'>-</td>
							</tr>
							
						</tfoot>
					</table>
				</div>
			</div>
				
			<?php
			
		echo'</div>';
		echo"<div class='box-footer'>";	
			echo'
				<button type="button" class="btn btn-md btn-danger" id="btn-back"> <i class="fa fa-long-arrow-left"></i> BACK </button>';
		if(!empty($rows_detail)){			
				echo'
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn-process-approve" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			
		}
		echo"</div>";
		?>
		
	</div>
</form>

<?php $this->load->view('include/footer'); ?>
<style>
	.sub-heading{
		border-radius :5px;
		background-color :#03506F;
		color : white;
		margin : 20px 10px 15px 10px !important;
		width :98% !important;
	}
	.ui-spinner-input{
		padding :10px 5px 10px 10px !important;
	}
	.text-wrap{
		word-wrap:break-word !important;
	}
	.text-center{
		text-align:center !important;
		vertical-align:middle !important;
	}
	.text-left{
		text-align:left !important;
		vertical-align:middle !important;
	}
	.text-right{
		text-align:right !important;
		vertical-align:middle !important;
	}
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
	

	$(document).ready(function(){
		
		
		$('#btn-back').click(function(){			
			loading_spinner();
			let Link_Back	= base_url+'/'+active_controller+'/list_outstanding_incentive';
			
			window.location.href =  Link_Back;
		});
		
		
	});
	
	
	function startCalculation(id){  
		intervalCalculation = setInterval('Calculation('+id+')',1);
	}
	
	function Calculation(Urut){
		let harga_net		= parseFloat($('#net_total_'+Urut).val().replace(/\,/g,''));		
		let prosen_inc		= parseInt($('#nil_incentive_'+Urut).val().replace(/\,/g,''));
		let tot_inc			= Math.round(harga_net * parseFloat(prosen_inc) / 100);
		
		$('#tot_incentive_'+Urut).val(tot_inc.format(0,3,','));	
		CalcALL();
	}
	function stopCalculation(){   
		clearInterval(intervalCalculation);
	}

	function CalcALL(){
		let grand_tot	= 0;	
		$('#list_detail').find('tr').each(function(){			
			let code_rows		= $(this).attr('id');
			const split_rows	= code_rows.split('_');
			let loop			= split_rows[2];
			let awal			= $('#tot_incentive_'+loop).val().replace(/\,/g,'');
			grand_tot			= parseFloat(grand_tot) + parseFloat(awal); 
			
		});		
		$('#total').val(grand_tot.format(0,3,','));
	}

	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
		
	
	$(document).on('click','#btn-process-approve',(e)=>{
		e.preventDefault();
		$('#btn-back, #btn-process-approve').prop('disabled',true);
		let Keterangan	= $('#descr').val();
		if(Keterangan == '' || Keterangan == null || Keterangan == '-'){
			
			swal({
			  title				: "Error Message !",
			  text				: 'Empty Description. Please inpu description first...',						
			  type				: "warning"
			});
			$('#btn-back, #btn-process-approve').prop('disabled',false);
			return false;
			
		}
		
		let ints		=0;		
		$('#list_detail').find('tr').each(function(){			
			let code_rows		= $(this).attr('id');
			const split_rows	= code_rows.split('_');
			let loop			= split_rows[2];
			let nil_ic			= $('#nil_incentive_'+loop).val().replace(/\,/g,'');
			if(nil_ic == '' || nil_ic == null || parseFloat(nil_ic) <= 0){
				ints++;
			}
		});	
		
		if(ints > 0){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty Incentive Detail. Please inpu incentive detail first...',						
			  type				: "warning"
			});
			$('#btn-back, #btn-process-approve').prop('disabled',false);
			return false;
		}
		
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
					var formData 	= new FormData($('#form_generate_cpr')[0]);
					var baseurl		= base_url +'/'+ active_controller+'/save_process_supervisor_incentive';
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
								$('#btn-back, #btn-process-approve').prop('disabled',false);
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
							$('#btn-back, #btn-process-approve').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-back, #btn-process-approve').prop('disabled',false);
					return false;
				}
		});
	});
	
	
	
	
</script>
