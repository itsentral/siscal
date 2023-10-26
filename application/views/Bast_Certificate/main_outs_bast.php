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
				<div class="col-sm-4">
					<?php
						echo"&nbsp;&nbsp;<button type='button' class='btn btn-md btn-success' id='btn-save'> PROSES <i class='fa fa-send'></i>  </button>";
						echo"&nbsp;&nbsp;<button type='button' class='btn btn-md btn-danger' id='btn-back'> BACK <i class='fa fa-refresh'></i>  </button>";
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
						<th class="text-center">No SO</th>				
						<th class="text-center">Tgl SO</th>
						<th class="text-center">Perusahaan</th>
						<th class="text-center">No Quotation</th>
						<th class="text-center">Tot Sertifikat</th>
					</tr>
				</thead>
				<tbody id="list_detail">
			   
				</tbody>
				
			</table>
		</div>
		
		<!-- /.box-body -->
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
		vertical-align	: midle !important;
	}
	.ui-datepicker-calendar{
		display : none;
	}
</style>
<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(function() {		
		data_display();
		$('#btn-back').click(function(e){
			e.preventDefault();
			loading_spinner();
			window.location.href	= base_url +'index.php/'+ active_controller;
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
		var action_link	= base_url +'index.php/'+ active_controller+'/proses_bast';
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
				{"targets":5,"sClass":"text-center","searchable":false,"orderable": false}
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 20,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'index.php/'+ active_controller+'/get_list_outstanding',
				type	: "post",
				data	: {'nocust':custno},
				cache	: false,
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	

</script>
