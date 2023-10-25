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
						<label class="control-label">Date</label>
						<?php
							echo form_input(array('id'=>'spk_date','name'=>'spk_date','class'=>'form-control input-sm','readOnly'=>true),date('Y-m-d'));	
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Technician <span class="text-red">*</span></label>
						<div>
							<select name="code_teknisi" id="code_teknisi" class="form-control chosen-select">
								<option value=""> - SELECT AN OPTION - </option>
								<?php 		
								 if($rows_teknisi){
									 foreach($rows_teknisi as $keyC=>$valC){
										echo'<option value="'.$keyC.'">'.$valC.'</option>';
									}
								 }
									
								?>
							</select>
						</div>
					</div>
				</div>				
			</div>
		</div>
		<div class="box-body">
			<div class="box box-primary">
				<div class="box-header">
					<h4 class="box-title"><i class="fa fa-wrench"></i> DETAIL TOOLS</h4>
					<div class="box-tools pull-right">
						<button type='button' class='btn btn-md bg-navy-active' id='btn-add-tool'> ADD TOOLS <i class='fa fa-plus'></i>  </button>					
					</div>
				</div>
				<div class="box-body">
					<table id="my-grid" class="table table-bordered table-striped">
						<thead>
							<tr class="bg-navy-blue">
								<th class="text-center">Tool Name</th>
								<th class="text-center">Qty</th>
								<th class="text-center">Type</th>
								<th class="text-center">SO No</th>
								<th class="text-center">Customer</th>
								<th class="text-center">Plan Date</th>
								<th class="text-center">Plan Technician</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>

						<tbody id="list_det_tool">
					   
						</tbody>
						
					</table>
				</div>
			</div>
		</div>
		<div class='box-body'>
			<div class='row col-md-2 col-md-offset-5' id='loader_proses_save'>
				<div class='loader'>
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>
		</div>
		<div class='box-footer text-center'>
			<button type='button' class='btn btn-md bg-red-active' id='btn_kembali'> <i class='fa fa-arrow-left'></i> BACK TO LIST </button>
			&nbsp;&nbsp;
			<button type='button' class='btn btn-md bg-green-active' id='btn_process_spk'> PROCESS SPK <i class='fa fa-arrow-right'></i> </button>
		</div>
		<!-- /.box-body -->
	</div>
	<div class="modal fade" id="MyModalView" tabindex="-1" role="dialog" aria-labelledby="MyModal" data-backdrop="static">
		<div class="modal-dialog" role="document" style="min-width:70% !important;">
			 <div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="MyModalTitle">LIST TOOL</h5>
					<button class="close" data-dismiss="modal" aria-label="close" id="btn-modal-close">
						<span aria-hidden="true"><i class="fa fa-close"></i></span>
					</button>
				</div>
				<div class="modal-body" id="MyModalDetail">
					<table class="table table-striped table-bordered" id="my-grid2">
						<thead>
							<tr style="background-color:#16697A !important;color:white !important;">
								<th class="text-center">Code</th>
								<th class="text-center">Tool Name</th>
								<th class="text-center">Qty</th>
								<th class="text-center">SO No</th>
								<th class="text-center">Customer</th>
								<th class="text-center">Plan Date</th>
								<th class="text-center">Technician</th>
								<th class="text-center">Type</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody id="list_outs_tool">
						
						</tbody>
					</table>
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
	
	.bg-navy-blue{
		background-color: #16697A !important;
		color	: #ffffff !important;
	}
