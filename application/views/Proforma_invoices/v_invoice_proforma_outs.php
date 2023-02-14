<?php
$this->load->view('include/side_menu'); 
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">
			<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
			
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class="form-group row">
				<label class="control-lable col-sm-1">Customer</label>
				<div class="col-sm-4">
					<?php
					echo form_dropdown('custid',$rows_cust,'', array('id'=>'custid', 'class'=>'form-control input-sm'));
					?>
				</div>
				<div class="col-sm-6">
					<?php
						echo"&nbsp;&nbsp;<button type='button' class='btn btn-md' style='background-color:#ff8f00;color:white;' id='btn-save'> <b>PROCESS INVOICE</b> <i class='fa fa-arrow-right fa-lg' style='width:45px;'></i>  </button>";
						echo"&nbsp;&nbsp;<button type='button' class='btn btn-md' id='btn-back'  style='background-color:#37474f;color:white;'> <i class='fa fa-arrow-left fa-lg' style='width:45px;'></i> <b>BACK</b> </button>";
					?>
				</div>
			</div>
			<div id="Loading_tes" class="overlay_load">
				<center>Please Wait . . .  &nbsp;<img src="<?php echo base_url('assets/img/loading_small.gif') ?>"></center>
			</div>
			<table id="my-grid" class="table table-bordered table-striped">
				<thead>
					<tr class="bg-blue">
						<th class="text-center"><input type="checkbox" name="chk_all" id="chk_all"></th>
						<th class="text-center">Order No</th>				
						<th class="text-center">Order Date</th>
						<th class="text-center">Customer</th>
						<th class="text-center">Quotation</th>
						<th class="text-center">PO No</th>
						<th class="text-center">Salesman</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody id="list_detail">
			   
				</tbody>
				
			</table>
		</div>
		
		<!-- /.box-body -->
	</div>
<div class="modal fade" id="HistoryModal" tabindex="-1" role="dialog" aria-labelledby="HistoryModal" data-backdrop="static">
    <div class="modal-dialog" role="document" >
		 <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color:red;">CLOSE SALES ORDER (INV PROFORMA)</h5>
                <button class="close" data-dismiss="modal" aria-label="close" id="btn-hist-close">
                    <span aria-hidden="true"><i class="fa fa-close"></i></span>
                </button>
            </div>
            <div class="modal-body" id="detail_modal_hist">
				<input type="hidden" name="code_close_so" id="code_close_so" value="">
				<div class="form-group row">
					<label class="control-lable col-sm-3">No SO</label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-sm" name="close_so" id="close_so" value="" readOnly>
					</div>
					
				</div>
				<div class="form-group row">
					<label class="control-lable col-sm-3">Close Reason</label>
					<div class="col-sm-8">
						<textarea cols="75" rows="2" class="form-control input-sm" name="close_reason" id="close_reason"></textarea>
					</div>
					
				</div>
			</div>
			<div class="modal-footer">
				<?php
				echo"<button type='button' class='btn btn-md' style='background-color:#c2185b;color:white;min-width:100%' id='btn-save-close'> <b>CLOSE PROCESS</b> <i class='fa fa-long-arrow-right fa-lg' style='width:45px;'></i>  </button>";
				?>
			</div>
		</div>
    </div>
</div>
<?php $this->load->view('include/footer'); ?>
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
	.ui-datepicker-calendar{
		display : none;
	}
</style>
<script type="text/javascript">
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(function() {		
		data_display();
		$('#btn-back').click(function(e){
			e.preventDefault();
			loading_spinner();
			window.location.href	= base_url +'/'+ active_controller;
		});
		
	});
	$(document).on('change','#custid',data_display);
	$(document).on('click','#btn-save',function(e){		
		e.preventDefault();
		$('#btn-save, #btn-back').prop('disabled',true);
		let cust_old 		= $('#custid').val();
		
		
		if(cust_old =='' || cust_old==null || cust_old=='0'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty Customer, Please choose Customer first...',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
			return false;
		}
		
		
		
		var total	= $('#list_detail input[type="checkbox"]').filter(':checked').length;
		if(parseInt(total) <= 0){
			swal({
			  title				: "Error Message !",
			  text				: 'No Record was selected....',						
			  type				: "warning"
			});
			$('#btn-save, #btn-back').prop('disabled',false);
			return false;
		}
		loading_spinner();
		var action_link	= base_url +'/'+ active_controller+'/generate_invoice';
		$('#form-proses').prop('action',action_link);
		$('#form-proses').submit();
		
	});
	
	$(document).on('click','#chk_all',function(e){		
		if($(this).is(':checked')){
			$('#list_detail input[type="checkbox"]:not(:checked)').trigger('click');
		}else{
			$('#list_detail input[type="checkbox"]:checked').trigger('click');
		}
		e.stopPropagation();
	});
	
	function data_display(){
		var custno		= $('#custid').val();
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
			"aaSorting": [[ 2, "asc" ]],			
			"columnDefs": [
				{"targets":0,"sClass":"text-center","searchable":false,"orderable": false},
				{"targets":1,"sClass":"text-center"},
				{"targets":2,"sClass":"text-center"},
				{"targets":3,"sClass":"text-left"},
				{"targets":4,"sClass":"text-left"},
				{"targets":5,"sClass":"text-left"},
				{"targets":6,"sClass":"text-center"},
				{"targets":7,"sClass":"text-center","searchable":false,"orderable": false}
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 20,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/get_list_outstanding',
				type	: "post",
				data	: {'nocust':custno},
				cache	: false,
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	$(document).on('click','#btn-hist-close',function(){
		$('#code_close_so, #close_so, #close_reason').val('');
		$('#HistoryModal').modal('hide');
	});
	
	function CloseOrder(CodeSo){
		let NomorSo	= $('#btn_cancel_'+CodeSo).attr('data-noso');
		$('#code_close_so').val(CodeSo);
		$('#close_so').val(NomorSo);
		$('#close_reason').val('');
		$('#HistoryModal').modal('show');
	}
	
	$(document).on('click','#btn-save-close',function(e){
		e.preventDefault();
		$('#btn-hist-close, #btn-save-close').prop('disabled',true);
		let CodeOrder	= $('#code_close_so').val();
		let ReasonOrder	= $('#close_reason').val();
		if(ReasonOrder == '' || ReasonOrder == null || ReasonOrder =='-'){
			swal({
			  title				: "Error Message !",
			  text				: 'Empty Close Reason...',						
			  type				: "warning"
			});
			$('#btn-hist-close, #btn-save-close').prop('disabled',false);
			false;
		}
		
		var data_post	= {'kode_so':CodeOrder,'alasan':ReasonOrder};
		
		let baseurl		= base_url +'/'+ active_controller+'/proses_close_order';
		$.ajax({
			url			: baseurl,
			type		: "POST",
			data		: data_post,
			cache		: false,
			dataType	: 'json',
			beforeSend	: function(){
				loading_spinner_new();
				
			},
			success		: function(data){
				close_spinner_new();
								
				if(data.status == 1){
					window.location.href = base_url +'/'+ active_controller+'/outstanding_invoice';
				}else{		
					swal({
					  title				: "Error Message !",
					  text				: data.pesan,						
					  type				: "warning"
					});
					
					$('#btn-hist-close, #btn-save-close').prop('disabled',false);
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
				
				$('#btn-hist-close, #btn-save-close').prop('disabled',false);
				return false;
			}
		});
	});

</script>
