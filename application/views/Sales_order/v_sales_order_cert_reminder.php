<?php
$this->load->view('include/side_menu'); 
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">
			<h4 class="box-title"><i class="fa fa-check-square"></i> SALES ORDER - REMINDER CERTIFICATE</h4>
			
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			
			
			<div id="Loading_tes" class="overlay_load">
				<center>Please Wait . . .  &nbsp;<img src="<?php echo base_url('assets/img/loading_small.gif') ?>"></center>
			</div>
			<table id="my-grid" class="table table-bordered table-striped">
				<thead>
					<tr class="bg-navy-blue">
						<th class="text-center">SO No</th>
						<th class="text-center">SO Date</th>				
						<th class="text-center">Customer</th>
						<th class="text-center">Quotation</th>
						<th class="text-center">PO No</th>
						<th class="text-center">Marketing</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>

				<tbody id="list_outs_inv">
			   
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
	
	.bg-navy-blue{
		background-color: #16697A !important;
		color	: #ffffff !important;
	}
</style>
<script type="text/javascript">
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
	$(function() {		
		data_display();		
	});
	
	
	
	
	function data_display(){
		
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
				{"targets":5,"sClass":"text-center"},
				{"targets":6,"sClass":"text-center","searchable":false,"orderable": false}
				
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 50,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/outstanding_partial_po',
				type	: "post",
				data 	: {},
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
	
	
	
	function CreateReminder(CodeSo){
		loading_spinner();
		let Link_Process		= base_url +'/'+ active_controller+'/sales_order_reminder_process?code_order='+encodeURIComponent(CodeSo);
		window.location.href	= Link_Process;
	}
	
	
	
	
	

</script>
