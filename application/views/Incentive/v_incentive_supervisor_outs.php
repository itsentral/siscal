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
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">					
						<label class="control-label">
							<strong>Supervisor</strong>
						</label>
						<div>
							<select name="teknisi" id="teknisi" class="form-control chosen-select">
								<option value=""> - SELECT AN OPTION - </option>
								<?php 		
								 if($rows_teknisi){
									foreach($rows_teknisi as $keyC=>$valC){
										echo'<option value="'.$keyC.'">'.strtoupper($valC).'</option>';
									}
								 }
								?>
							</select>
						</div>
						
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label class="control-label">&nbsp;</label>
						<div>
							<button type='button' class='btn btn-md bg-red-active' id='btn_kembali'> <i class='fa fa-arrow-left'></i> BACK TO LIST </button>
							&nbsp;&nbsp;
							<button type='button' class='btn btn-md bg-green-active' id='btn_process_outs'> PROCESS CPR <i class='fa fa-arrow-right'></i> </button>
							<?php
							if($akses_menu['download'] == '1'){
							?>
							&nbsp;&nbsp;
							<button type='button' class='btn btn-md bg-navy-active' id='btn_download_outs'> DOWNLOAD EXCEL <i class='fa fa-download'></i> </button>
							<?php
							}
							?>
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
						<th class="text-center"><input type="checkbox" id="chk_all" name="chk_all"></th>
						<th class="text-center">Quotation No</th>
						<th class="text-center">SO No</th>				
						<th class="text-center">Customer</th>
						<th class="text-center">Supervisor</th>
						<th class="text-center">Tool Code</th>
						<th class="text-center">Tool Name</th>
						<th class="text-center">Price</th>
						<th class="text-center">Qty</th>
						<th class="text-center">Disc (%)</th>
						<th class="text-center">Total</th>
						<th class="text-center">Dimention</th>
					</tr>
				</thead>

				<tbody id="list_outs_cpr">
			   
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
	
	.ui-datepicker-calendar{
		display : none;
	}
	
	.bg-navy-blue{
		background-color: #16697A !important;
		color	: #ffffff !important;
	}
	.text-wrap{
		word-wrap:break-word !important;
	}
	.text-center{
		text-align:center !important;
		vertical-align:middle !important;
	}
	.text-left{
		text-align:left !important;
		vertical-align:middle !important;
	}
	.text-right{
		text-align:right !important;
		vertical-align:middle !important;
	}
</style>
<script type="text/javascript">
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
	$(function() {		
		data_display();		
	});
	$(document).on('click','#btn_kembali',()=>{
		loading_spinner();
		window.location.href	= base_url +'/'+ active_controller;
	});
	
	$(document).on('click','#btn_download_outs',()=>{
		let ChosenTeknisi	= $('#teknisi').val();
		let LinkDownload	= base_url +'/'+ active_controller+'/export_supervisor_incentive_outs?teknisi='+encodeURIComponent(ChosenTeknisi);
		window.open(LinkDownload,'_blank');
	});
	
	$(document).on('click','#chk_all',()=>{
		if($('#chk_all').is(':checked')){
			$('#list_outs_cpr input[type="checkbox"]').prop('checked',true);
		}else{
			$('#list_outs_cpr input[type="checkbox"]').prop('checked',false);
		}
	});
	
	
	$(document).on('change','#teknisi',data_display);
	
	function data_display(){
		let TechChosen	 	= $('#teknisi').val();
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
			"aaSorting": [[ 4, "desc" ]],			
			"columnDefs": [
				{"targets":0,"sClass":"text-center","searchable":false,"orderable": false},
				{"targets":1,"sClass":"text-center"},
				{"targets":2,"sClass":"text-center"},
				{"targets":3,"sClass":"text-left text-wrap"},
				{"targets":4,"sClass":"text-center"},
				{"targets":5,"sClass":"text-center"},
				{"targets":6,"sClass":"text-left text-wrap","searchable":false,"orderable": false},
				{"targets":7,"sClass":"text-right","searchable":false,"orderable": false},
				{"targets":8,"sClass":"text-center","searchable":false,"orderable": false},
				{"targets":9,"sClass":"text-center","searchable":false,"orderable": false},
				{"targets":10,"sClass":"text-right","searchable":false,"orderable": false},
				{"targets":11,"sClass":"text-center","searchable":false,"orderable": false}
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 50,
			"aLengthMenu": [[5, 10, 50, 100, 500, 1000], [5, 10, 50, 100, 500, 1000]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/outstanding_incentive_cpr',
				type	: "post",
				data 	: {'teknisi':TechChosen},
				cache	: false,
				beforeSend: function() {
					$('#Loading_tes').show();
				}, 
				complete: function() {
					$('#Loading_tes').hide();
				},
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="12">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	
	
	
	$(document).on('click','#btn_process_outs',(e)=>{
		e.preventDefault();
		let ChosenTeknisi	= $('#teknisi').val();
		if(ChosenTeknisi == '' || ChosenTeknisi ==  null){
			swal({
			  title				: "Warning !",
			  text				: 'Empty Supervisor. Please choose supervisor first',						
			  type				: "warning"
			});				
			return false;	
		}
		
		
		let JumChosen		= $('#list_outs_cpr').find('input[type="checkbox"]:checked').length;
		
		if(parseInt(JumChosen) <= 0 || JumChosen == ''){
			swal({
			  title				: "Warning !",
			  text				: 'No record was selected. Please choose at least one record....',						
			  type				: "warning"
			});				
			return false;	
		}
		
		loading_spinner();
		let Link_Process		= base_url +'/'+ active_controller+'/supervisor_incentive_cpr_process';
		$('#form-proses').prop('action',Link_Process);		
		$('#form-proses').submit();
	});
	

</script>
