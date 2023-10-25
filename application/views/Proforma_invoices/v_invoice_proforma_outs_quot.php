<?php
$this->load->view('include/side_menu'); 
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">
			<div class="box-tools pull-right">
				<?php
						echo"<button type='button' class='btn btn-md bg-maroon-active' id='btn-back'> <i class='fa fa-arrow-left fa-lg' style='width:45px;'></i> <b>BACK</b> </button>";
					?>
			</div>
			
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
			</div>
			<div id="Loading_tes" class="overlay_load">
				<center>Please Wait . . .  &nbsp;<img src="<?php echo base_url('assets/img/loading_small.gif') ?>"></center>
			</div>
			<table id="my-grid" class="table table-bordered table-striped">
				<thead>
					<tr class="bg-blue">
						<th class="text-center">Quotation</th>				
						<th class="text-center">Date</th>
						<th class="text-center">Customer</th>
						<th class="text-center">Po No</th>
						<th class="text-center">PO Date</th>
						<th class="text-center">Salesman</th>
						<th class="text-center">Total</th>
						<th class="text-center">Action</th>
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
	
	
	
	function data_display(){
		let custno		= $('#custid').val();
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
				{"targets":2,"sClass":"text-left"},
				{"targets":3,"sClass":"text-center"},
				{"targets":4,"sClass":"text-center"},
				{"targets":5,"sClass":"text-left"},
				{"targets":6,"sClass":"text-righ"},
				{"targets":7,"sClass":"text-center","searchable":false,"orderable": false}
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 20,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/display_out_quotation',
				type	: "post",
				data	: {'nocust':custno},
				cache	: false,
				beforeSend: function() {
					$('#Loading_tes').show();
				}, 
				complete: function() {
					$('#Loading_tes').hide();
				},
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	

</script>
