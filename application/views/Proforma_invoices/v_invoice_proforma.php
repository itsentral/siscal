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
					<button type='button' class='btn btn-md' id='btn-add-inv'  style='background-color:#37474f;color:white;'> ADD INVOICE <i class='fa fa-arrow-right fa-lg' style="width:50px;"></i> </button>
				<?php
				}
				?>
			</div>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			
			<div id="Loading_tes" class="overlay_load">
				<center>Please Wait . . .  &nbsp;<img src="<?php echo base_url('assets/img/loading_small.gif') ?>"></center>
			</div>
			<table id="my-grid" class="table table-bordered table-striped">
				<thead>
					<tr class="bg-blue">
						<th class="text-center">Invoice No</th>
						<th class="text-center">Datet</th>				
						<th class="text-center">Customer</th>
						<th class="text-center">Order No</th>
						<th class="text-center">PO No</th>
						<th class="text-center">Status</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>

				<tbody id="list_detail_invoice">
			   
				</tbody>
				
			</table>
		</div>
		
		<!-- /.box-body -->
	</div>
<div class="modal fade" id="MyModal" tabindex="-1" role="dialog" aria-labelledby="MyModal" data-backdrop="static">
    <div class="modal-dialog" role="document" style="min-width:55% !important;">
		 <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="public_title"></h5>
                <button class="close" data-dismiss="modal" aria-label="close" id="btn-close">
                    <span aria-hidden="true"><i class="fa fa-close"></i></span>
                </button>
            </div>
            <div class="modal-body" id="detail_modal">
			
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
		vertical-align	: midle !important;
	}
	.ui-datepicker-calendar{
		display : none;
	}
</style>
<script type="text/javascript">
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var arr_akses			= <?php echo json_encode($akses_menu);?>;
	$(function() {		
		data_display();
		$('#btn-add-inv').click(function(e){
			e.preventDefault();
			loading_spinner();
			window.location.href	= base_url +'/'+ active_controller+'/outstanding_invoice';
		});
		
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
				{"targets":3,"sClass":"text-center","searchable":false,"orderable": false},
				{"targets":4,"sClass":"text-center","searchable":false,"orderable": false},
				{"targets":5,"sClass":"text-center","searchable":false,"orderable": false},
				{"targets":6,"sClass":"text-center","searchable":false,"orderable": false}
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 20,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/get_data_display',
				type	: "post",
				cache	: false,
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	function ActionInvoice(ObjectParam){
		let TitleAction	= ObjectParam.title;
		let CodeAction	= ObjectParam.code;
		let LinkAction 	= ObjectParam.action;
		$("#detail_modal").empty();
		loading_spinner_new();       
		$('#public_title').text(TitleAction);		
        $.post(base_url +'/'+ active_controller+'/'+LinkAction,{'code':CodeAction}, function(response) {
			close_spinner_new();  
            $("#detail_modal").html(response);
        });
		$("#MyModal").modal('show');		
	}
	
	
	function PrintInvoice(KodeInv){
		let UlrPrint	= base_url +'/'+ active_controller+'/print_invoice_proforma/'+KodeInv;
		window.open(UlrPrint,'_blank');
	}
	
	

</script>
