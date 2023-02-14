<?php
$this->load->view('include/side_menu'); 
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">
			<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-sm bg-red-active" id="btn_back_order" title="BACK TO LIST"> <i class="fa fa-arrow-left"></i> BACK TO LIST  </button>
			</div>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			
			<div id="Loading_tes" class="overlay_load">
				<center>Please Wait . . .  &nbsp;<img src="<?php echo base_url('assets/img/loading_small.gif') ?>"></center>
			</div>
			<table id="my-grid2" class="table table-bordered table-striped">
				<thead>
					<tr style="background-color :#16697A !important;color : white !important;">
						<th class="text-center">Sales Order</th>
						<th class="text-center">Date</th>				
						<th class="text-center">Customer</th>
						<th class="text-center">Quotation</th>
						<th class="text-center">Quotation<br>Date</th>
						<th class="text-center">Option</th>
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
	.text-left {
		text-align 		: left !important;
		vertical-align	: middle !important;
	}
	.text-right {
		text-align 		: right !important;
		vertical-align	: middle !important;
	}
	
	.text-wrap {
		word-wrap 		: break-word !important;
	}
	table.table-bordered thead th, table.table-bordered thead td {
		border-left-width: thin !important;
		border-top-width: 0;
	}
</style>
<script type="text/javascript">
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(function() {		
		data_display();
		
		
	});
	
	$(document).on('click','#btn_back_order', ()=>{
		loading_spinner_new();
		window.location.href	= base_url+'/'+active_controller;
	});
	
	function data_display(){
		let table_data 		= $('#my-grid2').DataTable({
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
				{"targets":2,"sClass":"text-left text-wrap"},
				{"targets":3,"sClass":"text-center"},
				{"targets":4,"sClass":"text-center"},
				{"targets":5,"sClass":"text-center","searchable":false,"orderable": false}
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 10,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/display_out_schedule_order',
				type	: "post",
				cache	: false,
				data	: {},
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	

</script>
