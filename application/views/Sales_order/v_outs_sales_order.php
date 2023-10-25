<?php
$this->load->view('include/side_menu'); 
?> 
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		<div class="box-header">
			<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
			<div class="box-tools pull-right">
				<button type="button" id="btn_list_cancel" class="btn btn-md text-center" style="background-color:#37474f; color:white;vertical-align:middle !important;" title="LIST CANCELLATION RECEIVE"> LIST CANCELLATION RECEIVE <i class="fa fa-long-arrow-right" style="width:40px;"></i> </button>
			</div>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Month</label>
						<div>
						<?php
							$rows_month	= array(
								''	   => 'All Month',
								'1'    => 'January'
								,'2'   => 'February'
								,'3'   => 'March'
								,'4'   => 'April'
								,'5'   => 'May'
								,'6'   => 'June'
								,'7'   => 'July'
								,'8'   => 'August'
								,'9'   => 'September'
								,'10'   => 'October'
								,'11'   => 'November'
								,'12'   => 'December'
							);
							echo form_dropdown('bulan', $rows_month, date('n'), array('class'=>'form-control chosen-select','id'=>'bulan'));
						?>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Year</label>
						<select name="tahun" id="tahun" class="form-control chosen-select">
							<?php
								echo "<option value='all'>ALL YEAR</option>";	
								$thn_skr = date('Y');
								for ($x = $thn_skr; $x >= '2018'; $x--) {
									$selectedthn = ($x == $thn_skr ? 'selected' : '');
									echo '<option value="'.$x.'" '.$selectedthn.'>'.$x.'</option>';
								}
							?>
						</select>
					</div>
				</div>	
			</div>
			<div id="Loading_tes" class="overlay_load">
				<center>Please Wait . . .  &nbsp;<img src="<?php echo base_url('assets/img/loading_small.gif') ?>"></center>
			</div>
			<table id="my-grid" class="table table-bordered table-striped">
				<thead>
					<tr style="background-color :#16697A !important;color : white !important;">
						<th class="text-center">No</th>
						<th class="text-center">Receive No</th>
						<th class="text-center">Receive Date</th>				
						<th class="text-center">Quotation</th>
						<th class="text-center">Customer</th>
						<th class="text-center">Marketing</th>
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
					<button class="close"   aria-label="close" id="btn-modal-close">
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
	$(document).on('click','#btn-modal-close',()=>{
		$("#MyModalDetail").html('');
		$('#MyModalTitle').text('');
		$("#MyModalView").modal('hide');
	});
	$(document).on('change','#bulan',data_display);
	$(document).on('change','#tahun',data_display);
	
	
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
		let MonthChosen		= $('#bulan').val();
		let YearChosen		= $('#tahun').val();
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
				{"targets":3,"sClass":"text-center"},
				{"targets":4,"sClass":"text-left"},
				{"targets":5,"sClass":"text-center"},
				{"targets":6,"sClass":"text-center","searchable":false,"orderable": false}
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 10,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/get_data_display',
				type	: "post",
				cache	: false,
				data	: {'bulan':MonthChosen,'tahun':YearChosen},
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	$(document).on('click','#btn_list_cancel',()=>{
		window.location.href	= base_url+'/'+active_controller+'/list_cancel_receive';
	});
	
	$(document).on('click','#btn_process_save_cancel', async(e)=>{
		e.preventDefault();
		$('#btn-modal-close, #btn_process_save_cancel').prop('disabled',true);
		
		let CancelReason = $('#cancel_reason').val();
		
		const ValueCheck	= {
			'alasan':{'nilai':CancelReason,'error':'Empty Cancel Reason. Please input reason first..'}
		};
		
		let JumDelete		= $('#list_detail_cancel').find('tr');
		if(parseInt(JumDelete) <= 0){
			ValueCheck['jum_baris']	=	{'nilai':'','error':'No record was selected to process..'};
		}
		
		
		try{			
			const ResultCheck		= await GeneralCheckEmptyValue(ValueCheck);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form_proses_cancel_receive')[0]);
			const ParamProcess	= {
				'action'		: 'save_cancel_recieve_tool',
				'parameter'		: formData,
				'loader'		: 'loader_proses_save'
			};			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess);
			
			if(Hasil_Bro.status == '1'){
				GeneralShowMessageError('success',Hasil_Bro.pesan);
				window.location.href	= base_url+'/'+active_controller+'/list_cancel_receive';
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-modal-close, #btn_process_save_cancel').prop('disabled',false);
				return false;
			}			
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-modal-close, #btn_process_save_cancel').prop('disabled',false);
            return false;
		}
	});
	
	function printReceive(code_receive){
		let LinkPrint = base_url +'/'+ active_controller+'/print_warehouse_receive?code='+encodeURIComponent(code_receive);
		window.open(LinkPrint,'_blank');
	}
</script>
