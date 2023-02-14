
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
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
						<h5>DETAIL APPROVAL SERTIFIKAT</h5>
					</div>
					
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Approval Status</label>
							<div>
							<?php
							$Tgl_Apv		= $Approve_By = '-';
							$Status_Apv	="<span class='badge bg-green'>WAITING APPROVAL</span>";
							if($rows_detail[0]->approve_certificate =='APV'){
								$Status_Apv	="<span class='badge bg-maroon'>APPROVED</span>";
								$Tgl_Apv	= date('d F Y',strtotime($rows_detail[0]->approve_date));
								$Approve_By	= $rows_detail[0]->approve_by;
							}else if($rows_detail[0]->approve_certificate =='REV'){
								$Status_Apv	="<span class='badge bg-yellow'>WAITING REVISION</span>";
							}
							echo $Status_Apv;								
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tgl Approve</label>
							<?php
								echo form_input(array('id'=>'tgl_approve','name'=>'tgl_approve','class'=>'form-control input-sm','readOnly'=>true),$Tgl_Apv);								
							?>
							
						</div>
					</div>	
				</div>
				<div class="row">
					<div class="col-sm-6">
						<label class="control-label">Approve By</label>
						<?php
							echo form_input(array('id'=>'by_approve','name'=>'by_approve','class'=>'form-control input-sm','readOnly'=>true),$Approve_By);								
						?>
					</div>
					<div class="col-sm-6">&nbsp;</div>
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
							<label class="control-label">Nama PIC</label>
							<?php
								echo form_input(array('id'=>'pic_quotation','name'=>'pic_quotation','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->quotation_pic);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Alamat</label>
							<?php
								echo form_textarea(array('id'=>'alamat','name'=>'alamat','class'=>'form-control input-sm','readOnly'=>true,'cols'=>75,'rows'=>2),$rows_header[0]->quotation_address);								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Marketing</label>
							<?php
								echo form_input(array('id'=>'marketing','name'=>'marketing','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->marketing_name);						
							?>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL SO</h5>
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
						<h5>DETAIL SCHEDULE</h5>
					</div>
					
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Nomor Schedule</label>
							<?php
								echo form_input(array('id'=>'schedule','name'=>'schedule','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->schedule_nomor);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Tgl Quotation</label>
							<?php
								echo form_input(array('id'=>'tgl_schedule','name'=>'tgl_schedule','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header[0]->schedule_date)));						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Kategori</label>
							<div>
							<?php
								$kategori	='Labs';
								if($rows_header[0]->insitu =='Y'){
									$kategori	='Insitu';
								}else if($rows_header[0]->subcon =='Y'){
									$kategori	='Subcon';
								}
								echo "<span class='badge bg-purple'>".$kategori."</span>"; 					
							?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">&nbsp;</div>				
				</div>
			<?php
				if($rows_header[0]->subcon !=='Y'){
					echo'
					<div class="row">
						<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
							<h5>DETAIL SPK TEKNISI</h5>
						</div>
						
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">SPK Teknisi</label>
								'.form_input(array('id'=>'spk_teknisi','name'=>'spk_teknisi','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->teknisi_id.'-'.date('Ymd',strtotime($rows_header[0]->plan_process_date))).'
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Tgl SPK</label>
								'.form_input(array('id'=>'tgl_spk','name'=>'tgl_spk','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header[0]->plan_process_date))).'
							</div>
						</div>				
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Teknisi ID</label>
								'.form_input(array('id'=>'code_teknisi','name'=>'code_teknisi','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->teknisi_id).'
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Nama Teknisi</label>
								'.form_input(array('id'=>'nama_teknisi','name'=>'nama_teknisi','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->teknisi_name).'
							</div>
						</div>				
					</div>
					';
				}
				
				if($rows_header[0]->insitu !=='Y'){
					$Method		= 'Alat Diambil Oleh Driver';
					$Driv_Rec	= $records['TransDetail']['receiving_by'];
					if($rows_header[0]->get_tool =='Customer'){
						$Method		= 'Alat Diantar Oleh Customer';
						$Driv_Rec	= '-';
					}
					
					$send_driver	= '-';
					$send_date		= '-';
					if($rows_header[0]->bast_send_date){
						$send_date	= date('d F Y',strtotime($rows_header[0]->bast_send_date));	
					}
					if($rows_header[0]->bast_send_by){
						$send_driver	= $rows_header[0]->bast_send_by;	
					}
					echo'
					<div class="row">
						<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
							<h5>DETAIL BAST AMBIL/TERIMA ALAT</h5>
						</div>
						
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Keterangan</label>
								<div>
									<span class="badge bg-maroon">'.$Method.'</span>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">No BAST</label>
								'.form_input(array('id'=>'nobast_rec','name'=>'nobast_rec','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->bast_rec_no).'
							</div>
						</div>				
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Tgl BAST</label>
								'.form_input(array('id'=>'tgl_nobast_rec','name'=>'tgl_nobast_rec','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y',strtotime($rows_header[0]->bast_rec_date))).'
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Driver</label>
								'.form_input(array('id'=>'driver_rec','name'=>'driver_rec','class'=>'form-control input-sm','readOnly'=>true),$Driv_Rec).'
							</div>
						</div>				
					</div>					
					<div class="row">
						<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
							<h5>DETAIL BAST KIRIM ALAT</h5>
						</div>
						
					</div>
					<div class="row">						
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">No BAST</label>
								'.form_input(array('id'=>'nobast_send','name'=>'nobast_send','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->bast_send_no).'
							</div>
						</div>	
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Tgl BAST</label>
								'.form_input(array('id'=>'tgl_nobast_send','name'=>'tgl_nobast_send','class'=>'form-control input-sm','readOnly'=>true),$send_date).'
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Driver</label>
								'.form_input(array('id'=>'driver_send','name'=>'driver_send','class'=>'form-control input-sm','readOnly'=>true),$send_driver).'
							</div>
						</div>
						<div class="col-sm-6">&nbsp;</div>						
					</div>
					';
				
				}
				if($rows_header[0]->subcon =='Y'){
					$send_date	= $send_driver = $rec_date	= $rec_driver = '-';
					if($rows_header[0]->subcon_bast_send_date){
						$send_date	= date('d F Y',strtotime($rows_header[0]->subcon_bast_send_date));	
					}
					if($rows_header[0]->subcon_sending_by){
						$send_driver	= $rows_header[0]->subcon_sending_by;	
					}
					
					if($rows_header[0]->subcon_bast_rec_date){
						$rec_date	= date('d F Y',strtotime($rows_header[0]->subcon_bast_rec_date));	
					}
					if($rows_header[0]->subcon_receiving_by){
						$rec_driver	= $rows_header[0]->subcon_receiving_by;	
					}
					echo'
					<div class="row">
						<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
							<h5>BAST KIRIM ALAT ~ SUBCON</h5>
						</div>
						
					</div>
					<div class="row">						
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">No BAST</label>
								'.form_input(array('id'=>'nobast_sub_send','name'=>'nobast_sub_send','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->subcon_bast_send_no).'
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Tgl BAST</label>
								'.form_input(array('id'=>'tgl_nobast_sub_send','name'=>'tgl_nobast_sub_send','class'=>'form-control input-sm','readOnly'=>true),$send_date).'
							</div>
						</div>
					</div>
					<div class="row">						
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Driver</label>
								'.form_input(array('id'=>'driver_sub_send','name'=>'driver_sub_send','class'=>'form-control input-sm','readOnly'=>true),$send_driver).'
							</div>
						</div>	
						<div class="col-sm-6">&nbsp;</div>
					</div>					
					<div class="row">
						<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
							<h5>BAST AMBIL ALAT ~ SUBCON</h5>
						</div>
						
					</div>
					<div class="row">						
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">No BAST</label>
								'.form_input(array('id'=>'nobast_sub_rec','name'=>'nobast_sub_rec','class'=>'form-control input-sm','readOnly'=>true),$rows_header[0]->subcon_bast_rec_no).'
							</div>
						</div>	
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Tgl BAST</label>
								'.form_input(array('id'=>'tgl_nobast_sub_rec','name'=>'tgl_nobast_sub_rec','class'=>'form-control input-sm','readOnly'=>true),$rec_date).'
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Driver</label>
								'.form_input(array('id'=>'driver_sub_rec','name'=>'driver_sub_rec','class'=>'form-control input-sm','readOnly'=>true),$rec_driver).'
							</div>
						</div>
						<div class="col-sm-6">&nbsp;</div>						
					</div>
					';
				}
			}
			?>
										
		</div>		
	
		
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
