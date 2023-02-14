
<form action="#" method="POST" id="form_proses_update" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			
			<?php
			if(empty($rows_detail)){
				echo"<div class='row'>
						<div class='col-sm-12'>
							<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
						</div>
					</div>";
			}else{
				$rows_Quot		= $this->db->get_where('quotations',array('id'=>$rows_detail->quotation_id))->row();
				echo form_input(array('id'=>'code_trans','name'=>'code_trans','type'=>'hidden'),$code_trans);
				echo form_input(array('id'=>'code_new','name'=>'code_new','type'=>'hidden'),$code_new);
				echo form_input(array('id'=>'code_driver_detail','name'=>'code_driver_detail','type'=>'hidden'),$rows_detail->id);
				echo form_input(array('id'=>'code_driver_header','name'=>'code_driver_header','type'=>'hidden'),$rows_header->id);
				echo form_input(array('id'=>'driver_id_modal','name'=>'driver_id_modal','type'=>'hidden'),$rows_header->driver_id);
				echo form_input(array('id'=>'cust_id_modal','name'=>'cust_id_modal','type'=>'hidden'),$rows_header->customer_id);
				echo form_input(array('id'=>'quotation_update','name'=>'quotation_update','type'=>'hidden'),$rows_detail->quotation_id);
			?>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL DRIVER PICKUP</h5>
					</div>					
				</div>
				<div class='row'>
					
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Pickup No</label>
							<?php
								echo form_input(array('id'=>'pickup_nomor_modal','name'=>'pickup_nomor_modal','class'=>'form-control input-sm','readOnly'=>true),$rows_header->nomor);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Pickup Date</label>
							<?php
								echo form_input(array('id'=>'pickup_date_modal','name'=>'pickup_date_modal','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header->datet)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'cust_modal','name'=>'cust_modal','class'=>'form-control input-sm','readOnly'=>true),$rows_header->customer_name);
														
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Driver</label>
							<?php
								echo form_input(array('id'=>'driver_modal','name'=>'driver_modal','class'=>'form-control input-sm','readOnly'=>true),$rows_header->driver_name);					
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation No</label>
							<?php
								echo form_input(array('id'=>'quotation_modal','name'=>'quotation_modal','class'=>'form-control input-sm','readOnly'=>true),$rows_Quot->nomor);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation Date</label>
							<?php
								echo form_input(array('id'=>'quot_date_modal','name'=>'quot_date_modal','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_Quot->datet)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'pono_modal','name'=>'pono_modal','class'=>'form-control input-sm','readOnly'=>true),$rows_Quot->pono);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO Date</label>
							<?php
								echo form_input(array('id'=>'podate_modal','name'=>'podate_modal','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_Quot->podate)));						
							?>
						</div>
					</div>				
				</div>
				
				<?php
				$File_Preview	= '';
				$Path_Loc		= $this->file_attachement.'receive_tool/';
				$rows_image		= $this->db->get_where('quotation_driver_detail_receive_file',array('driver_detail_receive'=>$rows_detail->id))->result();
							
				if($rows_image){
					echo'
					<div class="row">
						<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
							<h5>DETAIL TOOL PICTURE</h5>
						</div>					
					</div>
					
					';
					$Arr_Label	= array('FR'=>'The Front','BC'=>'The Back','RS'=>'The Right Side','LS'=>'The Left Side');
					foreach($rows_image as $keyImage=>$valImage){
						$Code_Image		= $valImage->file_name;
						$Split_Image	= explode('-',$Code_Image);
						$Type_Code		= $Split_Image[0];
						$Notes_Image	= $valImage->notes;
						
						echo'
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>
										<strong>'.$Arr_Label[$Type_Code].' Of Tool</strong>
									</label>
									<div>
										<a href="'.$Path_Loc.$Code_Image.'" class="btn btn-sm btn-success" target ="_blank"> VIEW FILE</a>
										
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>
										<strong>Notes '.$Arr_Label[$Type_Code].'</strong>
									</label>
									<div>
										<textarea cols="75" rows="2" id="notes_'.$valImage->id.'" name="notes_'.$valImage->id.'" class="form-control" readOnly>'.$Notes_Image.'</textarea>
									</div>	
								</div>
							</div>
						</div>
						';
					}
				}
				?>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL TOOL </h5>
					</div>					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Sentral Cust Tool Code</label>
							<div class="row">
								<div class="col-sm-8 col-xs-12">
									<?php
									echo form_input(array('id'=>'code_cust_cari','name'=>'code_cust_cari','class'=>'form-control input-sm','autocomplete'=>"off"));
									echo form_input(array('id'=>'code_sentral_tool','name'=>'code_sentral_tool','type'=>'hidden'));
									?>
								</div>
								<div class="col-sm-4 col-xs-12">
									<button type="button" class="btn btn-sm btn-primary" id="btn_cari_detail"> <i class="fa fa-search"></i> SEARCH </button>
								</div>							
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tool Name</label>
							<?php
								echo form_input(array('id'=>'tool_name','name'=>'tool_name','class'=>'form-control input-sm','autocomplete'=>"off",'readOnly'=>true),$rows_detail->tool_name);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Code Tool</label>
							<?php
								echo form_input(array('id'=>'tool_id','name'=>'tool_id','class'=>'form-control input-sm','readOnly'=>true),$rows_detail->tool_id);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tool Identification No <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'no_identifikasi','name'=>'no_identifikasi','class'=>'form-control input-sm','autocompete'=>'off'));								
							?>
								
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tool Serial Number No <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'no_serial_number','name'=>'no_serial_number','class'=>'form-control input-sm','autocompete'=>'off'));								
							?>
							
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tool Merk <span class="text-red"> *</span></label>
							<?php
								echo form_input(array('id'=>'merk_alat','name'=>'merk_alat','class'=>'form-control input-sm','autocompete'=>'off'));							
							?>
							
						</div>
					</div>
									
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tool Type</label>
							<?php
								echo form_input(array('id'=>'tipe_alat','name'=>'tipe_alat','class'=>'form-control input-sm','autocompete'=>'off'));						
							?>
							
						</div>
						</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Notes </label>
							<?php
								echo form_textarea(array('id'=>'descr', 'name'=>'descr','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm'));								
							?>
							
						</div>
					</div>
									
				</div>
				
			<?php
			}
		echo'</div>';
		
		if(!empty($rows_detail)){			
			echo'
			<div class="box-body">
				<div class="row col-md-2 col-md-offset-5" id="loader_proses_save">
					<div class="loader">
						<span></span>
						<span></span>
						<span></span>
						<span></span>
					</div>
				</div>
			</div>
			<div class="box-footer">			
				&nbsp;&nbsp;&nbsp;<button type="button" id="btn_process_update" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>
			</div>';
			
		}
		
		?>
		
	</div>
</form>
<style>
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
	$(document).ready(function(){
		$('#loader_proses_save').hide();
	});
</script>