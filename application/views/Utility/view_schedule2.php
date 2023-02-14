<?php
$this->load->view('include/side_menu'); 
?>    
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><?=$deskripsi;?></h3>
	</div>
	
	<section class="content-header">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
		<form method="post" action="<?=base_url()?>index.php/utility/tampilkan_schedule" autocomplete="off">
           
					<b>No SO : </b> <input type="text" id="no_so" name="no_so" value=""  /> <input type="submit" name="tampilkan" value="Tampilkan" onclick="return check()" class="btn btn-success pull-center">                             
						  
			  </form>
            
        </div>
    </div>
    </div>
</section>

	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr class='bg-blue'>
					<th class="text-center">No.</th>
					<th class="text-center">Id</th>
					<th class="text-center">No SO</th>
					<th class="text-center">Teknisi</th>
					<th class="text-center">Tool Name</th>
					<th class="text-center">Lokasi</th>
					<th class="text-center">Qty</th>
					<th class="text-center">Qty Proses</th>
					<th class="text-center">Qty Sisa</th>
					<th class="text-center">Option</th>
				</tr>
			</thead>
			<tbody>
			  <?php 
			  if($row){
					$int	=0;
					foreach($row as $datas){
						$int++; 
						
						echo"<tr>";							
							echo"<td align='center'>$int</td>";
							echo"<td align='left'>".$datas->id."</td>"; 
							echo"<td align='left'>".$datas->no_so."</td>"; 
							echo"<td align='left'>".$datas->teknisi_name."</td>"; 
							echo"<td align='left'>".$datas->tool_name."</td>";
							echo"<td align='left'>".$datas->location."</td>";
                            echo"<td align='left'>".$datas->qty."</td>";	
                            echo"<td align='left'>".$datas->qty_proses."</td>";		
                            echo"<td align='left'>".$datas->qty_sisa."</td>";								
							echo"<td align='center'>";
								if($akses_menu['update']=='1'){
									echo"<a href='".site_url('utility/edit_schedule/'.$datas->id)."' class='btn btn-sm btn-primary' title='Munculkan di menu Schedule' data-role='qtip'><i class='fa fa-edit'></i></a>";
								}									
								// if($akses_menu['delete']=='1'){
									// echo"&nbsp;<a href='#' onClick='return delData(".$datas->id.");' class='btn btn-sm btn-danger' title='Delete Data' data-role='qtip'><i class='fa fa-trash'></i></a>";
								// }
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
	
	$('#example1').dataTable({
    aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]
    ],
    iDisplayLength: 100
	});

		
	
</script>
