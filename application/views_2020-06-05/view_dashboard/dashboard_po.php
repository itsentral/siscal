<?php
$this->load->view('include/side_menu'); 
?> 
<div class="box box-danger box-solid">
	<div class="box-header">
		<h3 class="box-title">
			<i class="fa fa-certificate"></i> <?php echo('<span class="important">Dashboard Incomplete PO (<b>'.date('H:i').'</b>)</span>'); ?>
		</h3>
	</div>
	<div class="box-body">
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
	var active_controller	= 'Dashboard_purchase';
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
				var incomplete_bast		= parseInt(datas.total_incomplete_delivery);
				var incomplete_quot		= parseInt(datas.total_incomplete_quot);
				var incomplete_rec		= parseInt(datas.total_incomplete_receive);
				
				$('#link_other_incomplete_bast').text(incomplete_bast);
				$('#link_other_incomplete_quot').text(incomplete_quot);
				$('#link_other_outs_order').text(incomplete_rec);
				
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
		if(kode==1){
			var ket	= 'List Incomplte Quotation PO';
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
