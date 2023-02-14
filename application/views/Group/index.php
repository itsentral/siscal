<?php
$this->load->view('include/side_menu'); 
?>    
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><i class="fa fa-folder-o"></i> <?=$title;?></h3>
		<div class="box-tool pull-right">
		<?php
			if($akses_menu['create']=='1'){
		?>
		  <button type="button" class="btn btn-sm btn-success" id='btn-add'>
			<i class="fa fa-plus"></i> Add
		  </button>
		  <?php
			}
		  ?>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped"> 
			<thead>
				<tr class='bg-blue'>
					<th class="text-center" width="10px">No.</th>
					<th class="text-center">Group</th>
					<th class="text-center">Description</th>
					<th class="text-center">Option</th>
				</tr>
			</thead>
			<tbody>
			  <?php 
			  if($row){
					$int =0;
					foreach($row as $datas){
						$int++;						
						echo"<tr>";							
							echo"<td align='center'>$int</td>";
							echo"<td align='left'>".$datas['name']."</td>";
							echo"<td align='left'>".$datas['descr']."</td>";
							echo"<td align='center'>";
								if($akses_menu['update']=='1'){
									echo"<a href='".site_url('group/edit_group/'.$datas['id'])."' class='btn btn-sm btn-primary' title='Edit Group' data-role='qtip'><i class='fa fa-edit'></i></a> &nbsp;"; 
								}
								if($akses_menu['create']=='1' || $akses_menu['update']=='1'){
									echo"<a href='".site_url('group/access_menu/'.$datas['id'])."' class='btn btn-sm btn-success' title='Manage Access' data-role='qtip'><i class='fa fa-recycle'></i></a>";
								}
							echo"</td>";
						echo"</tr>";
					}
			  }
			  ?>
			</tbody>
		</table>
	</div>
	<!-- /.box-body -->
 </div>
  <!-- /.box -->

<?php $this->load->view('include/footer'); ?>
<script>
	$(document).ready(function(){
		$('#btn-add').click(function(){
			$('#spinner').modal('show');
			window.location.href = base_url +'index.php/'+ active_controller+'/add';
		});
		
	});
	
</script>
