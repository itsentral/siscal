<?php
$this->load->view('include/side_menu');
?> 
<form action="#" method="POST" id="form_proses_order" enctype="multipart/form-data" accept-charset="UTF-8">
	<div class="box box-warning">
		
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5><?php echo $title;?></h5>
				</div>
				
			</div>
			<?php
			if(empty($rows_detail)){
				echo"<div class='row'>
						<div class='col-sm-12'>
							<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
						</div>
					</div>";
			}else{
				
				
			?>
				
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO No</label>
							<?php
								echo form_input(array('id'=>'no_so','name'=>'no_so','class'=>'form-control input-sm','readOnly'=>true),$noso_rev);	
								echo form_input(array('id'=>'revisi','name'=>'revisi','type'=>'hidden'),$urut_rev);
								echo form_input(array('id'=>'old_id','name'=>'old_id','type'=>'hidden'),$code_old);
								echo form_input(array('id'=>'prev_id','name'=>'prev_id','type'=>'hidden'),$rows_header->id);
								echo form_input(array('id'=>'tgl_so','name'=>'tgl_so','type'=>'hidden'),$tgl_old);
								echo form_input(array('id'=>'quotation_id','name'=>'quotation_id','type'=>'hidden'),$rows_header->quotation_id);
								echo form_input(array('id'=>'customer_id','name'=>'customer_id','type'=>'hidden'),$rows_header->customer_id);
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SO Date</label>
							<?php
								echo form_input(array('id'=>'so_date','name'=>'so_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($tgl_old)));						
							?>
						</div>
					</div>				
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL QUOTATION</h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation</label>
							<?php
								echo form_input(array('id'=>'quotation_nomor','name'=>'quotation_nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->nomor);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation Date</label>
							<?php
								echo form_input(array('id'=>'quotation_date','name'=>'quotation_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_quot->datet)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->pono);	
								
							?>
						</div>
					</div>	
					<div class="col-sm-6">
						&nbsp;
					</div>
								
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL CUSTOMER</h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->customer_name);
														
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Plant</label>
							<select name="comp_plant" id="comp_plant" class="form-control input-sm chosen-select">
								<option value=""> - </option>
							<?php
								if($rows_plant){
									foreach($rows_plant as $keyPlant=>$valPlant){
										echo'<option value="'.$valPlant->id.'">'.$valPlant->branch.'</option>';
									}
								}
								
								
							?>
							</select>
						</div>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PIC Name <span class="text-red"> *</span></label>
							<div>
							<?php								
								echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm'),$rows_header->pic);	
								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PIC Phone <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'pic_phone','name'=>'pic_phone','class'=>'form-control input-sm'),str_replace(array('+','-',' '),'',$rows_header->phone));						
							?>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>ADDRESS </h5>
					</div>					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Delivery Address <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'address_send','name'=>'address_send','class'=>'form-control input-sm','cols'=>75,'rows'=>2),$rows_header->address_send);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Invoice Address <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'address_inv','name'=>'address_inv','class'=>'form-control input-sm','cols'=>75,'rows'=>2),$rows_header->address_inv);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Certificate Address <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'address_sertifikat','name'=>'address_sertifikat','class'=>'form-control input-sm','cols'=>75,'rows'=>2),$rows_header->address_sertifikat);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Calibration Address <span class="text-red"> *</span></label>
							<?php
								echo form_textarea(array('id'=>'address','name'=>'address','class'=>'form-control input-sm','cols'=>75,'rows'=>2),$rows_header->address);						
							?>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL NOTES</h5>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Delivery Notes</label>
							<?php
								echo form_textarea(array('id'=>'send_notes','name'=>'send_notes','class'=>'form-control input-sm text-up','cols'=>100,'rows'=>2),$rows_header->notes_delivery);
														
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Invoice Notes</label>
							<?php
								echo form_textarea(array('id'=>'inv_notes','name'=>'inv_notes','class'=>'form-control input-sm text-up','cols'=>100,'rows'=>2),$rows_header->notes_invoice);
											
							?>
						</div>
					</div>				
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL TOOL </h5>
					</div>
					
				</div>
				<div class="row">
					<div class="col-sm-12 text-right">
						<button type="button" class="btn btn-md bg-green-active" id="btn_add_tool"> <i class="fa fa-plus"></i> ADD TOOLS </button>
						
					</div>
					<div class="col-sm-12">
						&nbsp;
					</div>
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr class="bg-navy-active">
									<th class="text-center">Tool Name</th>
									<th class="text-center">Vendor</th>
									<th class="text-center">Qty</th>
									<th class="text-center">Req Cust</th>
									<th class="text-center">Description</th>
									<th class="text-center">Area Insitu</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								$Arr_Type	= array('Driver','Customer');
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										$intL++;
										$Code_Detail	= $valD->detail_id;
										$Code_DetQuot	= $valD->quotation_detail_id;
										$Code_Alat		= $valD->tool_id;
										$Nama_Alat		= $valD->tool_name;
										$Qty_Outs		= $valD->qty;
										$Range_Alat		= $valD->range.' '.$valD->piece_id;
										$Keterangan		= $valD->descr;
										$Ket_Cust		= '';
										$Def_CodeSupp	= $valD->supplier_id;
										$Def_NameSupp	= $valD->supplier_name;
										
										$Type			= $valD->tipe;
										$Get_Tool		= $valD->get_tool;
										
										$rows_QuotDet	= $this->db->get_where('quotation_details',array('id'=>$Code_DetQuot))->row();
										if($rows_QuotDet){
											$Ket_Cust	= $rows_QuotDet->descr;
										}
										
										$Tipe			= 'I';
																				
											
										echo'<tr id="tr_urut_'.$intL.'">';
											echo form_input(array('id'=>'quotation_detail_id_'.$intL,'name'=>'detDetail['.$intL.'][quotation_detail_id]','type'=>'hidden','class'=>'cekD'),$Code_DetQuot);
											echo form_input(array('id'=>'code_detail_'.$intL,'name'=>'detDetail['.$intL.'][code_detail]','type'=>'hidden'),$Code_Detail);
											echo form_input(array('id'=>'tool_id_'.$intL,'name'=>'detDetail['.$intL.'][tool_id]','type'=>'hidden'),$Code_Alat);
											echo form_input(array('id'=>'tool_name_'.$intL,'name'=>'detDetail['.$intL.'][tool_name]','type'=>'hidden'),$Nama_Alat);
											echo form_input(array('id'=>'range_'.$intL,'name'=>'detDetail['.$intL.'][range]','type'=>'hidden'),$valD->range);
											echo form_input(array('id'=>'piece_id_'.$intL,'name'=>'detDetail['.$intL.'][piece_id]','type'=>'hidden'),$valD->piece_id);
											echo form_input(array('id'=>'qty_sisa_'.$intL,'name'=>'detDetail['.$intL.'][qty_sisa]','type'=>'hidden'),$Qty_Outs);
											echo form_input(array('id'=>'tipe_'.$intL,'name'=>'detDetail['.$intL.'][tipe]','type'=>'hidden'),$Tipe);
											echo form_input(array('id'=>'get_tool_'.$intL,'name'=>'detDetail['.$intL.'][get_tool]','type'=>'hidden'),'Driver');
											echo'											
											<td class="text-left text-wrap">'.$Nama_Alat.'</td>
											<td class="text-left">';
												
												
												if(strtolower($Def_CodeSupp) == 'comp-001'){
													
													echo $Def_NameSupp;
													echo form_input(array('id'=>'supplier_'.$intL,'name'=>'detDetail['.$intL.'][supplier]','type'=>'hidden'),$Def_CodeSupp);
												}else{
													echo'
													<select name="detDetail['.$intL.'][supplier]" id="supplier_'.$intL.'" class="form-control chosen-select">
														
													';
													if($rows_supplier){
														foreach($rows_supplier as $keySupp=>$valSupp){
															$Yuup	= ($keySupp == $Def_CodeSupp)?'selected':'';
															echo '
															<option value="'.$keySupp.'" '.$Yuup.'>'.$valSupp.'</option>
															';
														}
													}
													echo'
													</select>
													';
												}
												
											echo'
											</td>
											<td class="text-center">'.form_input(array('id'=>'qty_detail_'.$intL,'name'=>'detDetail['.$intL.'][qty]','class'=>'form-control','readOnly'=>true),$Qty_Outs).'</td>
											<td class="text-left text-wrap">'.$Ket_Cust.'</td>
											<td class="text-center">'.form_textarea(array('id'=>'desc_'.$intL,'name'=>'detDetail['.$intL.'][descr]','class'=>'form-control input-sm text-up text-wrap','cols'=>50,'rows'=>2),$Keterangan).'</td>
											<td class="text-center">
													<select name="detDetail['.$intL.'][delivery_id]" id="delivery_id_'.$intL.'" class="form-control chosen-select">
														<option value=""> - OPTION LIST - </option>
													';
													if($rows_delivery){
														foreach($rows_delivery as $keyDelv=>$valDelv){
															$Code_Unik	= $keyDelv.'^'.$valDelv;
															$Yuup		= ($keyDelv == $valD->delivery_id)?'selected':'';
															echo '
															<option value="'.$Code_Unik.'" '.$Yuup.'>'.strtoupper($valDelv).'</option>
															';
														}
													}
											echo'
													</select>
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-sm btn-danger" title="Hapus Data" data-role="qtip" onClick="return DelItem('.$intL.');"><i class="fa fa-trash-o"></i></button>
											</td>																								
										</tr>
										';
											
										
											
										
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				
			<?php
				
			}
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
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn_process_order" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			
		}
		echo"</div>";
		?>
		
	</div>
