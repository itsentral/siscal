<?php
$this->load->view('include/side_menu'); 
?> 
<div class="box box-info box-solid">
	<div class="box-header">
		<h3 class="box-title">
			<i class="fa fa-credit-card"></i> <?php echo('<span class="important">Dashboard Invoice Other (<b>'.date('H:i').'</b>)</span>'); ?>
		</h3>
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
	var active_controller	= 'Dashboard_inv_other';
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
				var late_inv_send		= parseInt(datas.total_late_inv_send);
				var late_inv_follow1	= parseInt(datas.total_late_inv_follow1);
				var late_inv_follow2	= parseInt(datas.total_late_inv_follow2);
				var piutang_minus  		= parseInt(datas.piutang_minus);
				
				$('#link_other_late_inv_send').text(late_inv_send);
				$('#link_other_late_inv_follow1').text(late_inv_follow1);
				$('#link_other_late_inv_follow2').text(late_inv_follow2);
				$('#link_other_piutang_minus').text(piutang_minus.format(0,3,',')+' Juta');
			}, 
			'error'		: function(data){
				close_spinner_new();				
			}
			
		});
	}
	
	
	function view_other_dashboard(kode){
		loading_spinner_new();
		$('#isi').empty();
		$('#judul').text('');
		if(kode==2){
			var ket	= 'List Invoice Late Sending';
		}else if(kode==2){
			var ket	= 'List Invoice Late First Follow Up';
		}else if(kode==4){
			var ket	= 'List Invoice Late Second Follow Up';
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
	
	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>
