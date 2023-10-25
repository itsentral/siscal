<?php
$this->load->view('include/side_menu'); 
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">
			<h4 class="box-title"><i class="fa fa-check-square"></i> CREATE SUBCON PURCHASE ORDER</h4>
			
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">					
						<label class="control-label">
							<strong>Subcon</strong>
						</label>
						<div>
							<select name="supplier_id" id="supplier_id" class="form-control chosen-select">
								<option value=""> - SELECT AN OPTION - </option>
								<?php 		
								 
									foreach($rows_supplier as $keyC=>$valC){
										echo'<option value="'.$keyC.'">'.$valC.'</option>';
									}
								?>
							</select>
						</div>
						
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">&nbsp;</label>
						<div>
							<button type='button' class='btn btn-md bg-red-active' id='btn_kembali'> <i class='fa fa-arrow-left'></i> BACK TO LIST </button>
							&nbsp;&nbsp;
							<button type='button' class='btn btn-md bg-blue-active' id='btn_download'> DOWNLOAD EXCEL <i class='fa fa-download'></i> </button>
							&nbsp;&nbsp;
							<button type='button' class='btn btn-md bg-green-active' id='btn_process_inv'> PROCESS PO <i class='fa fa-arrow-right'></i> </button>
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
						<th class="text-center">Tool Code</th>
						<th class="text-center">Tool Name</th>				
						<th class='text-center'>Qty</th>
						<th class='text-center'>HPP</th>
						<th class='text-center'>Type</th>
						<th class='text-center'>Subcon</th>
						<th class='text-center'>SO No</th>										
						<th class='text-center'>Customer</th>
						<th class='text-center'>Quotation</th>
						<th class='text-center'>PO No</th>
						<th class='text-center'>Marketing</th>		
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
	$(document).on('click','#btn_kembali',()=>{
		loading_spinner();
		window.location.href	= base_url +'/'+ active_controller;
	});
	
	$(document).on('click','#btn_download',()=>{
		let Code_Supplier	= $('#supplier_id').val();
		let Link_Download	= base_url +'/'+ active_controller+'/download_outs_list?supplier='+encodeURIComponent(Code_Supplier);
		window.open(Link_Download,'_blank');
	});
	
	$(document).on('click','#chk_all',()=>{
		if($('#chk_all').is(':checked')){
			$('#list_outs_inv input[type="checkbox"]').prop('checked',true);
		}else{
			$('#list_outs_inv input[type="checkbox"]').prop('checked',false);
		}
	});
	
	
	$(document).on('change','#supplier_id',data_display);
	
	function data_display(){
		let CustChosen	 	= $('#supplier_id').val();
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
				{"targets":2,"sClass":"text-left"},
				{"targets":3,"sClass":"text-center"},
				{"targets":4,"sClass":"text-right"},
				{"targets":5,"sClass":"text-center"},
				{"targets":6,"sClass":"text-left"},
				{"targets":7,"sClass":"text-left"},
				{"targets":8,"sClass":"text-left"},
				{"targets":9,"sClass":"text-left"},
				{"targets":10,"sClass":"text-left"},
				{"targets":11,"sClass":"text-left"}
				
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 50,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/display_outstanding_subcon_po',
				type	: "post",
				data 	: {'supplier_id':CustChosen},
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
	
	
	
	
	
	$(document).on('click','#btn_process_inv',(e)=>{
		e.preventDefault();
		let ChosenCustomer	= $('#supplier_id').val();
		if(ChosenCustomer == '' || ChosenCustomer ==  null){
			swal({
			  title				: "Warning !",
			  text				: 'Empty Subcon. Please choose subcon first',						
			  type				: "warning"
			});				
			return false;	
		}
		
		
		let JumChosen		= $('#list_outs_inv').find('input[type="checkbox"]:checked').length;
		
		if(parseInt(JumChosen) <= 0 || JumChosen == ''){
			swal({
			  title				: "Warning !",
			  text				: 'No record was selected. Please choose at least one record....',						
			  type				: "warning"
			});				
			return false;	
		}
		
		const ChosenOrder	= [];
		$('#list_outs_inv').find('input[type="checkbox"]:checked').each(function(){
			ChosenOrder.push($(this).val());
		});
		
		let CodeTerpilih	= ChosenOrder.join('^');
		
		loading_spinner();
		let Link_Process		= base_url +'/'+ active_controller+'/subcon_purchase_order_process?code_order='+encodeURIComponent(CodeTerpilih)+'&supplier='+encodeURIComponent(ChosenCustomer);
		window.location.href	= Link_Process;
	});
	

</script>
