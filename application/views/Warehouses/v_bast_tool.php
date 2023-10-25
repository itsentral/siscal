<?php $this->load->view("include/side_menu");?>
<style>

	.table-bordered {
		border : 1px solid#ccc;
	}
	.table-bordered tbody tr td {
		border : 1px solid#ccc;
		vertical-align:middle;
	}
	.table-bordered thead tr th, .table-bordered thead tr td {
		border : 1px solid#ccc;
		vertical-align:middle;
	}
	.table-bordered tfoot tr td, .table-bordered tfoot tr th {
		border : 1px solid#ccc;
		vertical-align:middle;
	}
	
	/* .nav-tabs > li.active > a, 
	.nav-tabs > li.active > a:focus, 
	.nav-tabs > li.active > a:hover {
		background-color: #3AC8F8;
		color:#fff;	
	} */
	.lebar_col{
		white-space:nowrap;
	}
	.ceklist { /* Change width and height */
		width:1.4em;
		height:1.4em;
	}
	
	
	/* LOADER */
	.loader span{
	  display: inline-block;
	  width: 12px;
	  height: 12px;
	  border-radius: 100%;
	  background-color: #3498db;
	  margin: 35px 5px;
	  opacity: 0;
	}

	.loader span:nth-child(1){
		background: #4285F4;
	  	animation: opacitychange 1s ease-in-out infinite;
	}

	.loader span:nth-child(2){
  		background: #DB4437;
	 	animation: opacitychange 1s ease-in-out 0.11s infinite;
	}

	.loader span:nth-child(3){
  		background: #F4B400;
	  	animation: opacitychange 1s ease-in-out 0.22s infinite;
	}
	.loader span:nth-child(4){
  		background: #0F9D58;
	  	animation: opacitychange 1s ease-in-out 0.44s infinite;
	}

	@keyframes opacitychange{
	  0%, 100%{
		opacity: 0;
	  }

	  60%{
		opacity: 1;
	  }
	}
	
	.text-center{
		vertical-align :middle !important;
		text-align	: center !important;
	}
	.text-left{
		vertical-align :middle !important;
		text-align	: left !important;
	}
	.text-right{
		vertical-align :middle !important;
		text-align	: right !important;
	}
	
	.text-wrap{
		word-wrap	: break-word !important;
	}
	
	.bg-navy-blue{
		background-color: #16697A !important;
		color	: #ffffff !important;
	}

