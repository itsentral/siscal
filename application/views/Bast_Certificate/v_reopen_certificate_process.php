
<form action="#" method="POST" id="form-proses-reopen" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 text-center sub-heading" style="color:white;">
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
							<label class="control-label">Nama Alat</label>
							<?php
								echo form_input(array('id'=>'tool_name','name'=>'tool_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->tool_name);	
								echo form_input(array('id'=>'code_detail','name'=>'code_detail','type'=>'hidden'),$rows_detail[0]->id);
								echo form_input(array('id'=>'code_so','name'=>'code_so','type'=>'hidden'),$rows_header[0]->letter_order_id);	
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Merk</label>
							<?php
								echo form_input(array('id'=>'merk','name'=>'merk','class'=>'form-control input-sm','readOnly'=>true),$rows_detail[0]->merk);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tipe</label>
							<?php
								echo form_input(array('id'=>'tool_tipe','name'=>'tool_tipe','class'=>'form-control input-sm','readOnly'=>true),$rows_detail[0]->tool_type);					
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Supplier</label>
							<?php
								echo form_input(array('id'=>'supplier','name'=>'supplier','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->supplier_name);							
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Status Kalibrasi</label>
							<div>
							<?php
								$Status	="<span class='badge bg-green'>PROCESSED</span>";
								if($rows_detail[0]->flag_proses !='Y'){
									$Status	="<span class='badge bg-maroon'>CANCEL / FAILED</span>";
								}
								echo $Status;						
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Keterangan</label>
							<?php
								echo form_input(array('id'=>'keterangan','name'=>'keterangan','class'=>'form-control input-sm','readOnly'=>true),$rows_detail[0]->keterangan);						
							?>
						</div>
					</div>				
				</div>
				<?php
				if($rows_detail[0]->flag_proses == 'Y'){
				?>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">No Identifikasi</label>
							<?php
								echo form_input(array('id'=>'no_identifikasi','name'=>'no_identifikasi','class'=>'form-control input-sm','readOnly'=>true),$rows_detail[0]->no_identifikasi);								
							?>
							
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tgl Kalibrasi</label>
							<?php
								echo form_input(array('id'=>'tgl_proses','name'=>'tgl_proses','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_detail[0]->datet)));								
							?>
							
						</div>
					</div>				
				</div>
				
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Jam Kalibrasi</label>
							<?php
								echo form_input(array('id'=>'jam_proses','name'=>'jam_proses','class'=>'form-control input-sm','readOnly'=>true),$rows_detail[0]->start_time.' - '.$rows_detail[0]->end_time);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">							
							<?php
							if($rows_header[0]->subcon =='N'){
								$label		= 'Teknisi';
								$val_Aktual	= $rows_detail[0]->actual_teknisi_name;
							}else{
								$label		= 'Subcon';
								$val_Aktual	= $rows_detail[0]->actual_subcon_name;
							}
							echo'<label class="control-label">Aktual '.$label.'</label>';
							echo form_input(array('id'=>'teknisi','name'=>'teknisi','class'=>'form-control input-sm','readOnly'=>true),$val_Aktual);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">No Sertifikat</label>
							<?php
								echo form_input(array('id'=>'sertifikat','name'=>'sertifikat','class'=>'form-control input-sm','readOnly'=>true),$rows_detail[0]->no_sertifikat);								
							?>
							
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tgl Valid Sertifikat</label>
							<?php
								echo form_input(array('id'=>'valid_until','name'=>'valid_until','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_detail[0]->valid_until)));								
							?>
							
						</div>
					</div>				
				</div>
				
				<?php
					if($akses_menu['download'] =='1' && $rows_detail[0]->approve_certificate == 'APV'){
						echo'
						<div class="row">
							<div class="col-sm-6">
								<label class="control-label">File Sertifikat</label>
								<div>
									<a href="'.$this->file_attachement.'Entries/preview_file/sertifikat/'.$rows_detail[0]->file_name.'" target ="_blank" class="btn btn-sm bg-navy-active">VIEW CERTIFICATE</a>
									
								</div>
							</div>
							<div class="col-sm-6">&nbsp;</div>
						</div>
						';
					}
				}
				?>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL QUOTATION & SO</h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Quotation</label>
							<?php
								echo form_input(array('id'=>'nomor_quotation','name'=>'nomor_quotation','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->quotation_nomor);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tgl Quotation</label>
							<?php
								echo form_input(array('id'=>'tgl_quotation','name'=>'tgl_quotation','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header[0]->quotation_date)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Customer</label>
							<?php
								echo form_input(array('id'=>'customer','name'=>'customer','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->customer_name);								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">PO No</label>
							<?php
								echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->pono);						
							?>
						</div>
					</div>				
				</div>
				
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Nomor SO</label>
							<?php
								echo form_input(array('id'=>'nomor_so','name'=>'nomor_so','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->no_so);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tanggal SO</label>
							<?php
								echo form_input(array('id'=>'tgl_so','name'=>'tgl_so','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header[0]->tgl_so)));						
							?>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL REOPEN</h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Reopen Reason</label>
							<?php
								echo form_textarea(array('id'=>'reopen_reason', 'name'=>'reopen_reason','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm'));						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">File Sertifikat</label>
							<input type="file" id="lampiran_reopen" name="lampiran_reopen" class="form-control input-sm" onchange="ValidateSingleInput(this);">
							
						</div>
					</div>				
				</div>
			<?php
			}
			?>
										
		</div>		
		<?php
		if(!empty($rows_detail)){
			echo"<div class='footer'>";
				echo'<button type="button" id="btn-process-reopen" class="btn btn-md" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="SAVE PROCESS"> SAVE PROCESS <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>';
			echo"</div>";
		}
		?>
		
	</div>
</form>

<style>
	.sub-heading{
		border-radius :5px;
		background-color :#03506F;
		color : white;
		margin : 20px 10px 15px 10px !important;
		width :98% !important;
	}
</style>
