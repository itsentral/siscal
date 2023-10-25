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
				<div class="col-sm-2">
					<div class="form-group">					
						<label class="control-label">
							<strong>Technician</strong>
						</label>
						<div>
							<select name="teknisi" id="teknisi" class="form-control select2" style="width:100%;">
								<option value="">- Choose Technician -</option>
								<?php
									if($rows_teknisi){
										foreach($rows_teknisi as $key => $value){
											$selected = '';											
											echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
										}
									}
								?>
							</select>
						</div>
						
					</div>
				</div>
				
				<div class="col-sm-2">
					<div class="form-group">					
						<label class="control-label">
							<strong>Month</strong>
						</label>
						<div>
							<select name="bulan" id="bulan" class="form-control select2" style="width:100%;">
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
				<div class="col-sm-1">
					<div class="form-group">
						
						<label class="control-label">
							<strong>Year</strong>
						</label>
						<div>
							<select name="tahun" id="tahun" class="form-control select2" style="width:100%;">
								
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
							<button type="button" class="btn btn-sm bg-maroon-active" id="btn_download_summary" title="DOWNLOAD EXCEL"> DOWNLOAD SUMMARY <i class="fa fa-download"></i> </button>
							&nbsp;&nbsp;<button type="button" class="btn btn-sm bg-navy-active" id="btn_download_detail" title="DOWNLOAD EXCEL"> DOWNLOAD DETAIL <i class="fa fa-file"></i> </button>
						</div>
					</div>
				</div>
				<?php
				}
				?>
			</div>
		</div>
		<div class="box-body" style="overflow-x:scroll !important;">
			<div class="row col-md-2 col-md-offset-5" id="loader_proses">
				<div class="loader">
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>
			<div id="div_list_table">
				
			</div>
			
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
	
	.bg-navy-blue{
		background-color: #16697A !important;
		color	: #ffffff !important;
	}
	.blue-grey{
		background-color : #546e7a !important;
		color : #ffffff !important;
	}
	.text-up{
		text-transform:uppercase !important;
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
	.text-amber{
		color : #ff6f00 !important;
	}
	
	.text-yellow{
		color : #f9a825  !important;
	}
	
	.text-brown{
		color : #5d4037 !important;
	}
	
	.text-blue-grey{
		color : #37474f !important;
	}
	
	.text-green{
		color : #1b5e20 !important;
	}
	.text-blue{
		color : #01579b !important;
	}
	
	.text-teal{
		color : #00695c !important;
	}
	
	.text-red{
		color : #c62828 !important;
	}
	
	.text-purple{
		color : #7b1fa2 !important;
	}
	
	.text-maroon{
		color : #c2185b !important;
	}
	 
</style>
<script type="text/javascript">
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(function() {	
		$('#loader_proses').hide();
		data_display();
		
		
	});
	$(document).on('change','#teknisi',data_display);
	$(document).on('change','#bulan',data_display);
	$(document).on('change','#tahun',data_display);
	
	
	function ViewDetailTrans(CodeAction){
		let TitleAction	= 'DETAIL PRODUCTIVITY';
		
		loading_spinner_new();
		
		$('#MyModalTitle').text(TitleAction);		
        $.post(base_url +'/'+ active_controller+'/preview_detail_productivity',CodeAction, function(response) {
			close_spinner_new();
            $("#MyModalDetail").html(response);
        });
		$("#MyModalView").modal('show');		
	}
	
	
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
		let TechChosen		= $('#teknisi').val();
		
		let LinkProcess		= base_url +'/'+ active_controller+'/get_data_display';
		$('#loader_proses').show();
		$('#div_list_table').html('');
		
		 $.post(LinkProcess,{'bulan':MonthChosen,'tahun':YearChosen,'teknisi':TechChosen}, function(response) {
			$('#loader_proses').hide();
			$('#div_list_table').html(response);
           
        });
		
	}
	
	$(document).on('click','#btn_download_summary', ()=>{
		let Chosen_Month		= $('#bulan').val();
		let Chosen_Year			= $('#tahun').val();
		let Chosen_Technician	= $('#teknisi').val();
		let Link_Download	= base_url+'/'+active_controller+'/download_summary_productivity?bulan='+encodeURIComponent(Chosen_Month)+'&tahun='+encodeURIComponent(Chosen_Year)+'&teknisi='+encodeURIComponent(Chosen_Technician);
		window.open(Link_Download,'_blank');
	});
	
	

	$(document).on('click','#btn_download_detail', ()=>{
		let Chosen_Month		= $('#bulan').val();
		let Chosen_Year			= $('#tahun').val();
		let Chosen_Technician	= $('#teknisi').val();
		let Link_Download	= base_url+'/'+active_controller+'/download_detail_productivity?bulan='+encodeURIComponent(Chosen_Month)+'&tahun='+encodeURIComponent(Chosen_Year)+'&teknisi='+encodeURIComponent(Chosen_Technician);
		window.open(Link_Download,'_blank');
	});
</script>
