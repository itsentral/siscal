
<div class="box box-warning">		
	<div class="box-body">			
		<?php
		if(empty($rows_sentral)){
			echo"<div class='row'>
					<div class='col-sm-12'>
						<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
					</div>
				</div>";
		}else{
			
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
						<?php
						echo form_input(array('id'=>'code_sentral','name'=>'code_sentral','class'=>'form-control input-sm','readOnly'=>true),$rows_sentral->sentral_tool_code);
						
						?>
							
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tool Name</label>
						<?php
							echo form_input(array('id'=>'tool_name','name'=>'tool_name','class'=>'form-control input-sm','autocomplete'=>"off",'readOnly'=>true),$rows_rec_det->tool_name);						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Code Tool</label>
						<?php
							echo form_input(array('id'=>'tool_id','name'=>'tool_id','class'=>'form-control input-sm','readOnly'=>true),$rows_rec_det->tool_id);						
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tool Identification No <span class="text-red"> *</span></label>
						<?php
							echo form_input(array('id'=>'no_identifikasi','name'=>'no_identifikasi','class'=>'form-control input-sm','readOnly'=>true),$rows_sentral->no_identifikasi);								
						?>
							
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tool Serial Number No <span class="text-red"> *</span></label>
						<?php
							echo form_input(array('id'=>'no_serial_number','name'=>'no_serial_number','class'=>'form-control input-sm','readOnly'=>true),$rows_sentral->no_serial_number);								
						?>
						
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tool Merk <span class="text-red"> *</span></label>
						<?php
							echo form_input(array('id'=>'merk_alat','name'=>'merk_alat','class'=>'form-control input-sm','readOnly'=>true),$rows_sentral->merk);							
							?>
						
					</div>
				</div>
								
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tool Type</label>
						<?php
							echo form_input(array('id'=>'tipe_alat','name'=>'tipe_alat','class'=>'form-control input-sm','readOnly'=>true),$rows_sentral->tool_type);						
						?>
						
					</div>
					</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Notes </label>
						<?php
							echo form_textarea(array('id'=>'descr', 'name'=>'descr','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_rec_det->descr);								
						?>
						
					</div>
				</div>
								
			</div>
			<?php
			$File_Preview	= '';
			$Path_Loc		= $this->file_attachement.'receive_tool/';
						
			if($rows_rec_file){
				echo'
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>TOOL PICTURE</h5>
					</div>					
				</div>
				
				';
				$Arr_Label	= array('FR'=>'The Front','BC'=>'The Back','RS'=>'The Right Side','LS'=>'The Left Side');
				foreach($rows_rec_file as $keyImage=>$valImage){
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
			
			
		<?php
		}
	echo'</div>';
		
		?>
		
</div>