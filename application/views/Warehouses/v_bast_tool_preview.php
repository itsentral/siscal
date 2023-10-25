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

	<div class="box box-warning">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-envelope"></i> <?php echo('<span class="important">'.$title.'</span>'); ?>
			</h3>
			
			
		</div>
		<div class="box-body">
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">BAST</label>
						<?php
							echo form_input(array('id'=>'nomor_bast','name'=>'nomor_bast','class'=>'form-control input-sm','readOnly'=>true),$rows_header->nomor);						
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Date</label>
						<?php
							echo form_input(array('id'=>'tgl_bast','name'=>'tgl_bast','class'=>'form-control input-sm','readOnly'=>true),date('d F Y',strtotime($rows_header->datet)));						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Company</label>
						<?php
							echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header->name);
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Address</label>
						<?php
							echo form_textarea(array('id'=>'address', 'name'=>'address','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_header->address);						
						?>
					</div>
				</div>				
			</div>
			
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">PIC Name</label>
						<?php
							echo form_input(array('id'=>'pic_name','name'=>'pic_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header->pic);
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">SO No</label>
						<?php
							echo form_input(array('id'=>'no_so','name'=>'no_so','class'=>'form-control input-sm','readOnly'=>true),$rows_header->no_so);
							
						?>
					</div>
				</div>								
			</div>	
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Quotation</label>
						<?php
							echo form_input(array('id'=>'quotation_nomor','name'=>'quotation_nomor','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->nomor);
							
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">PO No</label>
						<?php
							echo form_input(array('id'=>'pono','name'=>'pono','class'=>'form-control input-sm','readOnly'=>true),$rows_quot->pono);
							
						?>
					</div>
				</div>								
			</div>
			
		</div>		
	</div>
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title">
				<i class="fa fa-refresh"></i> <?php echo('<span class="important">Data Detail</span>'); ?>
			</h3>			
		</div>
		<div class="box-body">
			<table class="table table-striped table-bordered" id="my-grid">
				<thead>
					<tr class="bg-blue">
						<td class="text-center">No</td>
						<th class="text-center">Code Tool</th>
						<th class="text-center">Name Tool</th>				
						<th class="text-center">Qty</th>
						<th class="text-center">Description</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody id="list_detail">
					<?php
					if($rows_detail){
						$intI		= 0;
						
						foreach($rows_detail as $ketD=>$valD){
							$intI++;
							$Qty		= $valD->qty;
							$Qty_In		= $valD->qty_io;
							
							$Qty_Pros	= $Qty;
							if($Qty_In > 0){
								$Qty_Pros	= $Qty_In;
							}
							
							$Template	= '-';
							$rows_Unit	= $this->db->get_where('bast_detail_tools',array('bast_detail_id'=>$valD->id))->num_rows();
							if($rows_Unit > 0){
								$Template	= '<button type="button" onClick="ViewReceiveTool(\''.$valD->id.'\')" class="btn btn-sm btn-danger" title="VIEW RECEIVE TOOLS"> <i class="fa fa-search"></i> </button>';
							}
							echo"<tr>";
								
								echo"<td class='text-center'>".$intI."</td>";
								echo"<td class='text-center'>".$valD->tool_id."</td>";
								echo"<td class='text-left'>".$valD->tool_name."</td>";
								echo"<td class='text-center'>".$Qty_Pros."</td>";
								echo"<td class='text-left'>".$valD->descr."</td>";
								echo"<td class='text-center'>".$Template."</td>";
							echo"</tr>";
						}
					}
					
				echo"</tbody>";
				
				?>
			</table>
		</div>
		<?php
		echo"<div class='box-footer text-center'>";	
			echo'
				<button type="button" class="btn btn-md bg-navy-active" id="btn-back"> <i class="fa fa-long-arrow-left"></i> &nbsp;&nbsp;BACK </button>';
		
		echo"</div>";
		?>
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
<style>
	
</style>

<?php $this->load->view("include/footer"); ?>
<script>
	var base_url				= '<?php echo site_url(); ?>';
	var active_controller		= '<?php echo($this->uri->segment(1)); ?>';
	var _validFileExtensions 	= [".jpg", ".jpeg", ".png", ".gif", ".bmp", ".pdf",".PDF",".JPEG",".JPG"];
	$(document).ready(function(){
		
	});
	$(document).on('click','#btn-back',(e)=>{
		loading_spinner();
		window.location.href =  base_url+'/'+active_controller;
	});
	const ViewReceiveTool = (Code_DetReceive)=>{
		
		loading_spinner_new();
		$.post(base_url+'/'+active_controller+'/preview_receive_tool_bast_driver',{'code_rec_detail':Code_DetReceive},function(response){
			close_spinner_new();
			$('#public_title').html('PREVIEW RECEIVE TOOLS - SEND BY CUSTOMER');
			$('#detail_modal').html(response);
			$('#MyModal').modal('show');
		});
	}
	
	
	
</script>