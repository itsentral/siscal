<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form-proses-subcon-po" enctype="multipart/form-data">
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
						<label class="control-label"><b>Order No <span class='text-red'>*</span></b></label>
						<div>
							<span class="badge bg-maroon-active">AUTOMATIC</span>
						</div>						
					</div>
				</div>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>Order Date <span class='text-red'>*</span></b></label>
						<?php
							echo form_input(array('id'=>'order_date','name'=>'order_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y'));						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>Subcon </b></label>
						<?php
							echo form_input(array('id'=>'supplier_name','name'=>'supplier_name','class'=>'form-control input-sm','readOnly'=>true),$rows_supplier->supplier);	
							echo form_input(array('id'=>'supplier_id','name'=>'supplier_id','type'=>'hidden'),$rows_supplier->id);	
							
						?>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>Address</b></label>
						<?php
							echo form_textarea(array('id'=>'address','name'=>'address','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75, 'rows'=>2),$rows_supplier->address);						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>PIC Name</b></label>
						<?php
						echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_supplier->cp);	
						?>
						
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Exclude VAT (PPN)</label>
						<select name="exc_ppn" id="exc_ppn" class="form-control input-sm chosen-select">
							<option value='N'>NO</option>
							<option value='Y'>YES</option>							
						</select>
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
								<th class="text-center">Tool Code</th>
								<th class="text-center">Tool Name</th>
								<th class="text-center">Type</th>
								<th class="text-center">Customer</th>				
								<th class="text-center">Qty</th>
								<th class="text-center">Price</th>
								<th class="text-center">Disc</th>
								<th class="text-center">Total</th>
								<th class="text-center">Description</th>
								<th class="text-center">Notes</th>
							</tr>
						</thead>
						<tbody id="list_item">
							<?php
							$OK_Insitu		=0;
							$Total			=0;
							if($rows_detail){
								$intL		=0;
								foreach($rows_detail as $ketD=>$valD){									
									$intL++;
									$flag_Insitu	= 'N';
									$kelas			= "bg-orange-active";
									if(strtolower($valD->tipe)=='insitu'){
										$flag_Insitu	= 'Y';
										$kelas			= "bg-maroon";
										$OK_Insitu++;
									}
									
									$hpp		= $valD->hpp;
									$Query_HPP	= "SELECT hpp FROM supplier_tools WHERE supplier_id = '".$rows_supplier->id."' AND tool_id = '".$valD->tool_id."' AND flag_active = 'Y' AND price > 0";
									$rows_HPP	= $this->db->query($Query_HPP)->row();
									if($rows_HPP){
										$hpp		= $rows_HPP->hpp;
									}
									$Quot_Sub			=($hpp  * $valD->qty);
									$Total				+= $Quot_Sub;
									
									echo"<tr id='tr_row_".$intL."'>";
											echo'<input type="hidden" name="detDetail['.$intL.'][tool_id]" id="tool_id_'.$intL.'" value="'.$valD->tool_id.'">';											
											echo'<input type="hidden" name="detDetail['.$intL.'][tool_name]" id="tool_name_'.$intL.'" value="'.$valD->tool_name.'">';
											echo'<input type="hidden" name="detDetail['.$intL.'][qty]" id="qty_'.$intL.'" value="'.$valD->qty.'">';
											echo'<input type="hidden" name="detDetail['.$intL.'][hpp]" id="hpp_'.$intL.'" value="'.$hpp.'">';
											echo'<input type="hidden" name="detDetail['.$intL.'][quotation_detail_id]" id="detail_id_'.$intL.'" value="'.$valD->quotation_detail_id.'">';
											echo'<input type="hidden" name="detDetail['.$intL.'][letter_order_detail_id]" id="letter_detail_id_'.$intL.'" value="'.$valD->id.'">';
											echo'<input type="hidden" name="detDetail['.$intL.'][quotation]" id="quotation_'.$intL.'" value="'.$valD->quotation_nomor.'">';
											echo'<input type="hidden" name="detDetail['.$intL.'][letter_order_id]" id="letter_id_'.$intL.'" value="'.$valD->letter_order_id.'">';
											echo'<input type="hidden" name="detDetail['.$intL.'][customer_id]" id="customer_id_'.$intL.'" value="'.$valD->customer_id.'">';
											echo'<input type="hidden" name="detDetail['.$intL.'][customer_name]" id="customer_name_'.$intL.'" value="'.$valD->customer_name.'">';											
											echo'<input type="hidden" name="detDetail['.$intL.'][flag_Insitu]" id="flag_Insitu_'.$intL.'" value="'.$flag_Insitu.'">';
											
											echo"<td align='center'>".$valD->tool_id."</td>";
											echo"<td align='left'>".$valD->tool_name."</td>";
											echo"<td align='left'>
													<span class='badge ".$kelas."'>".$valD->tipe."</span>
												</td>";																					
											echo"<td align='left'>".strtoupper($valD->customer_name)."</td>";
											echo"<td align='center'>".number_format($valD->qty)."</td>";											
											echo"<td align='right'>";
												echo'<input type="text" name="detDetail['.$intL.'][price]" id="price_'.$intL.'" value="'.number_format($hpp).'" class="form-control input-sm harga" size = "25px" onBlur ="stopCalculation();" onFocus = "startCalculation('.$intL.');" data-decimal = "." data-thousand = "" data-precision = "0" data-allow-zero ="false">';
											echo"</td>";
											echo"<td align='right'>";
												echo'<input type="text" name="detDetail['.$intL.'][discount]" id="discount_'.$intL.'" value="0" class="form-control input-sm harga" size = "15px" onBlur ="stopCalculation();" onFocus = "startCalculation('.$intL.');" data-decimal = "." data-thousand = "" data-precision = "0" data-allow-zero ="true">';
											echo"</td>";
											echo"<td align='right'>";
												echo'<input type="text" name="detDetail['.$intL.'][total]" id="total_'.$intL.'" value="'.number_format($Quot_Sub).'" class="form-control input-sm" size = "25px" readOnly>';
											echo"</td>";
											echo"<td align='center'>";
												echo form_textarea(array('id'=>'descr_'.$intL,'name'=>'detDetail['.$intL.'][descr]','class'=>'form-control input-sm','cols'=>75, 'rows'=>2),$valD->address);
											echo"</td>";
											echo"<td align='center'>";
												echo form_textarea(array('id'=>'notes_'.$intL,'name'=>'detDetail['.$intL.'][notes]','class'=>'form-control input-sm','cols'=>75, 'rows'=>2));
											echo"</td>";
										echo"</tr>";
									
									
									
								}
							}
						echo"
						</tbody>
						<tfoot>
							<tr class='bg-gray'>
								<th class='text-right' colspan='7'>Total</th>
								<th class='text-right'>";
									echo'<input type="text" name="item_total" id="item_total" value="'.number_format($Total).'" class="form-control input-sm" size = "25px" readOnly>';
						echo"		
								</th>
								<th class='text-center' colspan='2'>-</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			";
			if($OK_Insitu > 0){
			
			echo"
			<div class='box box-danger' id='detail_insitu'>
				<div class='box-header'>
					<h3 class='box-title'>
						<i class='fa fa-star'></i> <span class='important'>Data Insitu</span>
					</h3>
					<div class='box-tool pull-right'>
						<button type='button' class='btn btn-md btn-primary' id='viewInsitu'> <i class='fa fa-plus'></i> ADD INSITU </button>';
						
					</div>
				</div>
				<div class='box-body'>
					<table class='table table-bordered table-striped'>
						<thead>
							<tr class='bg-navy-active'>
								<td align='center'><b>Area</b></td>
								<td align='center'><b>Price</b></td>
								<td align='center'><b>Duration (Days)</b></td>							
								<td align='center'><b>Discount</b></td>
								<td align='center'><b>Total</b></td>
								<td align='center'><b>Action</b></td>
							</tr>
						</thead>
						<tbody id='list_insitu'>
							
						</tbody>
						<tfoot>
							<tr class='bg-gray'>
								<th align='right' colspan='4'><b>Sub Total</b></th>
								<th align='right'>";
								echo'<input type="text" name="total_insitu" id="total_insitu" value="0" class="form-control input-sm" size = "25px" readOnly>';	
									
							echo"
								</th>
								<th align='center'></th>
							</tr>						
						</tfoot>
					</table>
				</div>
			</div>
			";
			}
			echo"
			<div class='box box-danger' id='detail_akomodasi'>
				<div class='box-header'>
					<h3 class='box-title'>
						<i class='fa fa-star'></i> <span class='important'>Data Accommodation</span>
					</h3>
					<div class='box-tool pull-right'>
						<button type='button' class='btn btn-md btn-primary' id='viewAkomodasi'> <i class='fa fa-plus'></i> ADD ACCOMMODATION </button>';
						
					</div>
				</div>
				<div class='box-body'>
					<table class='table table-bordered table-striped'>
						<thead>
							<tr class='bg-navy-active'>
								<td align='center'><b>Accommodation</b></td>
								<td align='center'><b>Fee</b></td>						
								<td align='center'><b>Discount</b></td>
								<td align='center'><b>Total</b></td>
								<td align='center'><b>Action</b></td>
							</tr>
						</thead>
						<tbody id='list_akomodasi'>
							
						</tbody>
						<tfoot>
							<tr class='bg-gray'>
								<th align='right' colspan='3'><b>Sub Total</b></th>
								<th align='right'>";
								echo'<input type="text" name="total_akomodasi" id="total_akomodasi" value="0" class="form-control input-sm" size = "25px" readOnly>';	
									
							echo"
								</th>
								<th align='center'></th>
							</tr>						
						</tfoot>
					</table>
				</div>
			</div>
			";
			
							
							?>
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>SUMMARY SUBCON PURCHASE ORDER</h5>
				</div>
				
			</div>
		
			<div class='row'>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>Total DPP <span class='text-red'>*</span></b></label>
						<div>
							<?php
							echo form_input(array('id'=>'total_dpp','name'=>'total_dpp','class'=>'form-control input-sm','readOnly'=>true),number_format($Total));						
						?>
						</div>						
					</div>
				</div>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>VAT (PPN)</b></label>
						<?php
							echo form_input(array('id'=>'ppn','name'=>'ppn','class'=>'form-control input-sm','readOnly'=>true),number_format($Total * $prosen_ppn / 100));
							echo form_input(array('id'=>'prosen_ppn','name'=>'prosen_ppn','type'=>'hidden'),$prosen_ppn);
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6 col-xs-12">
					<div class="form-group">
						<label class="control-label"><b>Grand Total <span class='text-red'>*</span></b></label>
						<?php
							echo form_input(array('id'=>'grand_total','name'=>'grand_total','class'=>'form-control input-sm','readOnly'=>true),number_format($Total * (100 + $prosen_ppn) / 100));
							
						?>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12">
					&nbsp;
				</div>				
			</div>
				
			<?php
			
		echo'</div>';
		echo"
		<div class='box-body'>
			<div class='row col-md-2 col-md-offset-5' id='loader_proses_save'>
				<div class='loader'>
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>
		</div>
		<div class='box-footer'>";	
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
<div class="modal fade" id="MyInsitu" >
	<div class="modal-dialog" style="width:80%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">List Insitu Area</h4>
			</div>
			<div class="modal-body" id="listInsitu">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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
	/* LOADER */
	.loader span{
	  display: inline-block;
	  width: 12px;
	  height: 12px;
	  border-radius: 100%;
	  background-color: #3498db;
	  margin: 35px 5px;
	  opacity: 0;
	}

	.loader span:nth-child(1){
		background: #4285F4;
	  	animation: opacitychange 1s ease-in-out infinite;
	}

	.loader span:nth-child(2){
  		background: #DB4437;
	 	animation: opacitychange 1s ease-in-out 0.11s infinite;
	}

	.loader span:nth-child(3){
  		background: #F4B400;
	  	animation: opacitychange 1s ease-in-out 0.22s infinite;
	}
	.loader span:nth-child(4){
  		background: #0F9D58;
	  	animation: opacitychange 1s ease-in-out 0.44s infinite;
	}
	@keyframes opacitychange{
	  0%, 100%{
		opacity: 0;
	  }

	  60%{
		opacity: 1;
	  }
	}
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var Akomodasi_data		= <?php echo json_encode($rows_akomodasi)?>;
	

	$(document).ready(function(){
		$('#loader_proses_save').hide();
		$('.harga').maskMoney();
		$('#btn-back').click(function(){			
			loading_spinner();
			let Link_Back	= base_url+'/'+active_controller+'/outs_subcon_purchase_order';
			
			window.location.href =  Link_Back;
		});
		
		
	});
	
	
	
	$(document).on('click','#viewInsitu',()=>{
		loading_spinner_new();
		$('#listInsitu').html('');
		$.post(base_url +'/'+ active_controller+'/list_master_insitu',{}, function(response) {
			close_spinner_new();
            $("#listInsitu").html(response);
        });
		$("#MyInsitu").modal('show');
	});
	
	const selectArea =(id)=>{	
		
		let ChosenAreaCode	= $('#master_delv_id_'+id).val();
		let ChosenAreaName	= $('#master_delv_area_'+id).val();
		let ChosenAreaFee	= $('#master_delv_fee_'+id).val();
		let AreaFee			= parseFloat(ChosenAreaFee).format(0,3,',');
		
		let total_rows		= $('#list_insitu').find('tr').length;
		let loop			= 1;
		let Exist_Area		= 0;
		if(parseInt(total_rows) > 0){
			let Last_Area		= $('#list_insitu tr:last').attr('id');
			const Temp_Area		= Last_Area.split('_');
			loop				= parseInt(Temp_Area[2])+1;
			
			$('#list_insitu').find('input.cekI:hidden').each(function(){
				let hasil	= $(this).val();
				if(hasil==ChosenAreaCode){
					Exist_Area++;
				}
			});
		}
		
		if(Exist_Area <= 0){
			Template	='<tr id="tr_insitu_'+loop+'">'+
							'<td align="left">'+
								'<input type="hidden" name="detInsitu['+loop+'][delivery_id]"  id="insitu_delivery_id_'+loop+'" value="'+ChosenAreaCode+'" class="cekI">'+
								'<input type="hidden" name="detInsitu['+loop+'][delivery_name]"  id="insitu_delivery_name_'+loop+'" value="'+ChosenAreaName+'">'+
								'<input type="hidden" name="detInsitu['+loop+'][fee]"  id="insitu_fee_'+loop+'" value="'+ChosenAreaFee+'">'+ChosenAreaName+
							'</td>'+				
							'<td align="right">'+AreaFee+'</td>'+			
							'<td align="left"><input type="text" class="form-control input-sm harga" name="detInsitu['+loop+'][day]" id="insitu_day_'+loop+'" onblur="stopCalcInsitu();" onfocus="startCalcInsitu('+loop+');" data-decimal="." data-thousand="" data-prefix="" data-precision="0" data-allow-zero="false" size="5px" value="0"></td>'+			
							'<td align="left"><input type="text" class="form-control input-sm harga" name="detInsitu['+loop+'][diskon]" id="insitu_diskon_'+loop+'" onblur="stopCalcInsitu();" onfocus="startCalcInsitu('+loop+');" data-decimal="." data-thousand="" data-prefix="" data-precision="0" data-allow-zero="true" value="0"></td>'+	
							'<td align="center"><input type="text" class="form-control input-sm" name="detInsitu['+loop+'][total]" id="insitu_total_'+loop+'" readOnly value="0"></td>'+		
							'<td align="center"><button type="button" class="btn btn-sm btn-danger" title="Delete Rows" data-role="qtip" onClick="return DelInsitu('+loop+');"><i class="fa fa-trash-o"></i></button></td>'+
						'</tr>';
			$('#list_insitu').append(Template);
			$(".harga").maskMoney();
		}
	}
	
	const DelInsitu = (id)=>{
		$('#tr_insitu_'+id).remove();
		All_Insitu();
		CalcALL();
	}
	const startCalcInsitu = (id)=>{  
		intervalCalcInsitu = setInterval('CalcInsitu('+id+')',1);
	}
	const CalcInsitu =(id)=>{  
		let Harga_Delv		= parseFloat($('#insitu_fee_'+id).val().replace(/\,/g,''));		
		let Day_Delv		= parseInt($('#insitu_day_'+id).val().replace(/\,/g,''));
		let Disc_Delv		= parseFloat($('#insitu_diskon_'+id).val().replace(/\,/g,''));
		if(Disc_Delv == 0 || Disc_Delv == null){
			Disc_Delv	= 0;
		}		
		let Total_Delv 		= Harga_Delv * Day_Delv;
		let Net_Del			= parseFloat(Total_Delv) - parseFloat(Disc_Delv);
		let DPP_Delv		= Net_Del.format(0,3,',');
		$('#insitu_total_'+id).val(DPP_Delv);
		All_Insitu();
		CalcALL();
	}
	const stopCalcInsitu = ()=>{   
		clearInterval(intervalCalcInsitu);
	}
	
	const All_Insitu = ()=>{
		let sub_tot		=0;
		
		$('#list_insitu').find('tr').each(function(){
			let kode_ins	= $(this).attr('id');
			let det_ins		= kode_ins.split('_');
			let urut_ins	= det_ins[2];
			
			let Harga_Delv		= parseFloat($('#insitu_fee_'+urut_ins).val().replace(/\,/g,''));		
			let Day_Delv		= parseInt($('#insitu_day_'+urut_ins).val().replace(/\,/g,''));
			let Disc_Delv		= parseFloat($('#insitu_diskon_'+urut_ins).val().replace(/\,/g,''));
			if(Disc_Delv == 0 || Disc_Delv == null){
				Disc_Delv	= 0;
			}		
			let Total_Delv 		= Harga_Delv * Day_Delv;
			let Net_Del			= parseFloat(Total_Delv) - parseFloat(Disc_Delv);
			sub_tot				= parseFloat(sub_tot) + parseFloat(Net_Del);
			
			
			
		});
		
		
		let Total_Insitu		= parseFloat(sub_tot);
		$('#total_insitu').val(Total_Insitu.format(0,3,','));		
	}
	
	
	$(document).on('click','#viewAkomodasi',()=>{
		let total_row		= $('#list_akomodasi').find('tr').length;
		let awal			= 1;
		if(parseInt(total_row) > 0){
			let code_rows		= $('#list_akomodasi tr:last').attr('id');
			const rows_CodeID	= code_rows.split('_');
			awal				= parseInt(rows_CodeID[2]) + 1;				
		}
		var Template		=	'<tr id="tr_akomodasi_'+awal+'">'+
									'<td>'+
										'<select name="detAkomodasi['+awal+'][accommodation_id]" id="accommodation_id_'+awal+'" onChange="GetDetailAccommodation('+awal+');" class="form-control input-sm chosen-select">'+
											'<option value="">Silahkan Pilih</option>';						
										$.each(Akomodasi_data,function(key,nilai){
											Template			+='<option value="'+key+'">'+nilai+'</option>';
										});
			Template				  +='</select>'+
									'</td>'+
									'<td align="center">'+
										'<input type="text" class="form-control input-sm harga" name="detAkomodasi['+awal+'][nilai]" id="nilai_akomodasi_'+awal+'" onblur="stopCalcAkmodasi();" onfocus="startCalcAkomodasi('+awal+');" data-decimal="." data-thousand="" data-prefix="" data-precision="0" value="0">'+
									'</td>'+
									'<td align="center">'+
										'<input type="text" class="form-control input-sm harga" name="detAkomodasi['+awal+'][diskon]" id="disc_akomodasi_'+awal+'" onblur="stopCalcAkmodasi();" onfocus="startCalcAkomodasi('+awal+');" data-decimal="." data-thousand="" data-prefix="" data-precision="0" data-allow-zero="true" value="0">'+
									'</td>'+
									'<td align="center">'+
										'<input type="text" class="form-control input-sm" name="detAkomodasi['+awal+'][total]" id="total_akomodasi_'+awal+'" readOnly>'+
									'</td>'+
										'<td align="center"><button type="button" class="btn btn-sm btn-danger" title="Delete Rows" data-role="qtip" onClick="return DelAkomodasi('+awal+');"><i class="fa fa-trash-o"></i></button></td>'+
								'</tr>';
		//console.log(Template);
		$('#list_akomodasi').append(Template);
		$(".harga").maskMoney();
		$(".chosen-select").chosen();
			
	});
	
	const DelAkomodasi= (id)=>{
		$('#tr_akomodasi_'+id).remove();
		All_Akomodasi();
		CalcALL();
	}
	
	const GetDetailAccommodation =(urut)=>{
		let pilihan		= $('#accommodation_id_'+urut).val();
		let ono			= 0;
		$('#list_akomodasi').find('tr').each(function(){
			let kode_akom	= $(this).attr('id');
			const det_akom	= kode_akom.split('_');
			let urut_akom	= det_akom[2];
			if(urut_akom != urut){
				let pilih_urut	= $('#accommodation_id_'+urut_akom).val();
				if(pilihan == pilih_urut){
					//console.log(pilihan+' = '+pilih_urut+' '+urut_akom);
					ono++;
				}
			}
		});
		if(ono > 0){
			GeneralShowMessageError('success','Accommodation has been selected. Please choose other accommodation.....');			
			$('#accommodation_id_'+urut).val('');
			return false;
		}
		
	}
	
	const startCalcAkomodasi = (id)=>{  
		intervalCalcAkomodasi = setInterval('CalcAkomodasi('+id+')',1);
	}
	const CalcAkomodasi = (id)=>{  
		let Fee_Akomodasi		= parseFloat($('#nilai_akomodasi_'+id).val().replace(/\,/g,''));		
		let Disk_Akomodasi		= parseFloat($('#disc_akomodasi_'+id).val().replace(/\,/g,''));
		if(Disk_Akomodasi==0 || Disk_Akomodasi==null){
			Disk_Akomodasi	=0;
		}		
		
		let Net_Akomodasi	= parseFloat(Fee_Akomodasi) - parseFloat(Disk_Akomodasi);
		let DPP_Akomodasi	= Net_Akomodasi.format(0,3,',');
		$('#total_akomodasi_'+id).val(DPP_Akomodasi);
		All_Akomodasi();
		CalcALL();
	}
	
	
	
	function stopCalcAkmodasi(){   
		clearInterval(intervalCalcAkomodasi);
	}
	
	const All_Akomodasi =()=>{
		let sub_tot		=0;
		$('#list_akomodasi').find('tr').each(function(){
			let kode_akom			= $(this).attr('id');
			const det_akom			= kode_akom.split('_');
			let urut_akom			= det_akom[2];
			let Fee_Akomodasi		= parseFloat($('#nilai_akomodasi_'+urut_akom).val().replace(/\,/g,''));		
			let Disk_Akomodasi		= parseFloat($('#disc_akomodasi_'+urut_akom).val().replace(/\,/g,''));
			if(Disk_Akomodasi==0 || Disk_Akomodasi==null){
				Disk_Akomodasi	=0;
			}		
			
			let Net_Akomodasi	= parseFloat(Fee_Akomodasi) - parseFloat(Disk_Akomodasi);
			
			
			sub_tot		= parseFloat(sub_tot) + parseFloat(Net_Akomodasi);
		});
		
		//console.log(sub_tot);
		let Total_Akomodasi		= parseFloat(sub_tot);
		$('#total_akomodasi').val(Total_Akomodasi.format(0,3,','));		
	}
	
	const All_Alat =()=>{
		let sub_tot		=0;
		
		$('#list_item').find('tr').each(function(){			
			let nil		= $(this).attr('id');
			let jum		= nil.split('_');
			let loop	= jum[2];
			let awal	= $('#total_'+loop).val().replace(/\,/g,'');
			sub_tot		= parseFloat(sub_tot) + parseFloat(awal); 
			
		});
		//console.log(sub_tot);
		let Total_Tool		= parseFloat(sub_tot);
		$('#item_total').val(Total_Tool.format(0,3,','));		
	}
	
	function DelItem(id){
		$('#tr_row_'+id).remove();
		CalcALL();
	}
	
	
	const CalcALL =()=>{
		let sub_tot		= 0;
		let grand_tot	= 0;
		let ket_ppn		= $('#exc_ppn').val();
		let prosen_ppn	= $('#prosen_ppn').val();
		let total_alat	= 0;
		
		$('#list_item').find('tr').each(function(){			
			let nil		= $(this).attr('id');
			const jum	= nil.split('_');
			let loop	= jum[2];
			let awal	= $('#total_'+loop).val().replace(/\,/g,'');
			sub_tot		= parseFloat(sub_tot) + parseFloat(awal);
			total_alat	= parseFloat(total_alat) + parseFloat(awal);
			
		});
		$('#list_insitu').find('tr').each(function(){
			let kode_ins	= $(this).attr('id');
			const det_ins	= kode_ins.split('_');
			let urut_ins	= det_ins[2];
			let harga		= parseFloat($('#insitu_fee_'+urut_ins).val().replace(/\,/g,''));		
			let qty			= parseInt($('#insitu_day_'+urut_ins).val().replace(/\,/g,''));
			let diskon		= $('#insitu_diskon_'+urut_ins).val().replace(/\,/g,'');
			if(diskon==0 || diskon==null){
				diskon	=0;
			}		
			let Total 		= harga * qty;
			let net_total	= parseFloat(Total) - parseFloat(diskon);
			sub_tot		   = parseFloat(sub_tot) + parseFloat(net_total);
		});
		
		$('#list_akomodasi').find('tr').each(function(){
			let kode_akom	= $(this).attr('id');
			const det_akom	= kode_akom.split('_');
			let urut_akom	= det_akom[2];
			let Harga		= $('#nilai_akomodasi_'+urut_akom).val().replace(/\,/g,'');
			let diskon		= $('#disc_akomodasi_'+urut_akom).val().replace(/\,/g,'');
			if(diskon==0 || diskon==null){
				diskon	=0;
			}
			let net_total	= parseFloat(Harga) - parseFloat(diskon);
			sub_tot		= parseFloat(sub_tot) + parseFloat(net_total);
		});
		
		
		let ppn			=0;
		if(ket_ppn=='N'){
			ppn		= Math.floor(parseFloat(total_alat) * parseFloat(prosen_ppn) / 100);
		}
		grand_tot		= parseFloat(sub_tot) + parseFloat(ppn);
		$('#total_dpp').val(sub_tot.format(0,3,','));
		
		$('#ppn').val(ppn.format(0,3,','));
		$('#grand_total').val(grand_tot.format(0,3,','));
	}
	
	
	function startCalculation(id){  
		intervalCalculation = setInterval('Calculation('+id+')',1);
	}
	function Calculation(id){
		
		let harga		= parseInt($('#price_'+id).val().replace(/\,/g,''));		
		let qty			= parseInt($('#qty_'+id).val().replace(/\,/g,''));
		let diskon		= $('#discount_'+id).val().replace(/\,/g,'');
		if(diskon == 0 || diskon == null){
			diskon	= 0;
		}
		
		let net_pcs		= harga;
		let tot_net		= harga * qty;
		let nil_diskon	= Math.round(parseFloat(tot_net) * parseFloat(diskon)/100);
		//console.log('total_harga : '+tot_net+' discount : '+diskon);
		let net_harga		= parseFloat(tot_net) - parseFloat(nil_diskon);
		$('#total_'+id).val(net_harga.format(0,3,','));
		All_Alat();
		CalcALL();
	}
	function stopCalculation(){   
		clearInterval(intervalCalculation);
	}

	

	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
		
	$(document).on('click','#btn-process-approve', async(e)=>{
		e.preventDefault();
		$('#btn-back, #btn-process-approve').prop('disabled',true);
		
		let Supplier_Code = $('#supplier_id').val();
		let Tool_List		= $('#list_item').find('tr').length;
		
		
		const ValueCheck	= {
			'supplier':{'nilai':Supplier_Code,'error':'Empty Supplier. Please input supplier first..'}
		};
		
		if(parseInt(Tool_List) <= 0 || Tool_List == '' || Tool_List == null){
			ValueCheck['jumlah_alat']	= {'nilai':'','error':'No Tool Data Was Selected. Please Choose At Least One Tool......'};
		}
		
		let ints		= 0;
		let f_insitu	= 0;
		let intH		= 0;
		$('#list_item').find('tr').each(function(){
			let nil			= $(this).attr('id');
			let jum			= nil.split('_');
			let kode		= parseInt($('#qty_'+jum[2]).val());
			let sts_insitu	= $('#flag_Insitu_'+jum[2]).val();
			let harga_alat	= $('#price_'+jum[2]).val().replace(/\,/g,'');
			if(kode <= 0){					
				ints++;
			}
			if(harga_alat == '' || harga_alat == null || parseFloat(harga_alat) < 1000){
				intH++;
			}
			if(sts_insitu == 'Y'){
				f_insitu++;
			}
		});
		if(ints > 0){
			ValueCheck['qty_alat']	= {'nilai':'','error':'Qty Tool Cant Be Null. Please Input Qty At Least 1..'};
		}
		
		if(intH > 0){
			ValueCheck['harga_alat']	= {'nilai':'','error':'Price Tool Cant Be Null. Please Input Price First...'};
		}
		let data_insitu	= $('#list_insitu').find('tr').length;
		if(parseInt(data_insitu) > 0){
			let intD	=0;
			let intQ	=0;
			$('#list_insitu').find('tr').each(function(){
				let kode_ins	= $(this).attr('id');
				const jum_ins	= kode_ins.split('_');
				let delivery_id	= $('#insitu_delivery_id_'+jum_ins[2]).val();
				let hari		= $('#insitu_day_'+jum_ins[2]).val();
				if(delivery_id==null || delivery_id==''){
					intD++;
				}
				if(hari==0 || hari==null || hari==''){
					intQ++;
				}
			});
			
			if(intD > 0){
				ValueCheck['area_insitu']	= {'nilai':'','error':'Empty Area Insitu. Please Choose Area Insitu First....'};
				
			}
			if(intQ > 0){
				ValueCheck['day_insitu']	= {'nilai':'','error':'Empty Insitu Day. Please Input Insitu Day....'};
				
			}
		}
		
		if((f_insitu > 0 && parseInt(data_insitu) < 1) || (f_insitu == 0 && parseInt(data_insitu) > 0)){
			ValueCheck['tool_insitu']	= {'nilai':'','error':'Empty Insitu Area Or Insitu Tool. Please Set Insitu Tool Or Add Insitu Data...'};
			
		}
		
		let data_akomodasi	= $('#list_akomodasi').find('tr').length;
		if(parseInt(data_akomodasi) > 0){
			let intA	=0;
			let intF	=0;
			$('#list_akomodasi').find('tr').each(function(){
				let kode_akom		= $(this).attr('id');
				const jum_akom		= kode_akom.split('_');
				let akomodasi_id	= $('#accommodation_id_'+jum_akom[2]).val();
				let fee				= $('#nilai_akomodasi_'+jum_akom[2]).val().replace(/\,/g,'');
				if(akomodasi_id==null || akomodasi_id==''){
					intA++;
				}
				if(fee==0 || fee==null || fee==''){
					intF++;
				}
			});
			
			if(intA > 0){
				ValueCheck['tipe_akom']	= {'nilai':'','error':'Empty Accommodation Type. Please Choose Accommodation Type First..'};
			
			}
			if(intF > 0){
				ValueCheck['nil_akom']	= {'nilai':'','error':'Empty Accommodation Value. Please Input Accommodation Value..'};
				
			}
		}
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form-proses-subcon-po')[0]);
			const ParamProcess	= {
				'action'		: 'save_subcon_purchase_order_process',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href	= base_url+'/'+active_controller;
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-back, #btn-process-approve').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-back, #btn-process-approve').prop('disabled',false);
            return false;
		}
	});
	
	
	
	
	function ValidateSingleInput(oInput) {
		if (oInput.type == "file") {
			var sFileName = oInput.value;
			 if (sFileName.length > 0) {
				var blnValid = false;
				for (var j = 0; j < _validFileExtensions.length; j++) {
					var sCurExtension = _validFileExtensions[j];
					if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
						blnValid = true;
						break;
					}
				}
				 
				if (!blnValid) {
					swal({
					  title				: "Error Message !",
					  text				: 'Hanya boleh pilih jenis file IMAGES atau PDF....',						
					  type				: "warning"
					});
					
					oInput.value = "";
					return false;
				}
			}
		}
    	return true;
	}
	
	$(document).on('change','#exc_ppn',CalcALL);
</script>
