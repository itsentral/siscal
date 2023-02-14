<?php
$this->load->view('include/side_menu'); 
?> 
<div class="box box-info box-solid">
	<div class="box-header">
		<h3 class="box-title">
			<i class="fa fa-credit-card"></i> <?php echo('<span class="important">Dashboard Potential Bad Debt (<b>'.date('H:i').'</b>)</span>'); ?>
		</h3>
	</div>
	<div class="box-body">
		
		<div class="form-group row">
			<div class='col-md-4'>
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
			
			<div class='col-md-4'>
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
			<div class='col-md-4'>
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
	var active_controller	= 'Dashboard_invoice';
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
				var potential_bad  		= parseInt(datas.total_potential_bad);
				var potential_bad_pph  	= parseInt(datas.total_potential_bad_pph);
				var potential_bad_ppn  	= parseInt(datas.total_potential_bad_ppn);
				
				$('#link_other_potential_debt').text(potential_bad.format(0,3,',')+' Juta');
				$('#link_other_potential_debt_pph').text(potential_bad_pph.format(0,3,',')+' Juta');
				$('#link_other_potential_debt_ppn').text(potential_bad_ppn.format(0,3,',')+' Juta');
				
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
		}else if(kode==5){
			var ket	= 'List Invoice Potential Bad Debt';
		}else if(kode==6){
			var ket	= 'List Invoice Bad Debt';
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
	
	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>
