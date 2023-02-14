<?php

$this->load->view('include/side_menu'); 
?> 
<div class="box box-warning">
	<div class="box-header">
		<h3 class="box-title">
			<i class="fa fa-plus"></i> <?php echo('<span class="important">Dashboard Quotaion (<b>'.date('H:i').'</b>)</span>'); ?>
		</h3>
	</div>
	<div class="box-body">
		<div class="box box-primary box-solid">
			<div class="box-header">
				<h3 class="box-title">Periode <?php echo date('F Y');?></h3>
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
	var active_controller	= 'Dashboard';
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
				var total_quot			= parseInt(datas.total_quot);
				var deal				= parseInt(datas.deal_quot);
				var fail				= parseInt(datas.fail_quot);
				var cancel				= parseInt(datas.cancel_quot);
				
				
				$('#link_total').text(total_quot.format(0,3,',')+' Juta');
				$('#link_deal').text(deal.format(0,3,',')+' Juta');
				$('#link_fail').text(fail.format(0,3,',')+' Juta');
				$('#link_cancel').text(cancel.format(0,3,',')+' Juta');
				
				
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
	
	
	
	
	
	
	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>