</form>
<div class="modal fade" id="MyModalView" tabindex="-1" role="dialog" aria-labelledby="MyModal" data-backdrop="static">
	<div class="modal-dialog" role="document" style="min-width:70% !important;">
		 <div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="MyModalTitle"></h5>
				<button class="close" data-dismiss="modal" aria-label="close" id="btn-modal-close">
					<span aria-hidden="true"><i class="fa fa-close"></i></span>
				</button>
			</div>
			<div class="modal-body" id="MyModalDetail">
				<table class='table table-bordered table-striped'>
					<thead>
						<tr class='bg-blue'>
							<th class='text-center'>Tool Name</th>
							<th class='text-center'>Range</th>
							<th class='text-center'>Qty</th>
							<th class='text-center'>Vendor</th>
							<th class='text-center'>Description</th>
							<th class='text-center'>Action</th>
						</tr>
					</thead>
					<tbody id='list_order_data'>
						<?php
							
							if($rows_outs){
								$loopD	=0;
								foreach($rows_outs as $keyD=>$valD){
									$loopD++;
									$Code_Detail	= $valD->id;
									$Code_DetQuot	= $valD->id;
									$Code_Alat		= $valD->tool_id;
									$Nama_Alat		= $valD->tool_name;
									$Cust_Alat		= $valD->cust_tool;
									$Qty_Outs		= $valD->qty -  $valD->qty_so;
									$Range_Alat		= $valD->range.' '.$valD->piece_id;
									$Keterangan		= '';
									$Ket_Cust		= $valD->descr;
									$Def_CodeSupp	= $valD->supplier_id;
									$Def_NameSupp	= $valD->supplier_name;
									
									$Tool_Name		= $Nama_Alat;
									if(!empty($Cust_Alat) && $Cust_Alat !== '-'){
										$Tool_Name		= $Cust_Alat;
									}
									
									$Tipe		= 'I';
									
									
									
									
									echo"<tr id='tr_order_".$loopD."'>";
										echo form_input(array('id'=>'outs_quotation_detail_id_'.$loopD,'type'=>'hidden'),$Code_DetQuot);
										echo form_input(array('id'=>'outs_code_detail_'.$loopD,'type'=>'hidden'),$Code_Detail);
										echo form_input(array('id'=>'outs_tool_id_'.$loopD,'type'=>'hidden'),$Code_Alat);
										echo form_input(array('id'=>'outs_tool_name_'.$loopD,'type'=>'hidden'),$Tool_Name);
										echo form_input(array('id'=>'outs_range_'.$loopD,'type'=>'hidden'),$valD->range);
										echo form_input(array('id'=>'outs_piece_id_'.$loopD,'type'=>'hidden'),$valD->piece_id);
										echo form_input(array('id'=>'outs_qty_'.$loopD,'type'=>'hidden'),$Qty_Outs);
										echo form_input(array('id'=>'outs_supplier_id_'.$loopD,'type'=>'hidden'),$Def_CodeSupp);
										echo form_input(array('id'=>'outs_supplier_name_'.$loopD,'type'=>'hidden'),$Def_NameSupp);
										echo form_input(array('id'=>'outs_descr_'.$loopD,'type'=>'hidden'),$Keterangan);
										echo form_input(array('id'=>'outs_cust_descr_'.$loopD,'type'=>'hidden'),$Ket_Cust);
										echo form_input(array('id'=>'outs_tipe_'.$loopD,'type'=>'hidden'),$Tipe);
										
										echo"<td class='text-left'>".$Tool_Name."</td>";
										echo"<td class='text-center'>".$Range_Alat." ".$valD->piece_id."</td>";										
										echo"<td class='text-center'>".$Qty_Outs."</td>";
										echo"<td class='text-left'>".$Def_NameSupp."</td>";
										echo"<td class='text-left'>".$Ket_Cust."</td>";
										echo"<td class='text-center'>";
											echo"<button type='button' class='btn btn-md btn-danger' onClick='return selectOrder(".$loopD.");'>CHOOSE</button>";
										echo"</td>";
									echo"</tr>";
								}
							}
							
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
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
	.text-up{
		text-transform : uppercase !important;
	}
	.text-center{
		text-align : center !important;
		vertical-align	: middle !important;
	}
	.text-left{
		text-align : left !important;
		vertical-align	: middle !important;
	}
	.text-wrap{
		word-wrap : break-word !important;
	}
	
