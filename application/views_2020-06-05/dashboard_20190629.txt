<?php
$this->load->view('include/side_menu'); 
?> 
<div class="box box-warning">
	<div class="box-header">
		<h3 class="box-title">
			<i class="fa fa-plus"></i> <?php echo('<span class="important">Selamat Datang Di Sistem Informasi Sentral Calibration</span>'); ?>
		</h3>
	</div>
	<div class="box-body">
		<div class="box box-primary box-solid">
			<div class="box-header">
				<h3 class="box-title">Data Quotation <?php echo date('F Y');?></h3>
			</div>
			<div class="box-body ">
				<div class='form-group row'>
					
					<div class='col-md-3'>
						<div class="info-box bg-blue">
							<span class="info-box-icon">
								<i class="fa fa-calculator"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Total</span>
								<span class="info-box-number" id='quot_total'>
								<?php 
									$link	="<a href='#' onClick='return view(\"1\");' style='color:white !important' id='link_total'>".number_format($rows_data['total_quot'])." Juta</a>";
									echo $link; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="info-box bg-green">
							<span class="info-box-icon">
								<i class="fa fa-check"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Deal</span>
								<span class="info-box-number" id='quot_deal'>
									<?php 
										$link2	="<a href='#' onClick='return view(\"2\");' style='color:white !important' id='link_deal'>".number_format($rows_data['deal_quot'])." Juta</a>";
										echo $link2;
									
									?>
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="info-box bg-red">
							<span class="info-box-icon">
								<i class="fa fa-minus-square"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Fail</span>
								<span class="info-box-number" id='quot_fail'>
									<?php 
									$link3	="<a href='#' onClick='return view(\"3\");' style='color:white !important' id='link_fail'>".number_format($rows_data['fail_quot'])." Juta</a>";
									echo $link3;
									
									?>
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="info-box bg-yellow">
							<span class="info-box-icon">
								<i class="fa fa-recycle"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Cancel</span>
								<span class="info-box-number" id='quot_cancel'>
									<?php 
										$link4	="<a href='#' onClick='return view(\"4\");' style='color:white !important' id='link_cancel'>".number_format($rows_data['cancel_quot'])." Juta</a>";
										echo $link4;
									
									?>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class='form-group row'>
					<div class='col-md-3'>
						<div class="info-box bg-orange">
							<span class="info-box-icon">
								<i class="fa fa-file"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Total SO</span>
								<span class="info-box-number" id='total_so'>
								<?php 
								$total_order	="<a href='#' onClick='return view_order(1);' style='color:white !important' id='link_total_order'>".number_format($rows_data['total_order'])." Juta</a>";
								echo $total_order; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					
					<div class='col-md-3'>
						<div class="info-box bg-purple">
							<span class="info-box-icon">
								<i class="fa fa-calculator"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Cancel SO</span>
								<span class="info-box-number" id='total_cancel_so'>
								<?php 
								$cancel_order	="<a href='#' onClick='return view_order(2);' style='color:white !important' id='link_total_cancel_order'>".number_format($rows_data['total_cancel_order'])." SO</a>";
								echo $cancel_order; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
				</div>
				
			</div>			 
		</div><!-- /.box -->
		
		<div class="box box-success box-solid">
			<div class="box-header">
				<h3 class="box-title">Incomplete Data</h3>
			</div>
			<div class="box-body ">
				<div class="form-group row">
					<div class='col-md-3'>
						<div class="info-box bg-aqua">
							<span class="info-box-icon">
								<i class="fa fa-edit"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">PO Quotation</span>
								<span class="info-box-number" id='other_incomplete_quot'>
								<?php 
								$incomplete_quot	="<a href='#' onClick='return view_other_dashboard(\"1\");' style='color:white !important' id='link_other_incomplete_quot'>".number_format($rows_data['total_incomplete_quot'])."</a>";
								echo $incomplete_quot; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="info-box bg-maroon">
							<span class="info-box-icon">
								<i class="fa fa-truck"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Receive</span>
								<span class="info-box-number" id='other_outs_order'>
								<?php 
								$pick_cust_late	="<a href='#'  onClick='view_receive_orders();' style='color:white !important' id='link_other_outs_order'>".number_format($rows_data['total_incomplete_receive'])."</a>";
								echo $pick_cust_late; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="info-box bg-blue">
							<span class="info-box-icon">
								<i class="fa fa-file"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">BAST Rec/Send</span>
								<span class="info-box-number" id='other_incomplete_bast'>
								<?php 
								$incomplete_bast	="<a href='#' onClick='view_incomplete_bast();' style='color:white !important' id='link_other_incomplete_bast'>".number_format($rows_data['total_incomplete_delivery'])."</a>";
								echo $incomplete_bast; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="box box-info box-solid">
			<div class="box-header">
				<h3 class="box-title">Late Report</h3>
			</div>
			<div class="box-body ">
				<div class='form-group row'>
					<div class='col-md-3'>
						<div class="info-box bg-purple">
							<span class="info-box-icon">
								<i class="fa fa-refresh"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Calibration Process</span>
								<span class="info-box-number" id='process_late'>
									<?php 
										$process_late	="<a href='#' onClick='return view_late(\"2\");' style='color:white !important' id='link_late_process'>".number_format($rows_data['late_kalibrasi'])."</a>";
										echo $process_late;
									
									?>
								</span>
							</div>
						</div>
					</div>
					
					<div class='col-md-3'>
						<div class="info-box bg-blue">
							<span class="info-box-icon">
								<i class="fa fa-send"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Send To Subcon</span>
								<span class="info-box-number" id='late_subcon_send'>
									<?php 
										$link3	="<a href='#' onClick='return view_late(\"3\");' style='color:white !important' id='link_late_subcon_send'>".number_format($rows_data['late_kirim_subcon'])."</a>";
										echo $link3;
									
									?>
								</span>
							</div>
						</div>
					</div>
					
					<div class='col-md-3'>
						<div class="info-box bg-green">
							<span class="info-box-icon">
								<i class="fa fa-reply-all"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Pick From Subcon</span>
								<span class="info-box-number" id='late_subcon_pick'>
									<?php 
										$link4	="<a href='#' onClick='return view_late(\"4\");' style='color:white !important' id='link_late_subcon_pick'>".number_format($rows_data['late_ambil_subcon'])."</a>";
										echo $link4;
									
									?>
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="info-box bg-yellow">
							<span class="info-box-icon">
								<i class="fa fa-truck"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Send To Cust</span>
								<span class="info-box-number" id='send_cust_late'>
								<?php 
								$send_cust_late	="<a href='#' onClick='return view_late(\"5\");' style='color:white !important' id='link_send_late'>".number_format($rows_data['late_kirim_cust'])."</a>";
								echo $send_cust_late; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					
				</div>
				<div class='form-group row'>
					<div class='col-md-3'>
						<div class="info-box bg-red">
							<span class="info-box-icon">
								<i class="fa fa-refresh"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Unschedule (More Than 2 Days) </span>
								<span class="info-box-number" id='late_schedule'>
									<?php 
										$late_schedule	="<a href='#' onClick='return view_other_dashboard(\"7\");' style='color:white !important' id='link_late_schedule'>".number_format($rows_data['late_schedule'])."</a>";
										echo $late_schedule;
									
									?>
								</span>
							</div>
						</div>
					</div>
				
				</div>
			</div>			 
		</div><!-- /.box -->
		
		<div class="box box-warning box-solid">
			<div class="box-header">
				<h3 class="box-title">Sertifikat</h3>
			</div>
			<div class="box-body ">				
				<div class='form-group row'>					
					<div class='col-md-4'>
						<div class="info-box bg-aqua">
							<span class="info-box-icon">
								<i class="fa fa-certificate"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Certificate Unuploaded</span>
								<span class="info-box-number" id='upload_sertifikat'>
								<?php 
								$upload_sertifikat	="<a href='#' onClick='return view_sertifikat(\"1\");' style='color:white !important' id='link_upload_sertifikat'>".number_format($rows_data['upload_sertifikat'])."</a>";
								echo $upload_sertifikat; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-4'>
						<div class="info-box bg-blue">
							<span class="info-box-icon">
								<i class="fa fa-calendar"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Certificate Exp < <?php echo date('d M',strtotime('+1 month',strtotime(date('Y-m-d'))))?></span>
								<span class="info-box-number" id='remind_sertifikat'>
								<?php 
								$remind_sertifikat	="<a href='#' onClick='view_sertifikat_exp();' style='color:white !important' id='link_remind_sertifikat'>".number_format($rows_data['total_reminder'])."</a>";
								echo $remind_sertifikat; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-4'>
						
					</div>
				</div>
			</div>			 
		</div><!-- /.box -->
		
		<div class="box box-primary box-solid">
			<div class="box-header">
				<h3 class="box-title">Invoice</h3>
			</div>
			<div class="box-body">
				<div class="form-group row">
					<div class='col-md-3'>
						<div class="info-box bg-red">
							<span class="info-box-icon">
								<i class="fa fa-send"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Late Kirim</span>
								<span class="info-box-number" id='other_late_inv_send'>
								<?php 
								$late_send_inv	="<a href='#' onClick='return view_other_dashboard(\"2\");' style='color:white !important' id='link_other_late_inv_send'>".number_format($rows_data['total_late_inv_send'])."</a>";
								echo $late_send_inv; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="info-box bg-maroon">
							<span class="info-box-icon">
								<i class="fa fa-phone"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Late Follow Up I</span>
								<span class="info-box-number" id='other_late_inv_follow1'>
								<?php 
								$late_send_follow1	="<a href='#' onClick='return view_other_dashboard(\"3\");' style='color:white !important' id='link_other_late_inv_follow1'>".number_format($rows_data['total_late_inv_follow1'])."</a>";
								echo $late_send_follow1; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="info-box bg-yellow">
							<span class="info-box-icon">
								<i class="fa fa-phone"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Late Follow Up II</span>
								<span class="info-box-number" id='other_late_inv_follow2'>
								<?php 
								$late_send_follow2	="<a href='#' onClick='return view_other_dashboard(\"4\");' style='color:white !important' id='link_other_late_inv_follow2'>".number_format($rows_data['total_late_inv_follow2'])."</a>";
								echo $late_send_follow2; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="info-box bg-orange">
							<span class="info-box-icon">
								<i class="fa fa-money"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Piutang Minus</span>
								<span class="info-box-number" id='other_piutang_minus'>
								<?php 
								$piutang_minus	="<a href='#' onClick='return view_other_dashboard(\"12\");' style='color:white !important' id='link_other_piutang_minus'>".number_format($rows_data['piutang_minus'])." Juta</a>";
								echo $piutang_minus; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class='col-md-3'>
						<div class="info-box bg-blue">
							<span class="info-box-icon">
								<i class="fa fa-money"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Potential Bad Debt</span>
								<span class="info-box-number" id='other_potential_debt'>
								<?php 
								$potential_debt	="<a href='#' onClick='return view_other_dashboard(\"5\");' style='color:white !important' id='link_other_potential_debt'>".number_format($rows_data['total_potential_bad'])." Juta</a>";
								echo $potential_debt; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="info-box bg-aqua">
							<span class="info-box-icon">
								<i class="fa fa-money"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Bad Debt</span>
								<span class="info-box-number" id='other_bad_debt'>
								<?php 
								$bad_debt	="<a href='#' onClick='return view_other_dashboard(\"6\");' style='color:white !important' id='link_other_bad_debt'>".number_format($rows_data['total_bad_debt'])." Juta</a>";
								echo $bad_debt; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="info-box bg-green">
							<span class="info-box-icon">
								<i class="fa fa-money"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Potential Bad Debt PPH 23</span>
								<span class="info-box-number" id='other_potential_debt_pph'>
								<?php 
								$potential_bad_pph	="<a href='#' onClick='return view_other_dashboard(\"8\");' style='color:white !important' id='link_other_potential_debt_pph'>".number_format($rows_data['total_potential_bad_pph'])." Juta</a>";
								echo $potential_bad_pph; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="info-box bg-lime">
							<span class="info-box-icon">
								<i class="fa fa-money"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Bad Debt PPH 23</span>
								<span class="info-box-number" id='other_bad_debt_pph'>
								<?php 
								$bad_debt_pph	="<a href='#' onClick='return view_other_dashboard(\"9\");' style='color:white !important' id='link_other_bad_debt_pph'>".number_format($rows_data['total_bad_debt_pph'])." Juta</a>";
								echo $bad_debt_pph; 
								
								?>						
								</span>
							</div>
						</div>
					</div>					
				</div>
				<div class="form-group row">
					<div class='col-md-3'>
						<div class="info-box bg-green">
							<span class="info-box-icon">
								<i class="fa fa-money"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Potential Bad Debt PPN</span>
								<span class="info-box-number" id='other_potential_debt_ppn'>
								<?php 
								$potential_bad_ppn	="<a href='#' onClick='return view_other_dashboard(\"10\");' style='color:white !important' id='link_other_potential_debt_ppn'>".number_format($rows_data['total_potential_bad_ppn'])." Juta</a>";
								echo $potential_bad_ppn; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="info-box bg-yellow">
							<span class="info-box-icon">
								<i class="fa fa-money"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">Bad Debt PPN</span>
								<span class="info-box-number" id='other_bad_debt_ppn'>
								<?php 
								$bad_debt_ppn	="<a href='#' onClick='return view_other_dashboard(\"11\");' style='color:white !important' id='link_other_bad_debt_ppn'>".number_format($rows_data['total_bad_debt_ppn'])." Juta</a>";
								echo $bad_debt_ppn; 
								
								?>						
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>			 
		</div>		
	</div>		
