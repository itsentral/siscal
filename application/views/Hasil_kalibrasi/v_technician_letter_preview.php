
<form action="#" method="POST" id="form-proses" enctype="multipart/form-data">
	<div class="box box-warning">
		
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
					<h5>DETAIL SPK TECHNICIAN</h5>
				</div>
				
			</div>
			<?php
			if(empty($rows_header)){
				echo"<div class='row'>
						<div class='col-sm-12'>
							<h4 class='text-red'><b>NO RECORD WAS FOUND.....</b></h4>
						</div>
					</div>";
			}else{
				
			?>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SPK No</label>
							<?php
								echo form_input(array('id'=>'nomor_spk','name'=>'nomor_spk','class'=>'form-control input-sm','readOnly'=>true),$rows_header->id);	
								
							?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">SPK Date</label>
							<?php
								echo form_input(array('id'=>'tgl_spk','name'=>'tgl_spk','class'=>'form-control input-sm','readOnly'=>true),$rows_header->datet);						
							?>
						</div>
					</div>				
				</div>
				<div class='row'>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Technician</label>
							<?php
								echo form_input(array('id'=>'member_name','name'=>'member_name','class'=>'form-control input-sm','readOnly'=>true),$rows_header->member_name);						
							?>
						</div>
					</div>
					<div class="col-sm-6">
						&nbsp;
					</div>				
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-xs-12 text-center sub-heading" style="color:white;">
						<h5>DETAIL ITEM </h5>
					</div>
					
				</div>
				<div class="row">
					<div class="col-sm-12" style="overflow-x:scroll !important;">
						<table class="table table-striped table-bordered" id="my-grid">
							<thead>
								<tr class="bg-navy-active">
									<th class="text-center">No</th>
									<th class="text-center">Tool Name</th>
									<th class="text-center">Company</th>				
									<th class="text-center">SO No</th>
									<th class="text-center">Qty</th>
									<th class="text-center">Type</th>
									<th class="text-center">Plan Date</th>
								</tr>
							</thead>
							<tbody id="list_detail">
								<?php
								if($rows_detail){
									$intL	= 0;
									foreach($rows_detail as $ketD=>$valD){
										$intL++;
										$Nomor_SO = $Company = $Plan_Date = '-';
										$Query_SO	= "SELECT no_so,customer_name,plan_process_date,plan_time_start FROM trans_details WHERE id = '".$valD->detail_id."'";
										$rows_SO	= $this->db->query($Query_SO)->row();
										if($rows_SO){
											$Nomor_SO	= $rows_SO->no_so;
											$Company	= $rows_SO->customer_name;
											$Plan_Date	= date('d M Y H:i',strtotime($rows_SO->plan_process_date.' '.$rows_SO->plan_time_start));
										}
										
										if($valD->category == 'INSITU'){
											$jenis		='<span class="badge bg-maroon">Insitu</span>';
										}else{
											$jenis		='<span class="badge bg-green">Labs</span>';
										}
										
										echo"<tr>";	
											echo"<td align='center'>".$intL."</td>";
											echo"<td align='left'>".$valD->tool_name."</td>";
											echo"<td align='left'>".$Company."</td>";
											echo"<td align='center'>".$Nomor_SO."</td>";										
											echo"<td align='center'>".number_format($valD->qty)."</td>";
											echo"<td align='center'>".$jenis."</td>";
											echo"<td align='center'>".$Plan_Date."</td>";
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
</form>


<style>
	.sub-heading{
		border-radius :5px;
		background-color :#03506F;
		color : white;
		margin : 20px 10px 15px 10px !important;
		width :98% !important;
	}
	.ui-spinner-input{
		padding :10px 5px 10px 10px !important;
	}
</style>

