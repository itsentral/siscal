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
			<div class='form-group row'>			
				<label class='label-control col-sm-1'><b>Periode <span class='text-red'>*</span></b></label> 
				<div class='col-sm-2 col-xs-6'>
					<?php
						echo form_input(array('id'=>'periode_awal','name'=>'periode_awal','class'=>'form-control input-sm','readOnly'=>true,'data-role'=>'datepicker'),date('Y-m-01'));
					?>							
				</div>
				<div class='col-sm-2 col-xs-6'>
					<?php
						echo form_input(array('id'=>'periode_akhir','name'=>'periode_akhir','class'=>'form-control input-sm','readOnly'=>true,'data-role'=>'datepicker'),date('Y-m-d'));
					?>							
				</div>
				<div class='col-sm-6 col-xs-12'>
					<?php
						echo form_button(array('type'=>'button','class'=>'btn btn-sm bg-orange-active','value'=>'save','content'=>'PREVIEW DATA','id'=>'btn-preview')).'&nbsp;&nbsp;<button type="button" id="btn-kembali" class="btn btn-sm" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="BACK TO LIST"> BACK TO LIST <i class="fa fa-arrow-right" style="width:40px;"></i> </button>';
						
					?>							
				</div>
			</div>
			<div id="Loading_tes" class="overlay_load">
				<center>Please Wait . . .  &nbsp;<img src="<?php echo base_url('assets/img/loading_small.gif') ?>"></center>
			</div>
			<table id="my-grid" class="table table-bordered table-striped">
				<thead>
					<tr style="background-color :#16697A !important;color : white !important;">
						<th class="text-center">Quotation</th>
						<th class="text-center">Tgl Quotation</th>				
						<th class="text-center">Customer</th>
						<th class="text-center">Marketing</th>
						<th class="text-center">Nomor PO</th>
						<th class="text-center">Tgl PO</th>
						<th class="text-center">Option</th>
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
</style>
<script type="text/javascript">
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(function() {		
		data_display();
		
	});
	
	$(document).on('click','#btn-kembali',function(){			
		loading_spinner();
		window.location.href =  base_url+'/'+active_controller;
	});
	
	function ActionPreview(ObjectParam){
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
	
	$(document).on('click','#btn-preview',()=>{
		let tgl_awal	= $('#periode_awal').val();
		let tgl_awkhir	= $('#periode_akhir').val();
		if(tgl_awal > tgl_awkhir){
			swal({
			  title	: "Error Message!",
			  text	: 'Incorrect period Please input correct period.....',
			  type	: "warning"
			});
			return false;
		}
		data_display();
	});
	
	
	function data_display(){
		let DateFr		= $('#periode_awal').val();
		let DateTl		= $('#periode_akhir').val();
		
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
			"aaSorting": [[ 0, "desc" ]],			
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
			"iDisplayLength": 20,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/get_data_display_cancellation',
				type	: "post",
				cache	: false,
				data	: {'datefr':DateFr,'datetl':DateTl},
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	
	

</script>