</style>
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title text-red text-bold"><i class="fa fa-credit-card"></i> <?php echo $title;?></h3>
		
	</div>
    <div class="box-body">
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					
					<label class="control-label">
						<strong>Month</strong>
					</label>
					<div>
						<select name="bulan" id="bulan" class="form-control select2" style="width:100%;">
							<option value="">- Choose Month -</option>
							<?php
								$array_bulan = array(
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

								foreach($array_bulan as $key => $value){
									$selected = '';
									if($key == date('n')){
										//$selected = 'selected';
									}
									echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
								}
							?>
						</select>
					</div>
					
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					
					<label class="control-label">
						<strong>Year</strong>
					</label>
					<div>
						<select name="tahun" id="tahun" class="form-control select2" style="width:100%;">
							<option value="">- Choose Year -</option>
							<?php
								for($i = 2018; $i <= date('Y'); $i++){
									$selected = '';
									if(date('Y') == $i){
										$selected = 'selected';
									}
									echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
								}
							?>
						</select>
					</div>
					
				</div>
			</div>
			
		</div>
		<div class="row col-md-2 col-md-offset-5" id="loader_proses">
			<div class="loader">
				<span></span>
				<span></span>
				<span></span>
				<span></span>
			</div>
		</div>
		<div id="div_master_violation">
			<table id="my-grid2" class="table table-bordered table-striped">
				<thead>
					<tr class="bg-navy-blue">
						<th class="text-center">Bast No</th>
						<th class="text-center">Bast Date</th>
						<th class="text-center">Customer</th>
						<th class="text-center">SO No</th>						
						<th class="text-center">Quotation</th>
						<th class="text-center">PO No</th>
						<th class="text-center">Status</th>
						<th class="text-center">Action</th>					
					</tr>
				</thead>

				<tbody id="list_master_violation">
			   
				</tbody>
				
			</table>
		</div>
		
    </div>
</div>
<div class="modal fade" id="MyModal" tabindex="-1" role="dialog" aria-labelledby="MyModal" data-backdrop="static">
    <div class="modal-dialog" role="document" style="min-width:75% !important;">
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

<?php $this->load->view("include/footer"); ?>
<script>
	var base_url				= '<?php echo site_url(); ?>';
	var active_controller		= '<?php echo($this->uri->segment(1)); ?>';
	var _validFileExtensions 	= [".jpg", ".jpeg", ".png", ".gif", ".bmp", ".pdf",".PDF",".JPEG",".JPG"];
	$(document).ready(function(){
		GetDisplayData();		
	});
	
	
	
	$(document).on('change','#bulan',GetDisplayData);
	$(document).on('change','#tahun',GetDisplayData);
	
	function view_bast(CodeBast){
		loading_spinner_new();      
		window.location.href = base_url +'/'+active_controller+'/view_detail_bast?nobast='+encodeURIComponent(CodeBast);			
	}
	
	function GetDisplayData(){
		let ChosenMonth		= $('#bulan').val();
		let ChosenYear		= $('#tahun').val();
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
					{"targets":5,"sClass":"text-center"},
					{"targets":6,"sClass":"text-center"},
					{"targets":7,"sClass":"text-center","searchable":false,"orderable":false}
				],
				
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 50,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/get_display_list',
				type	: "post",
				data	:{'bulan':ChosenMonth,'tahun':ChosenYear},
				cache	: false,
				beforeSend: function() {
					$('#loader_proses').show();
				}, 
				complete: function() {
					$('#loader_proses').hide();
				},
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	
	
	function formatNumber(value) {
		return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	
	
	function ValidateSingleInput(oInput) {
		if (oInput.type == "file") {
			var sFileName = oInput.value;
			 if (sFileName.length > 0) {
				var blnValid = false;
				for (var j = 0; j < _validFileExtensions.length; j++) {
					var sCurExtension = _validFileExtensions[j];
					if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
						blnValid = true;
						break;
					}
				}
				 
				if (!blnValid) {
					swal("Warning!", "Hanya boleh pilih jenis file IMAGES atau PDF","warning");
					oInput.value = "";
					return false;
				}
			}
		}
    	return true;
	}
		
	
	
	function PrintBAST(KodeBast){
		let UlrPrint	= base_url +'/'+active_controller+'/print_bast_start_contract?nobast='+encodeURIComponent(KodeBast);
		window.open(UlrPrint,'_blank');
	}
	
	/*
	| ---------------------------- |
	|  UPDATE RECEIVE BAST KONTRAK |
	| ---------------------------- |
	*/
	
	$(document).on('click','#btn-save-receive',async(e)=>{
		e.preventDefault();
		$('#btn-save-receive, #btn-close').prop('disabled',true);
		let File_Bast			= $('#files_bast').val();
		
		const CheckParam	= {
			'file_bast' : {'nilai':File_Bast,'error':'Empty Bast File, please upload bast file first....'}
		};
		
		let intD 	= 0;
		let intC	= 0;
		let intUrut	= 0;
		$('#list_detail_container').find('tr').each(function(){
			intUrut++;
			let CodeRows	= $(this).attr('id');
			const SplitRows	= CodeRows.split('_');
			let urutRows	= SplitRows[1];
			
			let Receive_By	= $('#receive_by_'+urutRows).val();
			let Receive_Date= $('#receive_date_'+urutRows).val();
			
			if(Receive_By == '' || Receive_By ==  null || Receive_By == '-'){				
				CheckParam['receive_by'+intUrut]	= {'nilai':'','error':'Empty Receiver name at rows '+intUrut};
			}
			
			if(Receive_Date == '' || Receive_Date ==  null){
				CheckParam['receive_date'+intUrut]	= {'nilai':'','error':'Empty Receive date at rows '+intUrut};
			}
			
			
			
		});
		
		
		
		
		try{
			const RestCheckValue	= await GeneralCheckEmptyValue(CheckParam);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form_proses_update')[0]);
			const ParamProcess	= {
				'action'		: 'save_update_receive_bast_contract',
				'parameter'		: formData,
				'loader'		: '#loader_proses_save'
			};
			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess)
			//console.log(Hasil_Bro.status);
			
			if(Hasil_Bro.status == '1'){				
				let CodeRequest	= Hasil_Bro.code;	
				let SuccessMessage = Hasil_Bro.pesan;
				let CodeBast	   = Hasil_Bro.code;
				const NewContract  = Hasil_Bro.new_contract;
				
				if(CodeBast == '' || CodeBast == null){
					window.location.href = base_url+'/'+active_controller;
				}else{
					$('#loader_proses_save').show();
					/*
					# SEND EMAIL ##
					*/
					$.post(base_url+'/'+active_controller+'/SendEmailConfirm',{'code':CodeBast,'new_contract':NewContract}, function(hasil) {
						$('#loader_proses_save').hide();
						GeneralShowMessageError('success',SuccessMessage);
						window.location.href = base_url+'/'+active_controller;
					});
				}
				
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-save-receive, #btn-close').prop('disabled',false);
				return false;
			}
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-save-receive, #btn-close').prop('disabled',false);
            return false;
		}
		
	});
	
	
	/*
	| -------------------------- |
	|   UPDATE CONTRACT PERIOD   |
	| -------------------------- |
	*/
	
	$(document).on('click','#btn-save-periode',async(e)=>{
		e.preventDefault();
		$('#btn-save-periode, #btn-close').prop('disabled',true);
		let NewStart			= $('#datefr').val();
		let NewEnd				= $('#datetl').val();
		let OldStart			= $('#datefr_old').val();
		let UpdateReason		= $('#update_reason').val();
		
		const CheckParam	= {
			'alasan' : {'nilai':UpdateReason,'error':'Empty Update Reason, please input update reason first....'},
			'datefr' : {'nilai':NewStart,'error':'Empty Start Contract Date, please input start contract date first....'},
			'datetl' : {'nilai':NewEnd,'error':'Empty End Contract Date, please input end contract date first....'}
		};
		
		if(NewStart == OldStart){
			CheckParam['validdate']	= {'nilai':'','error':'Incorrect New Start Contract Date. New date cannot be the same with previous date ....'};
		}
		
		try{
			const RestCheckValue	= await GeneralCheckEmptyValue(CheckParam);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form_proses_period')[0]);
			const ParamProcess	= {
				'action'		: 'save_update_contract_period',
				'parameter'		: formData,
				'loader'		: '#loader_proses_save'
			};
			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess)
			//console.log(Hasil_Bro.status);
			
			if(Hasil_Bro.status == '1'){				
				let CodeRequest	= Hasil_Bro.code;	
				let SuccessMessage = Hasil_Bro.pesan;
				GeneralShowMessageError('success',SuccessMessage);
				window.location.href = base_url+'/'+active_controller;
							
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-save-periode, #btn-close').prop('disabled',false);
				return false;
			}
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-save-periode, #btn-close').prop('disabled',false);
            return false;
		}
		
	});
	
	/*
	| -------------------------- |
	|    UPDATE CHANGE VEHICLE   |
	| -------------------------- |
	*/
	
	$(document).on('click','#btn-save-change-unit',async(e)=>{
		e.preventDefault();
		$('#btn-save-change-unit, #btn-close').prop('disabled',true);
		let UpdateReason		= $('#change_reason').val();
		let SelectedRows		= $('#detail_container_change input[type="checkbox"]:checked').length
		const CheckParam	= {
			'alasan' : {'nilai':UpdateReason,'error':'Empty Update Reason, please input update reason first....'}
		};
		
		if(parseInt(SelectedRows) <= 0){
			CheckParam['jum_rows']	= {'nilai':'','error':'No records was selected to process. Please choose at least one record ....'};
		}else{
			$('#detail_container_change input[type="checkbox"]:checked').each(function(){
				let CheckCode	= $(this).attr('id');
				const SplitCode	= CheckCode.split('_');
				let UrutCode	= SplitCode[1];
				if($('#lambung_new_'+UrutCode).is(':checked')){
					let NopolChecked	= $('#lambung_new_'+UrutCode).val();
					if(NopolChecked == '' ||  NopolChecked == null){
						CheckParam['nopol'+UrutCode]	= {'nilai':'','error':'Empty new vehicle at rows '+UrutCode};
					}
				}
				
			});
		}
		
		try{
			const RestCheckValue	= await GeneralCheckEmptyValue(CheckParam);
			const ResultConfirm		= await GeneralShowConfirmSave();
			const formData 			= new FormData($('#form-change-vehicle')[0]);
			const ParamProcess	= {
				'action'		: 'save_update_contract_change_vehicle',
				'parameter'		: formData,
				'loader'		: '#loader_proses_save'
			};
			
			const Hasil_Bro			= await GeneralAjaxProcessData(ParamProcess)
			//console.log(Hasil_Bro.status);
			
			if(Hasil_Bro.status == '1'){				
				let CodeRequest	= Hasil_Bro.code;	
				let SuccessMessage = Hasil_Bro.pesan;
				GeneralShowMessageError('success',SuccessMessage);
				window.location.href = base_url+'/'+active_controller;
							
			}else{
				GeneralShowMessageError('error',Hasil_Bro.pesan);
				$('#btn-save-change-unit, #btn-close').prop('disabled',false);
				return false;
			}
		}catch(error){
			GeneralShowMessageError('error',error.message);
			$('#btn-save-change-unit, #btn-close').prop('disabled',false);
            return false;
		}
		
	});
	
</script>
