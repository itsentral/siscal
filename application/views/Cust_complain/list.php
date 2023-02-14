<?php
$this->load->view('include/side_menu'); 
?> 
<div class="box box-warning">
	<div class="box-header">
		<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
		<div class="box-tools pull-right">	
			<?php
				if($akses_menu['create'] == '1'){
					echo form_button(array('type'=>'button','class'=>'btn btn-md btn-success','id'=>'btn-create','content'=>'CREATE VOC'));
				}
				
			?>
		</div>		
	</div>
    <!-- /.box-header -->
	<div class="box-body">
		<!--
		<div class="form-group row">
			<label class="control-label col-sm-2 col-xs-4">Period</label>
			<div class="col-sm-6 col-xs-8">
				<?php
					echo form_input(array('id'=>'periode','name'=>'periode','class'=>'form-control input-sm','readOnly'=>true),date('F Y'));
				?>
			</div>
		</div>
		!-->
        <table id="my-grid" class="table table-bordered table-striped">
			<thead>
				<tr class="bg-blue">
					<th class="text-center">Nomor</th>
					<th class="text-center">Date</th>
					<th class="text-center">Customer</th>
					<th class="text-center">Sales Order</th>
					<th class="text-center">PIC</th>
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
<style>
	.ui-datepicker-calendar{
		display : none;
	}
</style>

<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var arr_akses			= <?php echo json_encode($akses_menu);?>;
	
    $(function() {
		$("#periode").datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'MM yy',
			onClose: function(dateText, inst) { 
				$(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
				data_display();
			}
		});
		 $('#btn-create').click(function(){
			 loading_spinner();
			 window.location.href =  base_url+active_controller+'/create_complain';
		 });
		data_display();
		
	});
	

	function data_display(){
		var periode		= $('#periode').val();
		var table_data = $('#my-grid').dataTable( {
			"paging"	: true,
			"processing": true,
			"serverSide": true,
			'destroy'	: true,
			"ajax": {
				"url"	:  base_url + active_controller+'/get_data_display',
				"type"	: "POST"
				/*
				"data"	:{'periode':periode}
				*/
							
			},		 
			"columns": [
				{"data":"nomor","sClass":"text-center"},
				{"data":"datet","sClass":"text-center"},
				{"data":"customer_name","sClass":"text-left"},
				{"data":"no_so","sClass":"text-left"},
				{"data":"pic_name","sClass":"text-left"},
				{"data":"sts_voc","sClass":"text-center"},
				{"data":"action","sClass":"text-center","searchable":false}
			],
			"rowCallback": function(row,data,index,iDisplayIndexFull){
				//console.log(data.tool_id);
				let flag_sts	= data.sts_voc;
				
				let OK_Edit		= 0;
				let Template2	= '';
				if(flag_sts=='CNC'){
					Template2	='<span class="badge bg-red">CANCELED</span>';					
				}else if(flag_sts=='PRG'){
					Template2	='<span class="badge bg-blue">ON PROGRESS</span>';					
				}else if(flag_sts=='OPN') {
					Template2	='<span class="badge bg-green">OPEN</span>';
					OK_Edit			= 1;
				}else if(flag_sts=='CLS'){
					Template2	='<span class="badge bg-orange">FINISH JOB</span>';					
				}else if(flag_sts=='CLA'){
					Template2	='<span class="badge bg-maroon">CLOSE VoC</span>';					
				}else if(flag_sts=='FOL'){
					Template2	='<span class="badge bg-info">FOLLOW UP</span>';					
				
				}
				
				let Template		='<a href="'+base_url + active_controller+'/view_voc/'+data.id+'" class="btn btn-sm btn-default" title="View Voc"><i class="fa fa-search"></i></a>';
				if(OK_Edit ==1){
					/*
					if(arr_akses['download']==1){
						Template		+='&nbsp;<a href="#" class="btn btn-sm btn-primary" onClick="previewPDF('+'\''+data.id+'\''+');" title="Print Voc"><i class="fa fa-print"></i></a>';
					}
					*/
					if(arr_akses['update']==1){
						Template		+='&nbsp;<a href="'+base_url + active_controller+'/update_voc/'+data.id+'" class="btn btn-sm btn-info" title="Follow Up VoC"><i class="fa fa-send"></i></a>';
					}
					
					if(arr_akses['delete']==1){
						Template		+='&nbsp;<a href="'+base_url + active_controller+'/cancel_voc/'+data.id+'" class="btn btn-sm btn-danger" title="Cancel VoC"><i class="fa fa-trash"></i></a>';
					}
					
				}	
				$('td:eq(5)',row).html(Template2);
				$('td:eq(6)',row).html(Template);
			},
			"order": [[1,"desc"]]
		});
		
	}
	function previewPDF(kode_letter){
		var Links		= base_url+active_controller+'/print_voc/'+kode_letter;
		window.open(Links,'_blank');
	}

</script>