</style>
<script type="text/javascript">
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	
	$(function() {	
		$('#loader_proses_save').hide();
		$('#spk_date').datepicker({
			dateFormat	: 'yy-mm-dd',
			changeMonth	:true,
			changeYear	:true,
			minDate		:'+0d'
		});
	});
	
	
	$(document).on('click','#btn_kembali',()=>{
		loading_spinner();
		window.location.href	= base_url +'/'+ active_controller;
	});
	
	$(document).on('click','#btn-add-tool',()=>{		
		$("#list_outs_tool").html('');
		$("#MyModalView").modal('show');
		data_display_outs();
		
	});
	
	function data_display_outs(){
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
			"aaSorting": [[ 1, "asc" ]],			
			"columnDefs": [
				{"targets":0,"sClass":"text-center"},
				{"targets":1,"sClass":"text-left text-wrap"},
				{"targets":2,"sClass":"text-center"},
				{"targets":3,"sClass":"text-center"},
				{"targets":4,"sClass":"text-left text-wrap"},
				{"targets":5,"sClass":"text-center"},
				{"targets":6,"sClass":"text-center"},
				{"targets":7,"sClass":"text-center"},
				{"targets":8,"sClass":"text-center","searchable":false,"orderable": false}
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 20,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/outstanding_technician_letter',
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
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="9">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	
	
	
	
	function ChosenTool(code_trans){
		
		let Code_Tool	= $('#trans_tool_'+code_trans).data('trans-tool');
		let Name_Tool	= $('#trans_tool_'+code_trans).data('trans-name');
		let Nomor_SO	= $('#trans_tool_'+code_trans).data('trans-so');
		let Code_Detail	= $('#trans_tool_'+code_trans).data('trans-detail');
		let Customer	= $('#trans_tool_'+code_trans).data('trans-cust');
		let Teknisi		= $('#trans_tool_'+code_trans).data('trans-teknisi');
		let Plan_Date	= $('#trans_tool_'+code_trans).data('trans-date');
		let Qty			= $('#trans_tool_'+code_trans).data('trans-qty');
		let Category	= $('#trans_tool_'+code_trans).data('trans-jenis');
		let Code_SO		= $('#trans_tool_'+code_trans).data('trans-letter');
		let kategori	= 'LABS';
		let jenis		='<span class="badge bg-aqua">Labs</span>';
		if(Category=='Insitu'){
			kategori	= 'INSITU';
			jenis		='<span class="badge bg-maroon">Insitu</span>';
		}
		
		let Jum_Rows	= $('#list_det_tool').find('tr').length;
		let loop		= 1;
		var OK_Proses	= 0;
		if(parseInt(Jum_Rows) > 0){
			let Code_Last		= $('#list_det_tool tr:last').attr('id');
			const Split_Last	= Code_Last.split('_');
			loop				= parseInt(Split_Last[2]) + 1;
			$('#list_det_tool').find('input.cekD:hidden').each(function(){
				let hasil	= $(this).val();
				if(hasil==code_trans){
					OK_Proses++;
				}
			});
		}
		
		if(OK_Proses == 0){
			Template	='<tr id="tr_urut_'+loop+'">'+
							'<td class="text-left">'+
								'<input type="hidden" name="TechOrderDetail['+loop+'][kode_proses]"  id="kode_proses_'+loop+'" value="'+code_trans+'" class="cekD">'+
								'<input type="hidden" name="TechOrderDetail['+loop+'][tool_name]"  id="tool_name_'+loop+'" value="'+Name_Tool+'">'+
								'<input type="hidden" name="TechOrderDetail['+loop+'][tool_id]"  id="tool_id_'+loop+'" value="'+Code_Tool+'">'+
								'<input type="hidden" name="TechOrderDetail['+loop+'][qty]"  id="qty_'+loop+'" value="'+Qty+'">'+
								'<input type="hidden" name="TechOrderDetail['+loop+'][detail_id]"  id="detail_id_'+loop+'" value="'+Code_Detail+'">'+
								'<input type="hidden" name="TechOrderDetail['+loop+'][category]"  id="category_'+loop+'" value="'+kategori+'">'+Name_Tool+			
							 '</td>'+
							 '<td class="text-center">'+Qty+'</td>'+
							 '<td class="text-center">'+jenis+'</td>'+
							 '<td class="text-center">'+Nomor_SO+'</td>'+
							 '<td class="text-left text-wrap">'+Customer+'</td>'+
							 '<td class="text-center">'+Plan_Date+'</td>'+
							 '<td class="text-center">'+Teknisi+'</td>'+
							 '<td class="text-center"><button type="button" class="btn btn-sm btn-danger" title="Hapus Data" data-role="qtip" onClick="return DelItem('+loop+');"><i class="fa fa-trash-o"></i></button></td>'+
						'</tr>';
			$('#list_det_tool').append(Template);
		}
		
	}
	
	function DelItem(CodeUrut){
		$('#tr_urut_'+CodeUrut).remove();
	}
	
	$(document).on('click','#btn_process_spk', async(e)=>{
		e.preventDefault();
		$('#btn_kembali, #btn_process_spk').prop('disabled',true);
	
		let SPK_Date		= $('#spk_date').val();		
		let Code_Technician	= $('#code_teknisi').val();
		let Jumlah_Baris	= $('#list_det_tool').find('tr').length;
		
		const ValueCheck	= {
			'tanggal':{'nilai':SPK_Date,'error':'Empty SPK Date. Please Choose SPK Date first..'},
			'teknisi':{'nilai':Code_Technician,'error':'Empty Technician. Please choose technician first..'}
		};
		
		if(parseInt(Jumlah_Baris) <= 0){
			ValueCheck['jumlah']	= {'nilai':'','error':'No tool wa selected. Please select at least one record..'};
		}
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form-proses')[0]);
			const ParamProcess	= {
				'action'		: 'save_create_technician_process',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href = base_url +'/'+ active_controller;
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn_kembali, #btn_process_spk').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn_kembali, #btn_process_spk').prop('disabled',false);
            return false;
		}
		
		
	});
	
	
	

</script>