</style>
<script>
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var rows_supplier		= <?php echo json_encode($rows_supplier); ?>;
	var rows_delivery		= <?php echo json_encode($rows_delivery); ?>;
	
	var _Address_Delv		= $('#address_send').val();
	var _Address_Inv		= $('#address_inv').val();
	var _Address_Cert		= $('#address_sertifikat').val();
	var _Address_Cust		= $('#address').val();
	var _PIC_Cust			= $('#pic_name').val();
	var _PIC_Phone			= $('#pic_phone').val();
	
	$(document).ready(function(){
		$('#loader_proses_save').hide();
		$('#list_detail').find('tr').each(function(){
			var id_tr	= $(this).attr('id').split('_');
			var kodes	= id_tr[2];
			var nil		= $('#qty_sisa_'+kodes).val();
			
			$('#qty_detail_'+kodes).spinner({					
				min: 0,
				max: parseInt(nil)
			 });
		});	
		
		$('.tanggal').datepicker({
			dateFormat	: 'dd-mm-yy',
			changeMonth	:true,
			changeYear	:true,
			minDate		:'+0d'
		});
		
		//$('#pic_phone').mask('?999 999 999 999 999');
		$('.chosen-select').chosen();
		
	});
	
		
	$(document).on('click','#btn-back',(e)=>{
		loading_spinner();
		window.location.href =  base_url+'/'+active_controller;
	});
	
	$(document).on('click','#btn_add_tool',()=>{
		$('#MyModalView').modal('show');
	});
	
	const selectOrder = (id)=>{	
	
		
		let Code_Detail = $('#outs_code_detail_'+id).val();
		let Code_Quot 	= $('#outs_quotation_detail_id_'+id).val();
		let Code_Tool 	= $('#outs_tool_id_'+id).val();
		let Name_Tool 	= $('#outs_tool_name_'+id).val();
		let Range_Tool	= $('#outs_range_'+id).val();
		let Satuan_Tool	= $('#outs_piece_id_'+id).val();
		let Qty_Tool	= $('#outs_qty_'+id).val();
		let Code_Supp	= $('#outs_supplier_id_'+id).val();
		let Name_Supp	= $('#outs_supplier_name_'+id).val();
		let Descr_Tool	= $('#outs_descr_'+id).val();
		let Descr_Cust	= $('#outs_cust_descr_'+id).val();
		let Tipe_Pros	= $('#outs_tipe_'+id).val();
		
		
		
		let total	=$('#list_detail').find('tr').length;
		let loop	= 1;
		let ada		= 0;
		if(parseInt(total) > 0){
			let CodeRows	= $('#list_detail tr:last').attr('id');
			const SplitRows = CodeRows.split('_');
			loop			= parseInt(SplitRows[2]) + 1;
			
			$('#list_detail').find('input.cekD:hidden').each(function(){
				let hasil	= $(this).val();
				if(hasil==Code_Quot){
					ada++;
				}
			});
			
			
		}
		
				
		if(ada==0){
			let Template	='<tr id="tr_urut_'+loop+'">'+
								'<input type="hidden" name="detDetail['+loop+'][quotation_detail_id]"  id="quotation_detail_id_'+loop+'" value="'+Code_Quot+'" class="cekD">'+
								'<input type="hidden" name="detDetail['+loop+'][code_detail]"  id="code_detail_'+loop+'" value="'+Code_Detail+'">'+
								'<input type="hidden" name="detDetail['+loop+'][tool_id]"  id="tool_id_'+loop+'" value="'+Code_Tool+'">'+
								'<input type="hidden" name="detDetail['+loop+'][tool_name]"  id="tool_name_'+loop+'" value="'+Name_Tool+'">'+
								'<input type="hidden" name="detDetail['+loop+'][range]"  id="range_'+loop+'" value="'+Range_Tool+'">'+
								'<input type="hidden" name="detDetail['+loop+'][piece_id]"  id="piece_id_'+loop+'" value="'+Satuan_Tool+'">'+
								'<input type="hidden" name="detDetail['+loop+'][qty_sisa]"  id="qty_sisa_'+loop+'" value="'+Qty_Tool+'">'+
								'<input type="hidden" name="detDetail['+loop+'][tipe]"  id="tipe_'+loop+'" value="'+Tipe_Pros+'">'+
								'<input type="hidden" name="detDetail['+loop+'][get_tool]"  id="get_tool_'+loop+'" value="Driver">'+
								'<td class="text-left text-wrap">'+Name_Tool+'</td>'+
								'<td class="text-left">';
								if(Code_Supp !='COMP-001'){	
									Template	+='<select name="detDetail['+loop+'][supplier]" id="supplier_'+loop+'" class="form-control chosen-select">';
										if(!$.isEmptyObject(rows_supplier)){
											$.each(rows_supplier,function(key,value){
												let yuup	=(key==Code_Supp)?'selected':'';
												Template	+='<option value="'+key+'" '+yuup+'>'+value+'</option>';
												
											});
										}
										
									Template	+='</select>';
										
									}else{
										Template	+='<input type="hidden" name="detDetail['+loop+'][supplier]"  id="supplier_'+loop+'" value="'+Code_Supp+'">'+Name_Supp;
										
									}
				Template	+='</td>'+
							'<td class="text-center"><input type="text" name="detDetail['+loop+'][qty]"  id="qty_detail_'+loop+'" value="'+Qty_Tool+'" class="form-control input-sm" readOnly></td>'+
							'<td class="text-left text-wrap">'+Descr_Cust+'</td>'+
							'<td class="text-center"><textarea cols="50" rows="2" name="detDetail['+loop+'][descr]"  id="desc_'+loop+'" class="form-control input-sm text-up text-wrap">'+Descr_Tool+'</textarea></td>'+
							'<td class="text-center">';
					Template	+='<select name="detDetail['+loop+'][delivery_id]" id="delivery_id_'+loop+'" class="form-control chosen-select">';
						if(!$.isEmptyObject(rows_delivery)){
							$.each(rows_delivery,function(key,value){
								let code_unik = key+'^'+value;
								Template	+='<option value="'+code_unik+'">'+value+'</option>';
								
							});
						}
								
					Template	+='</select>';
				Template	+='</td>'+
							'<td align="center"><button type="button" class="btn btn-sm btn-danger" title="Hapus Data" data-role="qtip" onClick="return DelItem('+loop+');"><i class="fa fa-trash-o"></i></button></td>'+
						'</tr>';
						
			$('#list_detail').append(Template);
			$('#qty_detail_'+loop).spinner({					
				min: 1,
				max: parseInt(Qty_Tool)
			 });
			 $('#delivery_id_'+loop).chosen({width: '100%'});
			 if(Code_Supp !='COMP-001'){
				$('#supplier_'+loop).chosen({width: '100%'});
				$('#supplier_'+loop).val(Code_Supp).trigger('chosen:updated');
			 }
							
		
		}
		
	}
	
	const DelItem =(Urut)=>{
		$('#tr_urut_'+Urut).remove();
	}
	
	
	$(document).on('click','#btn_process_order', async(e)=>{
		e.preventDefault();
		$('#btn-back, #btn_process_order').prop('disabled',true);
		
		let AddressChosen = $('#address').val();
		let AddressDeliver = $('#address_send').val();
		let AddressInvoice = $('#address_inv').val();
		let AddressCert 	= $('#address_sertifikat').val();
		let PICNameChosen = $('#pic_name').val();
		let PicPhoneChosen=	$('#pic_phone').val();
		
		const ValueCheck	= {
			'alamat':{'nilai':AddressChosen,'error':'Empty Customer Address. Please input customer address first..'},
			'kirim':{'nilai':AddressDeliver,'error':'Empty Delivery Address. Please input delivery address first..'},
			'invoice':{'nilai':AddressInvoice,'error':'Empty Invoice Address. Please input invoice address first..'},
			'dokumen':{'nilai':AddressCert,'error':'Empty Certificate Address. Please input certificate address first..'},
			'contact_person':{'nilai':PICNameChosen,'error':'Empty PIC Name. Please input PIC name first..'},
			'contact_phone':{'nilai':PicPhoneChosen,'error':'Empty PIC Phone. Please input pic phone first..'}
		};
		
		let JumChecked	= $('#list_detail').find('tr').length;
		if(parseInt(JumChecked) <= 0){
			let rowsChosen		= '';
			ValueCheck['rows_pilih']	={'nilai':rowsChosen,'error':'No record was selected. Please choose at least one record..'};
			
		}
		let intL	= 0;
		let intD	= 0;
		$('#list_detail').find('tr').each(function(){
			const SplitCode	= $(this).attr('id').split('_');
			let CodeUrut	= SplitCode[2];
			
			let QtyChosen	= $('#qty_detail_'+CodeUrut).val();
			if(parseInt(QtyChosen) <= 0 || QtyChosen == null || QtyChosen == ''){
				intL++;
			}
			
			let DelvChosen	= $('#delivery_id_'+CodeUrut).val();
			if(DelvChosen == null || DelvChosen == ''){
				intD++;
			}
		});
		
		if(intL > 0){
			let QtyChosen		= '';
			ValueCheck['rows_qty']	={'nilai':QtyChosen,'error':'Empty Quantity Pickup. Please input qty first...'};
			
		}
		
		if(intD > 0){
			let DelvChosen		= '';
			ValueCheck['rows_kirim']	={'nilai':DelvChosen,'error':'Empty Insitu area. Please choose area insitu first...'};
			
		}
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form_proses_order')[0]);
			const ParamProcess	= {
				'action'		: 'save_revisi_letter_order',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href	= base_url+'/'+active_controller;
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-back, #btn_process_order').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-back, #btn_process_order').prop('disabled',false);
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
	
	$(document).on('change','#comp_plant',()=>{
		
		let ChosenPlant			= $('#comp_plant').val();
		if(ChosenPlant == '' || ChosenPlant == null){
			$('#address_send').val(_Address_Delv);
			$('#address_inv').val(_Address_Inv);
			$('#address_sertifikat').val(_Address_Cert);
			$('#address').val(_Address_Cust);
			$('#pic_name').val(_PIC_Cust);
			$('#pic_phone').val(_PIC_Phone);
		}else{
			let ChosenCust	= $('#customer_id').val();
			$('#loader_proses_save').show();
			$.post(base_url+'/'+active_controller+'/get_detail_comp_plant',{'plant':ChosenPlant,'nocust':ChosenCust},function(response){
				$('#loader_proses_save').hide();
				const datas	= $.parseJSON(response);
				$('#address').val(datas.alamat);
				$('#address_send').val(datas.alamat);
				$('#address_sertifikat').val(datas.alamat);
				$('#pic_name').val(datas.nama);
				$('#pic_phone').val(datas.phone);
			});
		}
	});
	
</script>
