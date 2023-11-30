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
											$selected = 'selected';
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
									for($i = date('Y'); $i >= 2018; $i--){
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
				<?php
				if($akses_menu['download'] == '1'){
				?>
				<div class="col-sm-6">
					<div class="form-group">					
						<label class="control-label">
							&nbsp;
						</label>
						<div>
							<button type="button" class="btn btn-sm bg-maroon-active" id="btn_download_excel" title="DOWNLOAD EXCEL"> DOWNLOAD EXCEL <i class="fa fa-download"></i> </button>
						</div>
					</div>
				</div>
				<?php
				}
				?>
			</div>
		</div>
		<div class="box-body" style="overflow-x:scroll !important;">
			<div id="Loading_tes" class="overlay_load">
				<center>Please Wait . . .  &nbsp;<img src="<?php echo base_url('assets/img/loading_small.gif') ?>"></center>
			</div>
			<table id="my-grid" class="table table-bordered table-striped">
				<thead>
					<tr style="background-color :#16697A !important;color : white !important;">
						  <th class="text-center" rowspan="2">No Qutotation</th>
						  <th class="text-center" rowspan="2">No PO</th>
						  <th class="text-center" rowspan="2">Customer</th>
						  <th class="text-center" colspan="2">SO</th>
						  <th class="text-center" rowspan="2">No Schedule</th>
						  <th class="text-center" rowspan="2">Tool ID</th>
						  <th class="text-center" rowspan="2">Tool Name</th>
						  <th class="text-center" rowspan="2">Qty</th>
						  <th class="text-center" rowspan="2">Status</th>
						  <th class="text-center" rowspan="2">Vendor</th>					  
						  <th class="text-center" rowspan="2">Lokasi</th>
						  <th class="text-center" colspan="4">Ambil Dari Cust</th>
						  <th class="text-center" colspan="4">Kirim Ke Subcon</th>
						  <th class="text-center" colspan="5">Proses Kalibrasi</th>
						  <th class="text-center" colspan="4">Ambil Dari Subcon</th>
						  <th class="text-center" colspan="4">Kirim Ke Customer</th>
						  <th class="text-center" rowspan="2">Keterangan</th>
						  <th class="text-center" rowspan="2">Cals Notes</th>
					</tr>
					<tr style="background-color :#16697A !important;color : white !important;">
						  <th class="text-center">Nomor</th>
						  <th class="text-center">Tanggal</th>
						  <th class="text-center">Tgl Ambil</th>
						  <th class="text-center">No SPK</th>
						  <th class="text-center">No BAST</th>
						  <th class="text-center">Qty Receive</th>
						  <th class="text-center">Tgl Plan</th>
						  <th class="text-center">No SPK</th>
						  <th class="text-center">No BAST</th>
						  <th class="text-center">Qty Kirim</th>
						  <th class="text-center">Tgl Plan</th>
						  <th class="text-center">Teknisi</th>
						  <th class="text-center">Qty Inlab</th>
						  <th class="text-center">Qty Sukses</th>
						  <th class="text-center">Qty Gagal</th>
						  <th class="text-center">Tgl Plan</th>
						  <th class="text-center">No SPK</th>
						  <th class="text-center">No BAST</th>
						  <th class="text-center">Qty Ambil</th>
						  <th class="text-center">Tgl Plan</th>
						  <th class="text-center">No SPK</th>
						  <th class="text-center">No BAST</th>
						  <th class="text-center">Qty Kirim</th>
						 
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
			"aaSorting": [[ 4, "desc" ]],			
			"columnDefs": [
				{"targets":0,"sClass":"text-center"},
				{"targets":1,"sClass":"text-center"},
				{"targets":2,"sClass":"text-left text-wrap"},
				{"targets":3,"sClass":"text-center"},
				{"targets":4,"sClass":"text-center"},
				{"targets":5,"sClass":"text-center"},
				{"targets":6,"sClass":"text-center"},
				{"targets":7,"sClass":"text-left text-wrap"},
				{"targets":8,"sClass":"text-center"},
				{"targets":9,"sClass":"text-center"},
				{"targets":10,"sClass":"text-left text-wrap"},
				{"targets":11,"sClass":"text-center"},
				{"targets":12,"sClass":"text-center"},
				{"targets":13,"sClass":"text-center"},
				{"targets":14,"sClass":"text-center"},
				{"targets":15,"sClass":"text-center","searchable":false,"orderable": false},
				{"targets":16,"sClass":"text-center"},
				{"targets":17,"sClass":"text-center"},
				{"targets":18,"sClass":"text-center"},
				{"targets":19,"sClass":"text-center"},
				{"targets":20,"sClass":"text-center"},
				{"targets":21,"sClass":"text-center"},
				{"targets":22,"sClass":"text-center"},
				{"targets":23,"sClass":"text-center"},
				{"targets":24,"sClass":"text-center"},
				{"targets":25,"sClass":"text-center"},
				{"targets":26,"sClass":"text-center"},
				{"targets":27,"sClass":"text-center"},
				{"targets":28,"sClass":"text-center"},
				{"targets":29,"sClass":"text-center"},
				{"targets":30,"sClass":"text-center"},
				{"targets":31,"sClass":"text-center"},
				{"targets":32,"sClass":"text-center"},
				{"targets":33,"sClass":"text-center","searchable":false,"orderable": false},
				{"targets":34,"sClass":"text-center","searchable":false,"orderable": false}
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
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	$(document).on('click','#btn_download_excel', ()=>{
		let Chosen_Month		= $('#bulan').val();
		let Chosen_Year			= $('#tahun').val();
		let Link_Download	= base_url+'/'+active_controller+'/download_excel_tool?bulan='+encodeURIComponent(Chosen_Month)+'&tahun='+encodeURIComponent(Chosen_Year);
		window.open(Link_Download,'_blank');
	});
	
	

</script>
