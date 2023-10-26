<?php
$this->load->view('include/side_menu'); 
?>    
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><?=$title;?></h3>
		<div class="box-tool pull-right">
		<?php
			if($akses_menu['create']=='1'){
		?>
		  <a href="#" class="btn btn-sm btn-success" id='btn-add'>
			<i class="fa fa-plus"></i> Add
		  </a>
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
					<th class="text-center">No.</th>
					<th class="text-center">Menu</th>
					<th class="text-center">Path</th>
					<th class="text-center">Parent</th>
					<th class="text-center">Icon</th>
					<th class="text-center">Status</th>
					<th class="text-center">Option</th>
				</tr>
			</thead>
			<tbody>
			  <?php 
			  if($row){
					$int	=0;
					foreach($row as $datas){
						$int++;
						$path	= (isset($datas->path) && $datas->path)?$datas->path:'#';
						$class	= 'bg-green';
						$status	= 'Active';
						if($datas->active ==null){
							$class	= 'bg-red';
							$status	= 'Not Active';
						}
						$parent_id	= (isset($data_menu[$datas->parent_id]) && $data_menu[$datas->parent_id])?$data_menu[$datas->parent_id]:'';
						
						echo"<tr>";							
							echo"<td align='center'>$int</td>";
							echo"<td align='left'>".$datas->name."</td>";
							echo"<td align='left'>$path</td>";
							echo"<td align='left'>".$parent_id."</td>";
							echo"<td align='left'>".$datas->icon."</td>";
							echo"<td align='center'><span class='badge $class'>$status</span></td>";
							echo"<td align='center'>";
								if($akses_menu['update']=='1'){
									echo"<a href='".site_url('menu/edit/'.$datas->id)."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
								}									
								if($akses_menu['delete']=='1'){
									echo"&nbsp;<a href='#' onClick='return delData(".$datas->id.");' class='btn btn-sm btn-danger' title='Delete Data' data-role='qtip'><i class='fa fa-trash'></i></a>";
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
	function delData(id){
       var r=confirm("Do you want to delete this data?")
        if (r==true)
          window.location.href = base_url +'index.php/'+ active_controller+'/delete/'+id;
        else
          return false;
        } 
</script>
