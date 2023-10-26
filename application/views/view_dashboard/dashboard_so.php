<?php
$this->load->view('include/side_menu'); 
?> 
<div class="box box-warning">
	<div class="box-header">
		<h3 class="box-title">
			<i class="fa fa-plus"></i> <?php echo('<span class="important">Sales Order (<b>'.date('H:i').'</b>)</span>'); ?>
		</h3>
	</div>
	<div class="box-body">
		<div class="box box-primary box-solid">
			<div class="box-header">
				<h3 class="box-title">Periode <?php echo date('F Y');?></h3>
			</div>
			<div class="box-body ">
				<div class='form-group row'>
					<div class='col-md-6'>
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
					
					<div class='col-md-6'>
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
	var active_controller	= 'Dashboard_order';
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
				var total_order			= parseInt(datas.total_order);
				
				
				$('#link_total_order').text(total_order+' Juta');
				
			}, 
			'error'		: function(data){
				close_spinner_new();				
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
	
	
	
	
	
	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>
