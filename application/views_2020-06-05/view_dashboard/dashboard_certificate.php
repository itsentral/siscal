<?php
$this->load->view('include/side_menu'); 
?> 
<div class="box box-success box-solid">
	<div class="box-header">
		<h3 class="box-title">
			<i class="fa fa-certificate"></i> <?php echo('<span class="important">Dashboard Certificate (<b>'.date('H:i').'</b>)</span>'); ?>
		</h3>
	</div>
	<div class="box-body">			
		<div class='form-group row'>					
			<div class='col-md-4'>
				<div class="info-box bg-aqua">
					<span class="info-box-icon">
						<i class="fa fa-certificate"></i>
					</span>
					<div class="info-box-content">
						<span class="info-box-text">Certificate Unuploaded (INTERNAL)</span>
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
				<div class="info-box bg-purple">
					<span class="info-box-icon">
						<i class="fa fa-certificate"></i>
					</span>
					<div class="info-box-content">
						<span class="info-box-text">Certificate Unuploaded  (SUBCON)</span>
						<span class="info-box-number" id='subcon_sertifikat'>
						<?php 
						$subcon_sertifikat	="<a href='#' onClick='return view_sertifikat(\"2\");' style='color:white !important' id='link_subcon_sertifikat'>".number_format($rows_data['subcon_sertifikat'])."</a>";
						echo $subcon_sertifikat; 
						
						?>						
						</span>
					</div>
				</div>
			</div>
			<div class='col-md-4'>
				<div class="info-box bg-maroon">
					<span class="info-box-icon">
						<i class="fa fa-send"></i>
					</span>
					<div class="info-box-content">
						<span class="info-box-text">Late Send Certificate (INTERNAL)</span>
						<span class="info-box-number" id='kirim_sertifikat'>
						<?php 
						$kirim_sertifikat	="<a href='#' onClick='return view_sertifikat(\"3\");' style='color:white !important' id='link_kirim_sertifikat'>".number_format($rows_data['kirim_sertifikat'])."</a>";
						echo $kirim_sertifikat; 
						
						?>						
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class='form-group row'>
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
	var active_controller	= 'Dashboard_certificates';
	$(document).ready(function(){
		//ambil_data_dashboard();
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
				var tot_reminder		= parseInt(datas.total_reminder);
				var upload_sertifikat	= parseInt(datas.upload_sertifikat);
				var subcon_sertifikat	= parseInt(datas.subcon_sertifikat);
				var kirim_sertifikat	= parseInt(datas.kirim_sertifikat);
				
				$('#link_remind_sertifikat').text(tot_reminder);
				$('#link_subcon_sertifikat').text(subcon_sertifikat);
				$('#link_kirim_sertifikat').text(kirim_sertifikat);
				
			}, 
			'error'		: function(data){
				close_spinner_new();				
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
					var ket='List Certificate Unuploaded (INTERNAL)';
				}else if(id==2){
					var ket='List Certificate Unuploaded (SUBCON)';
				}else if(id==3){
					var ket='Late Send Certificate (INTERNAL)';
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
	
	
	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>
