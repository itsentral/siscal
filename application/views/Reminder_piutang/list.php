<?php
$this->load->view('include/side_menu'); 
?> 
<div class="box box-warning">
	<div class="box-header">
		<h4 class="box-title"><i class="fa fa-check-square"></i> <?php echo $title;?></h4>
		<div class="box-tools pull-right">	
			<?php
				if($akses_menu['create'] == '1'){
					echo form_button(array('type'=>'button','class'=>'btn btn-md btn-success','id'=>'btn-create','content'=>'ADD LETTER'));
				}
				
			?>
		</div>		
	</div>
    <!-- /.box-header -->
	<div class="box-body">
        <table id="my-grid" class="table table-bordered table-striped">
			<thead>
				<tr class="bg-blue">
					<th class="text-center">Nomor</th>
					<th class="text-center">Date</th>
					<th class="text-center">Customer</th>
					<th class="text-center">PIC Name</th>
					<th class="text-center">PIC Email</th>
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
<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var arr_akses			= <?php echo json_encode($akses_menu);?>;
    $(function() {		
		 $('#btn-create').click(function(){
			 loading_spinner();
			 window.location.href =  base_url+active_controller+'/create_letter';
		 });
		data_display();
		
	});
	

	function data_display(){
		var cabang		= $('#kdcab').val();
		var table_data = $('#my-grid').dataTable( {
			"paging"	: true,
			"processing": true,
			"serverSide": true,
			'destroy'	: true,
			"ajax": {
				"url"	:  base_url + active_controller+'/get_data_display',
				"type"	: "POST"
				/*
				"data"	:{'cabang':cabang}
				*/
							
			},		 
			"columns": [
				{"data":"nomor_surat","sClass":"text-center"},
				{"data":"datet","sClass":"text-center"},
				{"data":"customer_name","sClass":"text-left"},
				{"data":"pic_name","sClass":"text-center"},
				{"data":"pic_email","sClass":"text-left"},
				{"data":"sts_letter","sClass":"text-center","searchable":false},
				{"data":"action","sClass":"text-center","searchable":false}
			],
			"rowCallback": function(row,data,index,iDisplayIndexFull){
				//console.log(data.tool_id);
				var flag_batal	= data.sts_letter;
				var OK_Edit		= 1;
				if(flag_batal=='CNC'){
					var Template2	='<span class="badge bg-red">CANCELED</span>';
					OK_Edit			= 0;
				}else if(flag_batal=='OPN') {
					var Template2	='<span class="badge bg-green">OPEN</span>';					
				}else if(flag_batal=='CLS') {
					var Template2	='<span class="badge bg-maroon">CLOSE</span>';
					OK_Edit			= 0;
				}
				
				var Template		='<a href="'+base_url + active_controller+'/view_letter/'+data.id+'" class="btn btn-sm btn-default" title="View Detail"><span class="glyphicon glyphicon-search"></span></a>';
				if(OK_Edit ==1){
					if(arr_akses['download']==1){
						Template		+='&nbsp;&nbsp;&nbsp;<a href="#" class="btn btn-sm btn-primary" onClick="previewPDF('+'\''+data.id+'\''+');"><span class="glyphicon glyphicon-print"></span></a>';
					}
					if(arr_akses['delete']==1){
						Template		+='&nbsp;&nbsp;&nbsp;<a href="'+base_url + active_controller+'/cancel_letter/'+data.id+'" class="btn btn-sm btn-danger" title="Cancel Letter"><span class="glyphicon glyphicon-trash"></span></a>';
					}
					if(arr_akses['update']==1){
						Template		+='&nbsp;&nbsp;&nbsp;<a href="'+base_url + active_controller+'/email_letter/'+data.id+'" class="btn btn-sm btn-info" title="Send Email"><i class="fa fa-envelope"></i></a>';
					}
				}	
				
				$('td:eq(5)',row).html(Template2);
				$('td:eq(6)',row).html(Template);
			},
			"order": [[1,"desc"]]
		});
		
	}
	function previewPDF(kode_letter){
		var Links		= base_url+active_controller+'/print_letter/'+kode_letter+'/D';
		window.open(Links,'_blank');
	}

</script>
