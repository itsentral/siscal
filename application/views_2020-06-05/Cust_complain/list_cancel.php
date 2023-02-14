<?php
$this->load->view('include/side_menu'); 
?> 
<div class="box box-warning">
	<div class="box-header">
		<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
		
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
					<th class="text-center">Cancel Reason</th>
					<th class="text-center">Cancel Date</th>
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
				{"data":"cancel_reason","sClass":"text-center"},
				{"data":"cancel_date","sClass":"text-center"},
				{"data":"action","sClass":"text-center","searchable":false}
			],
			"rowCallback": function(row,data,index,iDisplayIndexFull){
				
				
				let Template		='<a href="'+base_url + active_controller+'/view_voc/'+data.id+'" class="btn btn-sm btn-warning" title="View Voc"><i class="fa fa-search"></i></a>';
				
				$('td:eq(7)',row).html(Template);
			},
			"order": [[1,"desc"]]
		});
		
	}
	
</script>
