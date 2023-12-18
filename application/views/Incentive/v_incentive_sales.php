<?php
$this->load->view('include/side_menu'); 
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">
			<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
			<div class="box-tools pull-right">
				<?php
				if($akses_menu['create'] == 1){
				?>
					<button type='button' class='btn btn-md bg-maroon' id='btn-add'> CREATE INCENTIVE CPR <i class='fa fa-money'></i>  </button>
					
				<?php
				}
				?>
			</div>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">					
						<label class="control-label">
							<strong>Salesman</strong>
						</label>
						<div>
							<select name="sales" id="sales" class="form-control chosen-select">
								<option value=""> - ALL SALESMAN - </option>
								<?php 		
								 if($rows_sales){
									foreach($rows_sales as $keyC=>$valC){
										echo'<option value="'.$keyC.'">'.$valC.'</option>';
									}
								 }
								?>
							</select>
						</div>
						
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						
						<label class="control-label">
							<strong>Month</strong>
						</label>
						<div>
							<select name="bulan" id="bulan" class="form-control chosen-select" style="width:100%;">
								<option value="">- Choose Month -</option>
								<?php
									$array_bulan = array(
										'1'    => 'January'
										,'2'   => 'February'
										,'3'   => 'March'
										,'4'   => 'April'
										,'5'   => 'May'
										,'6'   => 'June'
										,'7'   => 'July'
										,'8'   => 'August'
										,'9'   => 'September'
										,'10'   => 'October'
										,'11'   => 'November'
										,'12'   => 'December'
									);

									foreach($array_bulan as $key => $value){
										$selected = '';
										if($key == date('n')){
											$selected = 'selected';
										}
										echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
									}
								?>
							</select>
						</div>
						
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						
						<label class="control-label">
							<strong>Year</strong>
						</label>
						<div>
							<select name="tahun" id="tahun" class="form-control chosen-select" style="width:100%;">
								<option value="">- Choose Year -</option>
								<?php
									for($i = date('Y'); $i >= 2018; $i--){
										$selected = '';
										if(date('Y') == $i){
											$selected = 'selected';
										}
										echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
									}
								?>
							</select>
						</div>
						
					</div>
				</div>
				
			</div>
			
			<div id="Loading_tes" class="overlay_load">
				<center>Please Wait . . .  &nbsp;<img src="<?php echo base_url('assets/img/loading_small.gif') ?>"></center>
			</div>
			<table id="my-grid" class="table table-bordered table-striped">
				<thead>
					<tr class="bg-navy-blue">
						<th class="text-center">CPR No</th>
						<th class="text-center">CPR Date</th>				
						<th class="text-center">Salesman</th>
						<th class="text-center">Description</th>
						<th class="text-center">Incentive</th>
						<th class="text-center">Status</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>

				<tbody id="list_detail">
			   
				</tbody>
				
			</table>
		</div>
		
		<!-- /.box-body -->
	</div>
	<div class="modal fade" id="MyModalView" tabindex="-1" role="dialog" aria-labelledby="MyModal" data-backdrop="static">
		<div class="modal-dialog" role="document" style="min-width:70% !important;">
			 <div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="MyModalTitle"></h5>
					<button class="close" data-dismiss="modal" aria-label="close" id="btn-modal-close">
						<span aria-hidden="true"><i class="fa fa-close"></i></span>
					</button>
				</div>
				<div class="modal-body" id="MyModalDetail">
				
				</div>
			</div>
		</div>
	</div>