</div>
<div class="modal fade" id="MyQuotation">
	<div class="modal-dialog" style="width:85% !important">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="judul"></h4>
			</div>
			<div class="modal-body" id="isi">
      	
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="MyLate">
	<div class="modal-dialog" style="width:85% !important">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="judul_late"></h4>
			</div>
			<div class="modal-body" id="isi_late">
      	
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>	
</div>	
<?php $this->load->view('include/footer'); ?>
<script>
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= 'Dashboard';
	$(document).ready(function(){
		ambil_data_dashboard();
		//setInterval(ambil_data_dashboard,500000);		 
	});
	function ambil_data_dashboard(){
		loading_spinner_new();
		var baseurl	= base_url+active_controller+'/json_dashboard';
		$.ajax({
			'url'		: baseurl,
			'type'		: 'get', 
			'success'	: function(data){
				close_spinner_new();
				var datas				= $.parseJSON(data);
				var total_quot			= parseInt(datas.total_quot);
				var deal				= parseInt(datas.deal_quot);
				var fail				= parseInt(datas.fail_quot);
				var cancel				= parseInt(datas.cancel_quot);
				var tot_reminder		= parseInt(datas.total_reminder);
				var total_order			= parseInt(datas.total_order);
				var incomplete_bast		= parseInt(datas.total_incomplete_delivery);
				var incomplete_quot		= parseInt(datas.total_incomplete_quot);
				var incomplete_rec		= parseInt(datas.total_incomplete_receive);
				var late_inv_send		= parseInt(datas.total_late_inv_send);
				var late_inv_follow1	= parseInt(datas.total_late_inv_follow1);
				var late_inv_follow2	= parseInt(datas.total_late_inv_follow2);
				var potential_bad  		= parseInt(datas.total_potential_bad);
				var bad_debt	  		= parseInt(datas.total_bad_debt);
				var upload_sertifikat	= parseInt(datas.upload_sertifikat);
				var late_kalibrasi		= parseInt(datas.late_kalibrasi);
				var late_ambil_subcon	= parseInt(datas.late_ambil_subcon);
				var late_kirim_subcon	= parseInt(datas.late_kirim_subcon);
				var late_kirim_cust	 	= parseInt(datas.late_kirim_cust);
				var late_schedule		= parseInt(datas.late_schedule);
				var potential_bad_pph  	= parseInt(datas.total_potential_bad_pph);
				var bad_debt_pph  		= parseInt(datas.total_bad_debt_pph);
				var potential_bad_ppn  	= parseInt(datas.total_potential_bad_ppn);
				var bad_debt_ppn  		= parseInt(datas.total_bad_debt_ppn);
				var piutang_minus  		= parseInt(datas.piutang_minus);
				
				$('#link_total').text(total_quot.format(0,3,',')+' Juta');
				$('#link_deal').text(deal.format(0,3,',')+' Juta');
				$('#link_fail').text(fail.format(0,3,',')+' Juta');
				$('#link_cancel').text(cancel.format(0,3,',')+' Juta');
				$('#link_remind_sertifikat').text(tot_reminder);
				$('#link_total_order').text(total_order+' Juta');
				$('#link_other_incomplete_bast').text(incomplete_bast);
				$('#link_other_incomplete_quot').text(incomplete_quot);
				$('#link_other_outs_order').text(incomplete_rec);
				$('#link_other_late_inv_send').text(late_inv_send);
				$('#link_other_late_inv_follow1').text(late_inv_follow1);
				$('#link_other_late_inv_follow2').text(late_inv_follow2);
				$('#link_other_potential_debt').text(potential_bad.format(0,3,',')+' Juta');
				$('#link_other_bad_debt').text(bad_debt.format(0,3,',')+' Juta');
				$('#link_upload_sertifikat').text(upload_sertifikat);
				$('#link_send_late').text(late_kirim_cust);
				$('#link_late_process').text(late_kalibrasi);
				$('#link_late_subcon_pick').text(late_ambil_subcon);
				$('#link_late_subcon_send').text(late_kirim_subcon);
				$('#link_late_schedule').text(late_schedule);
				$('#link_other_potential_debt_pph').text(potential_bad_pph.format(0,3,',')+' Juta');
				$('#link_other_bad_debt_pph').text(bad_debt_pph.format(0,3,',')+' Juta');
				$('#link_other_potential_debt_ppn').text(potential_bad_ppn.format(0,3,',')+' Juta');
				$('#link_other_bad_debt_ppn').text(bad_debt_ppn.format(0,3,',')+' Juta');
				$('#link_other_piutang_minus').text(piutang_minus.format(0,3,',')+' Juta');
			}, 
			'error'		: function(data){
				close_spinner_new();				
			}
			
		});
	}
	function view(id){
		loading_spinner_new();
		$('#isi').empty();
		$('#judul').text('');
		
		var baseurl	= base_url+active_controller+'/getdetail/'+id;
		$.ajax({
			'url'		: baseurl,
			'type'		: 'get', 
			'success'	: function(data){
				close_spinner_new();
				if(id==1){
					var ket='Daftar Total Quotaion';
				}else if(id==2){
					var ket='List Deal Quotation';
				}else if(id==3){
					var ket='List Fail Quotation';
				}else if(id==4){
					var ket='List Cancel Quotation';
				}
				$('#spinner').modal('hide');
				$('#judul').text(ket);
				$('#isi').html(data);
				$('#MyQuotation').modal('show');
			}, 
			'error'		: function(data){
				close_spinner_new();
				alert('An error occured, please try again.');
			}
		});
	}
	
	function view_late(id){
		loading_spinner_new();
		$('#isi_late').empty();
		$('#judul_late').text('');
		
		var baseurl=base_url+active_controller+'/getlatedata/'+id;
		$.ajax({
			'url'		: baseurl,
			'type'		: 'get', 
			'success'	: function(data){
				close_spinner_new();
				if(id==2){
					var ket='List Late Calibration Process';
				}else if(id==3){
					var ket='List Late Send Tool To Subcont';
				}else if(id==4){
					var ket='List Late Pick Tool From Subcont';
				}else if(id==5){
					var ket='List Late Send Tool To Cust';
				}
			
				$('#judul_late').text(ket);
				$('#isi_late').html(data);
				$('#MyLate').modal('show');
			}, 
			'error'		: function(data){
				close_spinner_new();
				alert('An error occured, please try again.');
			}
		});
	}
	
	function view_sertifikat(id){
		loading_spinner_new();
		$('#isi_late').empty();
		$('#judul_late').text('');
		
		var baseurl=base_url+active_controller+'/getcertificatedata/'+id;
		$.ajax({
			'url'		: baseurl,
			'type'		: 'get', 
			'success'	: function(data){
				close_spinner_new();
				if(id==1){
					var ket='List Certificate Unuploaded';
				}
			
				$('#judul_late').text(ket);
				$('#isi_late').html(data);
				$('#MyLate').modal('show');
			}, 
			'error'		: function(data){
				close_spinner_new();
				alert('An error occured, please try again.');
			}
		});
	}
	
	function view_other_dashboard(kode){
		loading_spinner_new();
		$('#isi').empty();
		$('#judul').text('');
		if(kode==1){
			var ket	= 'List Incomplte Quotation PO';
		}else if(kode==2){
			var ket	= 'List Invoice Late Sending';
		}else if(kode==2){
			var ket	= 'List Invoice Late First Follow Up';
		}else if(kode==4){
			var ket	= 'List Invoice Late Second Follow Up';
		}else if(kode==5){
			var ket	= 'List Invoice Potential Bad Debt';
		}else if(kode==6){
			var ket	= 'List Invoice Bad Debt';
		}else if(kode==7){
			var ket	= 'List Late Schedule Process';
		}else if(kode==8){
			var ket	= 'List Piutang PPH 23 Potential Bad Debt';
		}else if(kode==9){
			var ket	= 'List Piutang PPH 23 Bad Debt';
		}else if(kode==10){
			var ket	= 'List Piutang PPN Potential Bad Debt';
		}else if(kode==11){
			var ket	= 'List Piutang PPN Bad Debt';
		}else if(kode==12){
			var ket	= 'List Piutang Minus';
		}
		
		
		var baseurl=base_url+active_controller+'/get_other_dashboard/'+kode;
		$.ajax({
			'url'		: baseurl,
			'type'		: 'get', 
			'success'	: function(data){
				close_spinner_new();
				$('#judul').text(ket);
				$('#isi').html(data);
				$('#MyQuotation').modal('show');
			}, 
			'error'		: function(data){
				close_spinner_new();
				alert('An error occured, please try again.');
			}
		});
	}
	
	function view_order(kode){
		loading_spinner_new();
		$('#isi').empty();
		$('#judul').text('');
		
		var baseurl=base_url+active_controller+'/getdataorder/'+kode;
		$.ajax({
			'url'		: baseurl,
			'type'		: 'get', 
			'success'	: function(data){
				close_spinner_new();
				var ket	= 'List Sales Order';
				$('#judul').text(ket);
				$('#isi').html(data);
				$('#MyQuotation').modal('show');
			}, 
			'error'		: function(data){
				close_spinner_new();
				alert('An error occured, please try again.');
			}
		});
	}
	
	function view_sertifikat_exp(){
		loading_spinner_new();
		$('#isi').empty();
		$('#judul').text('');
		
		var baseurl=base_url+active_controller+'/get_sertifikat_exp';
		$.ajax({
			'url'		: baseurl,
			'type'		: 'get', 
			'success'	: function(data){
				close_spinner_new();
				var ket	= 'List Reminder Sertifikat';
				$('#judul').text(ket);
				$('#isi').html(data);
				$('#MyQuotation').modal('show');
			}, 
			'error'		: function(data){
				close_spinner_new();
				alert('An error occured, please try again.');
			}
		});
	}
	
	function view_incomplete_bast(){
		loading_spinner_new();
		$('#isi').empty();
		$('#judul').text('');
		
		var baseurl=base_url+active_controller+'/get_incomplete_bast';
		$.ajax({
			'url'		: baseurl,
			'type'		: 'get', 
			'success'	: function(data){
				close_spinner_new();
				var ket	= 'List Incomplete BAST';
				$('#judul').text(ket);
				$('#isi').html(data);
				$('#MyQuotation').modal('show');
			}, 
			'error'		: function(data){
				close_spinner_new();
				alert('An error occured, please try again.');
			}
		});
	}
	
	function view_receive_orders(){
		loading_spinner_new();
		$('#isi').empty();
		$('#judul').text('');
		
		var baseurl=base_url+active_controller+'/get_incomplete_receive';
		$.ajax({
			'url'		: baseurl,
			'type'		: 'get', 
			'success'	: function(data){
				close_spinner_new();
				var ket	= 'List Incomplete Receive Tool';
				$('#judul').text(ket);
				$('#isi').html(data);
				$('#MyQuotation').modal('show');
			}, 
			'error'		: function(data){
				close_spinner_new();
				alert('An error occured, please try again.');
			}
		});
	}
	
	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>
