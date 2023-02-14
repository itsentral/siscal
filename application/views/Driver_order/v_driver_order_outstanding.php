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
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Plan Date</label>
						<div>
						<?php
							echo form_input(array('id'=>'plan_date','name'=>'plan_date','class'=>'form-control input-sm','readOnly'=>true),date('d-m-Y'));
						?>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
				<?php
				if($akses_menu['create'] == '1'){
					echo'
						<label class="control-label">&nbsp;</label>
						<div>
							<button type="button" id="btn_create_spk" class="btn btn-md text-center" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="CREATE SPK DRIVER"> CREATE SPK DRIVER <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>
						</div>
					';
				}
				?>
				</div>	
			</div>
			<div id="Loading_tes" class="overlay_load">
				<center>Please Wait . . .  &nbsp;<img src="<?php echo base_url('assets/img/loading_small.gif') ?>"></center>
			</div>
			<table id="my-grid" class="table table-bordered table-striped">
				<thead>
					<tr style="background-color :#16697A !important;color : white !important;">
						<th class="text-center"><input type="checkbox" name="chk_all" id="chk_all"></th>
						<th class="text-center">Driver Order</th>
						<th class="text-center">Plan Date</th>				
						<th class="text-center">Company</th>
						<th class="text-center">Type</th>
						<th class="text-center">Description</th>
						<th class="text-center">Address</th>
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
	.text-left {
		text-align 		: left !important;
		vertical-align	: midle !important;
	}
	.text-right {
		text-align 		: right !important;
		vertical-align	: midle !important;
	}
	
	.text-wrap {
		word-wrap 		: break-word !important;
	}
</style>
<script type="text/javascript">
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(function() {		
		data_display();
		$('#plan_date').datepicker({
			dateFormat	: 'dd-mm-yy',
			changeMonth	:true,
			changeYear	:true,
			onClose: function (date, datepicker) {
				data_display();
			}
		});
		
	});
	
	$(document).on('click','#chk_all',()=>{
		if($('#chk_all').is(':checked')){
			$('#list_detail input[type="checkbox"].check_detail').prop('checked',true);
		}else{
			$('#list_detail input[type="checkbox"].check_detail').prop('checked',false);
		}
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
	
	
	
	
	function data_display(){
		let MonthChosen		= $('#plan_date').val();
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
			"aaSorting": [[ 2, "desc" ]],			
			"columnDefs": [
				{"targets":0,"sClass":"text-center","searchable":false,"orderable": false},
				{"targets":1,"sClass":"text-center"},
				{"targets":2,"sClass":"text-center"},
				{"targets":3,"sClass":"text-left"},
				{"targets":4,"sClass":"text-center"},
				{"targets":5,"sClass":"text-center"},
				{"targets":6,"sClass":"text-left text-wrap"},
				{"targets":7,"sClass":"text-center","searchable":false,"orderable": false}
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 10,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/get_data_display',
				type	: "post",
				cache	: false,
				data	: {'tanggal':MonthChosen},
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	$(document).on('click','#btn_create_spk', (e)=>{
		e.preventDefault();
		let PlanDate	= $('#plan_date').val();
		let Jum_Chosen	= $('#list_detail .check_detail:checked').length;
		
		if(parseInt(Jum_Chosen) <= 0){
			GeneralShowMessageError('error','No record was selected, please choose at least one record..');
			return false;
		}
		const ChosenOrder	= [];
		$('#list_detail .check_detail:checked').each(function(){
			ChosenOrder.push($(this).val());
		});
		
		let JoinOrder	= ChosenOrder.join('^_^');
		let LinkProcess	= base_url +'/'+ active_controller+'/create_spk_driver_order?code_driver='+encodeURIComponent(JoinOrder)+'&plan_date='+encodeURIComponent(PlanDate);
		window.location.href	= LinkProcess;
	});

</script>