<?php $this->load->view('include/footer'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<!-- page script -->
<style>
	.overlay_load {
		background: #eee; 
		display: none;       
		position: absolute;  
		top: 0;              
		right: 0;            
		bottom: 0;
		left: 0;
		padding-top:40%;
		opacity: 0.7;
		z-index:2;
	}
	.text-center {
		text-align 		: center !important;
		vertical-align	: middle !important;
	}
	
	.bg-navy-blue{
		background-color: #16697A !important;
		color	: #ffffff !important;
	}
</style>
<script type="text/javascript">
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var arr_akses			= <?php echo json_encode($akses_menu);?>;
	$(function() {		
		data_display();
		$('#btn-add').click(function(e){
			e.preventDefault();
			loading_spinner();
			window.location.href	= base_url +'/'+ active_controller+'/list_outstanding_incentive';
		});		
	});
	
	function ActionIncentive(ObjectParam){
		let TitleAction	= ObjectParam.title;
		let CodeAction	= ObjectParam.code;
		let LinkAction 	= ObjectParam.action;
		
		loading_spinner_new();		
		$('#MyModalTitle').text(TitleAction);		
        $.post(base_url +'/'+ active_controller+'/'+LinkAction,{'code':CodeAction}, function(response) {
			close_spinner_new();
            $("#MyModalDetail").html(response);
        });
		$("#MyModalView").modal('show');		
	}
	
	
	
	$(document).on('change','#sales',data_display);
	$(document).on('change','#bulan',data_display);
	$(document).on('change','#tahun',data_display);
	
	function data_display(){
		let SalesChosen	 	= $('#sales').val();
		let MonthChosen		= $('#bulan').val();
		let YearChosen		= $('#tahun').val();
		let table_data 		= $('#my-grid').DataTable({
			"serverSide": true,
			"destroy"	: true,
			"stateSave" : false,
			"bAutoWidth": false,
			"oLanguage": {
				"sSearch": "<b>Live Search : </b>",
				"sLengthMenu": "_MENU_ &nbsp;&nbsp;<b>Records Per Page</b>&nbsp;&nbsp;",
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ entries",
				"sInfoFiltered": "(filtered from _MAX_ total entries)", 
				"sZeroRecords": "No matching records found", 
				"sEmptyTable": "No data available in table", 
				"sLoadingRecords": "Please wait - loading...", 
				"oPaginate": {
					"sPrevious": "Prev",
					"sNext": "Next"
				}
			},
			"aaSorting": [[ 1, "desc" ]],			
			"columnDefs": [
				{"targets":0,"sClass":"text-center"},
				{"targets":1,"sClass":"text-center"},
				{"targets":2,"sClass":"text-center"},
				{"targets":3,"sClass":"text-left"},
				{"targets":4,"sClass":"text-right"},
				{"targets":5,"sClass":"text-center","searchable":false,"orderable": false},
				{"targets":6,"sClass":"text-center","searchable":false,"orderable": false}
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 20,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/get_data_display',
				type	: "post",
				data 	: {'sales':SalesChosen,'bulan':MonthChosen,'tahun':YearChosen},
				cache	: false,
				beforeSend: function() {
					$('#Loading_tes').show();
				}, 
				complete: function() {
					$('#Loading_tes').hide();
				},
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	/*
	| ------------------------------- |
	|		UPDATE PAYMENT			  |
	| ------------------------------- |
	*/
	$(document).on('click','#btn_upd_payment',(e)=>{
		e.preventDefault();
		$('#btn-modal-close, #btn_upd_payment').prop('disabled',true);
		let PaymentDate	= $('#buk_tgl').val();
		let PaymentReff	= $('#buk_id').val();
		if(PaymentDate == '' || PaymentDate == null){
			
			swal({
			  title				: "Error Message !",
			  text				: 'Empty Payment Date. Please choose payment date first...',						
			  type				: "warning"
			});
			$('#btn-modal-close, #btn_upd_payment').prop('disabled',false);
			return false;
			
		}
		
		if(PaymentReff == '' || PaymentReff == null || PaymentReff == '-'){
			
			swal({
			  title				: "Error Message !",
			  text				: 'Empty Payment Reff No. Please input payment reff no first...',						
			  type				: "warning"
			});
			$('#btn-modal-close, #btn_upd_payment').prop('disabled',false);
			return false;
			
		}
		
		swal({
			  title: "Are you sure?",
			  text: "You will not be able to process again this data!",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonClass: "btn-danger",
			  confirmButtonText: "Yes, Process it!",
			  cancelButtonText: "No, cancel process!",
			  closeOnConfirm: true,
			  closeOnCancel: false
			},
			function(isConfirm) {					
				if (isConfirm) {
					loading_spinner_new();
					var formData 	= new FormData($('#form_proses_preview')[0]);
					var baseurl		= base_url +'/'+ active_controller+'/save_payment_sales_cpr';
					$.ajax({
						url			: baseurl,
						type		: "POST",
						data		: formData,
						cache		: false,
						dataType	: 'json',
						processData	: false, 
						contentType	: false,				
						success		: function(data){
							close_spinner_new();
							if(data.status == 1){	
								swal({
									  title	: "Save Success!",
									  text	: data.pesan,
									  type	: "success"
									});
								window.location.href = base_url +'/'+ active_controller;
							}else{								
								swal({
								  title	: "Save Failed!",
								  text	: data.pesan,
								  type	: "warning"
								});									
								//alert(data.pesan);
								$('#btn-modal-close, #btn_upd_payment').prop('disabled',false);
								return false;
								
							}
						},
						error: function() {
							close_spinner_new();
							swal({
							  title				: "Error Message !",
							  text				: 'An Error Occured During Process. Please try again..',						
							  type				: "warning"
							});
							$('#btn-modal-close, #btn_upd_payment').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-modal-close, #btn_upd_payment').prop('disabled',false);
					return false;
				}
		});
	});
	
	
	/*
	| ------------------------------- |
	|		CANCEL INCENTIVE CPR	  |
	| ------------------------------- |
	*/
	$(document).on('click','#btn_cancel_cpr',(e)=>{
		e.preventDefault();
		$('#btn-modal-close, #btn_cancel_cpr').prop('disabled',true);
		let CancelReason	= $('#cancel_reason').val();
		
		
		if(CancelReason == '' || CancelReason == null || CancelReason == '-'){
			
			swal({
			  title				: "Error Message !",
			  text				: 'Empty Cancel Reason Please input cancel reason first...',						
			  type				: "warning"
			});
			$('#btn-modal-close, #btn_cancel_cpr').prop('disabled',false);
			return false;
			
		}
		
		swal({
			  title: "Are you sure?",
			  text: "You will not be able to process again this data!",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonClass: "btn-danger",
			  confirmButtonText: "Yes, Process it!",
			  cancelButtonText: "No, cancel process!",
			  closeOnConfirm: true,
			  closeOnCancel: false
			},
			function(isConfirm) {					
				if (isConfirm) {
					loading_spinner_new();
					var formData 	= new FormData($('#form_proses_preview')[0]);
					var baseurl		= base_url +'/'+ active_controller+'/save_cancel_sales_cpr';
					$.ajax({
						url			: baseurl,
						type		: "POST",
						data		: formData,
						cache		: false,
						dataType	: 'json',
						processData	: false, 
						contentType	: false,				
						success		: function(data){
							close_spinner_new();
							if(data.status == 1){	
								swal({
									  title	: "Save Success!",
									  text	: data.pesan,
									  type	: "success"
									});
								window.location.href = base_url +'/'+ active_controller;
							}else{								
								swal({
								  title	: "Save Failed!",
								  text	: data.pesan,
								  type	: "warning"
								});									
								//alert(data.pesan);
								$('#btn-modal-close, #btn_cancel_cpr').prop('disabled',false);
								return false;
								
							}
						},
						error: function() {
							close_spinner_new();
							swal({
							  title				: "Error Message !",
							  text				: 'An Error Occured During Process. Please try again..',						
							  type				: "warning"
							});
							$('#btn-modal-close, #btn_cancel_cpr').prop('disabled',false);
							return false;
						}
					});
					
				} else {
					close_spinner_new();
					swal("Cancelled", "Data can be process again :)", "error");
					$('#btn-modal-close, #btn_cancel_cpr').prop('disabled',false);
					return false;
				}
		});
	});
	
</script>
