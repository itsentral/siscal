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
					<button type='button' class='btn btn-md bg-maroon' id='btn-add-letter'> CREATE TECHNICIAN LETTER <i class='fa fa-plus'></i>  </button>
				<?php
				}
				?>
			</div>
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
							<select name="bulan" id="bulan" class="form-control chosen-select" style="width:100%;">
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
							<select name="tahun" id="tahun" class="form-control chosen-select" style="width:100%;">
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
				
			</div>
			
			<div id="Loading_tes" class="overlay_load">
				<center>Please Wait . . .  &nbsp;<img src="<?php echo base_url('assets/img/loading_small.gif') ?>"></center>
			</div>
			<table id="my-grid" class="table table-bordered table-striped">
				<thead>
					<tr class="bg-navy-blue">
						<th class="text-center">SPK No</th>
						<th class="text-center">Date</th>				
						<th class="text-center">Technician</th>
						<th class="text-center">Status</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>

				<tbody id="list_detail">
			   
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
	var arr_akses			= <?php echo json_encode($akses_menu);?>;
	$(function() {		
		data_display();
		
	});
	
	$(document).on('click','#btn-add-letter',(e)=>{
		e.preventDefault();
		loading_spinner();
		window.location.href	= base_url +'/'+ active_controller+'/create_spk_technician';
	});
	
	$(document).on('change','#bulan',data_display);
	$(document).on('change','#tahun',data_display);
	
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
			"aaSorting": [[ 1, "desc" ]],			
			"columnDefs": [
				{"targets":0,"sClass":"text-center"},
				{"targets":1,"sClass":"text-center"},
				{"targets":2,"sClass":"text-left"},
				{"targets":3,"sClass":"text-center","searchable":false,"orderable": false},
				{"targets":4,"sClass":"text-center","searchable":false,"orderable": false}
			],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 20,
			"aLengthMenu": [[5, 10, 20, 50, 100, 150], [5, 10, 20, 50, 100, 150]],
			"ajax":{
				url 	: base_url +'/'+ active_controller+'/get_data_display',
				type	: "post",
				data 	: {'bulan':MonthChosen,'tahun':YearChosen},
				cache	: false,
				beforeSend: function() {
					$('#Loading_tes').show();
				}, 
				complete: function() {
					$('#Loading_tes').hide();
				},
				error	: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="5">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		});
	}
	
	function PreviewSPK(kode){
		$('#Mymodal-detail').empty();
		
		var baseurl		= base_url +'/'+ active_controller+'/detail_spk_letter';
		$.ajax({
			url			: baseurl,
			type		: "POST",
			data		: {'kode_spk':kode},
			beforeSend: function() {
				$('#Loading_tes').show();
			},
			success		: function(data){
				$('#Mymodal-title').html('SPK TECHNICIAN DETAIL');
				$('#Mymodal-list').html(data);
				$('#Mymodal').modal('show');
			},
			complete: function() {
				setTimeout(function(){
					$('#Loading_tes').hide();
				}, 1500);
			},
			error: function() {				
				swal({
				  title				: "Error Message !",
				  text				: 'An Error Occured During Process. Please try again..',						
				  type				: "warning"
				});				
				return false;
			}		
			
		});
		
		
	}
	
	$(document).on('click','#btn-partial',(e)=>{
		e.preventDefault();
		loading_spinner();
		window.location.href	= base_url +'/'+ active_controller+'/list_outstanding_partial_po';
	});
	

</script>
