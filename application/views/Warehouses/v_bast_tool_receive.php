
<div class="box box-warning">		
	<div class="box-body">			
		<?php
		if(empty($rows_header)){
			echo"<div class='row'>
					<div class='col-sm-12'>
						<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
					</div>
				</div>";
		}else{
			
		?>
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL TOOL </h5>
				</div>					
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Code Tool</label>
						<?php
							echo form_input(array('id'=>'detail_tool_id','name'=>'detail_tool_id','class'=>'form-control input-sm','readOnly'=>true),$rows_header->tool_id);						
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tool Name</label>
						<?php
							echo form_input(array('id'=>'detail_tool_name','name'=>'detail_tool_name','class'=>'form-control input-sm','autocomplete'=>"off",'readOnly'=>true),$rows_header->tool_name);						
						?>
					</div>
				</div>				
			</div>
			<div class='row'>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Qty Receive</label>
						<?php
							echo form_input(array('id'=>'qty_receive','name'=>'qty_receive','class'=>'form-control input-sm','readOnly'=>true),$rows_header->qty_io);						
						?>
					</div>
				</div>
				
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Notes </label>
						<?php
							echo form_textarea(array('id'=>'descr_detail', 'name'=>'descr_detail','cols'=>'75','rows'=>'2', 'class'=>'form-control input-sm','readOnly'=>true),$rows_header->descr);								
						?>
						
					</div>
				</div>		
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL RECEIVE</h5>
				</div>				
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12">
					<table class="table table-striped table-bordered" id="my-grid-receive">
						<thead>
							<tr class="bg-navy-blue">
								<th class="text-center">Code Receive</th>
								<th class="text-center">Description</th>
								<th class="text-center">File</th>
							</tr>
						</thead>
						<tbody id="list_detail_receive">
						<?php
						if($rows_detail){
							$Path_Loc		= $this->file_attachement.'bast_tool/';
							foreach($rows_detail as $keyRec=>$valRec){
								
								$Template	= '';
								$rows_Image	= $this->db->get_where('bast_detail_tool_file',array('bast_tool_id'=>$valRec->id))->result();
								if($rows_Image){
									$intImage	= 0;
									foreach($rows_Image as $keyImage=>$valImage){
										$intImage++;
										if(!empty($Template))$Template	.='&nbsp;&nbsp;';
										$Template	.='<a href="'.$Path_Loc.$valImage->file_name.'" class="btn btn-sm btn-success" target ="_blank"> FILE '.$intImage.'</a>';
									}
									
								}
								echo"<tr>";
								
									echo"<td class='text-center'>".$valRec->trans_data_detail_id."</td>";
									echo"<td class='text-left'>".$valRec->rec_descr."</td>";
									echo"<td class='text-center'>".$Template."</td>";
								echo"</tr>";
							}
						}
						?>
						</tbody>
					</table>
				</div>				
			</div>
			
		<?php
		}
	echo'</div>';
		
		?>
		
</div>