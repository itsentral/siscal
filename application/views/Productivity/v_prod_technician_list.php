<table id="my-grid2" class="table table-bordered table-striped" style="overflow-x:scroll !important;">
	<thead>
		<tr class="bg-navy-blue">
			<th class="text-center" rowspan="2">No</th>
			<th class="text-center" rowspan="2">Technician</th>
			<th class="text-center" colspan="<?php echo count($rows_day) + 1;?>">Productivity <?php echo date('F Y',mktime(0,0,0,$bulan_cari,1,$tahun_cari));?><br>(In HOURS)</th>
		</tr>		
		<tr class="bg-navy-blue">
			<?php
			if($rows_day){
				foreach($rows_day as $keyDay=>$valDay){
					echo'<th class="text-center">'.$valDay.'</th>';
				}
			}
			echo'<th class="text-center">Total</th>';
			?>
		</tr>
	</thead>
	
		<?php
		//echo"<pre>";print_r($rows_detail);
		$Temp_Find	= array();
		if($rows_detail){
			foreach($rows_detail as $keyDet=>$valDet){
				$code_Tech		= $valDet->code_technician;
				$date_Cals		= $valDet->date_cals;
				$Jum_Minute		= $valDet->jumlah_menit;
				$Temp_Find[$code_Tech][$date_Cals]	= $Jum_Minute;
			}
			unset($rows_detail);
		}
		if($rows_teknisi){
			
			$Temp_Summary	= array();
			$intL			= 0;
			$Total_All		= 0;
			echo'
			<tbody>
			';
			
			
			//echo"<pre>";print_r($detDetail);
			foreach($rows_teknisi as $keyTech=>$valTech){
				$intL++;
				
				echo'
				<tr>
					<td class="text-center">'.$intL.'</td>
					<td class="text-left">'.$valTech.'</td>				
				';
				$Sub_Total		= 0;
				foreach($rows_day as $keyDate=>$valDate){
					$Tanggal_Cari		= date('Y-m-d',mktime(0,0,0,$bulan_cari,$keyDate,$tahun_cari));
					$Nil_Tanggal		= 0;
					if(isset($Temp_Find[$keyTech][$keyDate]) && !empty($Temp_Find[$keyTech][$keyDate])){
						$Nil_Tanggal	= round($Temp_Find[$keyTech][$keyDate] / 60,1);
					}
					
					$Text_Tanggal		= $Nil_Tanggal;
					if(!isset($Temp_Summary[$keyDate]) || empty($Temp_Summary[$keyDate])){
						$Temp_Summary[$keyDate]	= 0;
					}
					$Temp_Summary[$keyDate]	+=$Nil_Tanggal;
					$Sub_Total				+=$Nil_Tanggal;
					$Total_All				+=$Nil_Tanggal;
					if($Nil_Tanggal > 0){
						$Text_Tanggal	='<a href="#" class="text-amber" onClick="ViewDetailTrans({teknisi : \''.$keyTech.'\',bulan : \''.$bulan_cari.'\',tahun : \''.$tahun_cari.'\',hari : \''.$keyDate.'\'});">'.$Nil_Tanggal.'</a>';
					}
					
					echo '<td class="text-right">'.$Text_Tanggal.'</td>';
				}
				$Text_Sub		= $Sub_Total;
				if($Sub_Total > 0){
					$Text_Sub	='<a href="#" class="text-red" onClick="ViewDetailTrans({teknisi : \''.$keyTech.'\',bulan : \''.$bulan_cari.'\',tahun : \''.$tahun_cari.'\',hari : \'\'});">'.$Sub_Total.'</a>';
				}
				echo '<td class="text-right">'.$Text_Sub.'</td>';
				
				
				echo'						
				</tr>
				';
				
				
			}
			$Text_Total	= $Text_Average	= '';
			foreach($rows_day as $keyFoot=>$valFoot){
				$Nil_Total	= $Temp_Summary[$keyFoot];
				
				$Nil_Rata	= 0;
				if($Nil_Total > 0){
					$Nil_Rata	= round($Nil_Total / count($rows_teknisi),1);
				}
				
				$Text_Total	.='<th class="text-right">'.round($Nil_Total,1).'</th>';
				$Text_Average	.='<th class="text-right">'.round($Nil_Rata,1).'</th>';
			}
			$Text_Total	.='<th class="text-right">'.number_format($Total_All,1).'</th>';
			$Text_Average	.='<th class="text-right">'.round($Total_All / count($rows_teknisi),1).'</th>';
			echo'
			</tbody>
			<tfoot>
				<tr class ="blue-grey">
					<th class="text-right" colspan="2">TOTAL</th>	
					'.$Text_Total.'
				</tr>
				<tr class ="blue-grey">
					<th class="text-right" colspan="2">AVERAGE</th>	
					'.$Text_Average.'
				</tr>
			</tfoot>
			';	
				
			
		}else{
			echo'
			<tbody>
				<tr>
					<th colspan ="'.(count($rows_day) + 3).'" class="text-left red-text">NO RECORD WAS FOUND..</th>
				</tr>
			</tbody>
			';
		}
	?>
	
	
</table>
