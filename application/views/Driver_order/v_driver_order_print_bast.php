<?php 
$sroot = $_SERVER['DOCUMENT_ROOT'];
include $sroot.'/Siscal_Dashboard/application/libraries/MPDF57/mpdf.php';
$mpdf=new mPDF('utf-8', 'A4');				// Create new mPDF Document
$ArrBulan	=array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','Nopember','Desember');
$ArrHari	= array(
	'Sun'	=> 'Minggu',
	'Mon'	=> 'Senin',
	'Tue'	=> 'Selasa',
	'Wed'	=> 'Rabu',
	'Thu'	=> 'Kamis',
	'Fri'	=> 'Jumat',
	'Sat'	=> 'Sabtu'
	);
//Beginning Buffer to save PHP variables and HTML tags
ob_start();
$img_file	= $sroot.'/Siscal_Dashboard/assets/img/logo.jpg';
$img_file2	= $sroot.'/Siscal_Dashboard/assets/img/kan.png';
$img_file3 	= $sroot.'/Siscal_Dashboard/assets/img/line.jpg';

//echo"<pre>";print_r($records);exit;

?>  

<style type="text/css">
@page {
	margin-top: 0.8cm;
    margin-left: 1cm;
    margin-right: 1cm;
	margin-bottom: 0.8cm;
}
.font{
	font-family: verdana,arial,sans-serif;
	font-size:14px;
}
.fontheader{
	font-family: verdana,arial,sans-serif;
	font-size:13px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}

table.noborder2 th {
	font-size:11px;
	padding: 1px;
	border-color: #666666;
}

table.noborder2 td {	
	padding: 1px;
	border-color: #666666;
	background-color: #ffffff;
	font-size:10px;
	font-family: verdana,arial,sans-serif;
}
table.noborder3 td {	
	padding: 1px;
	border-color: #666666;
	background-color: #ffffff;
	font-size:12px;
	font-family: verdana,arial,sans-serif;
}
table.noborder, .noborder2,noborder3 {
	font-family: verdana,arial,sans-serif;
}

table.noborder th {
	font-size:12px;
	padding: 1px;
	border-color: #666666;
}

table.noborder td {	
	padding: 1px;
	border-color: #666666;
	background-color: #ffffff;
	font-size:13px;
	font-family: verdana,arial,sans-serif;
}

table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}

table.gridtable th {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: #666666;
	background-color: #f2f2f2;
	
}

table.gridtable th.head {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #7f7f7f;
	color: #ffffff;
}
table.gridtable td {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}

table.gridtable td zero {
	border-width: 1px;
	padding: 5px;
	border-color: #666666;
	background-color: #ffffff;
	
}

table.gridtable td.cols {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}

table.cooltabs {
	font-size:12px;
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
}

table.cooltabs th.reg {
	font-family: verdana,arial,sans-serif;
    border-radius: 5px 5px 5px 5px;
    background: #e3e0e4;
    padding: 5px;
}

table.cooltabs td.reg {
	font-family: verdana,arial,sans-serif;
    border-radius: 5px 5px 5px 5px;
    padding: 5px;
	border-width: 1px;
}

#cooltabs {
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
    border-radius: 5px 5px 5px 5px;
    background: #e3e0e4;
    padding: 5px; 
    width: 800px;
    height: 20px; 
}

#cooltabs2{
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
    border-radius: 5px 5px 5px 5px;
    background: #e3e0e4;
    padding: 5px; 
    width: 180px;
    height: 10px;
}

#space{
    padding: 3px; 
    width: 180px;
    height: 1px;
}

#cooltabshead{
	font-size:12px;
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
    border-radius: 5px 5px 0 0;
    background: #dfdfdf;
    padding: 5px; 
    width: 162px;
    height: 10px;
	float:left;
}

#cooltabschild{
	font-size:10px;
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
    border-radius: 0 0 5px 5px;
    padding: 5px; 
    width: 162px;
    height: 10px;
	float:left;
}

p {
  margin: 0 0 0 0;
}

p.pos_fixed {
	font-family: verdana,arial,sans-serif;
    position: fixed;
    top: 50px;
    left: 230px;
}

p.pos_fixed2 {
	font-family: verdana,arial,sans-serif;
    position: fixed;
    top: 589px;
    left: 230px;
}

p.notesmall {
	font-size: 9px;
}

.barcode {
    padding: 1.5mm;
    margin: 0;
    vertical-align: top;
    color: #000044;
}

.barcodecell {
    text-align: center;
    vertical-align: middle;
	position: fixed;
	top: 14px;
	right: 10px;
}
p.pt {
	font-family: verdana,arial,sans-serif;
	font-size:7px;
    position: fixed;
    top: 62px;
    left: 5px;
}
h3.pt {
	font-family: calibri,arial,sans-serif;
	position: fixed;	
    top: 175px;
    left: 250px;
	}

h3 {
	font-family: calibri,arial,sans-serif;
	position: fixed;	
    top: 65px;
    left: 200px;
	}

h2 {
	font-family: calibri,arial,sans-serif;
	position: fixed;
    top: 95px;
    left: 280px;
	}
	
p.reg {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
}

p.sub {
	font-family: verdana,arial,sans-serif;
	font-size:13px;
    position: fixed;
    top: 55px;
    left: 220px;
	color: #6b6b6b;
}

p.header {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color: #330000;
}

p.barcs {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
    position: fixed;
    top: 13px;
    right: 1px;
}

p.alamat {
	font-family: verdana,arial,sans-serif;
	font-size:7px;
    position: fixed;
    top: 71px;
    left: 5px;
}

p.tlp {
	font-family: verdana,arial,sans-serif;
	font-size:7px;
    position: fixed;
    top: 80px;
    left: 5px;
}

p.date {
	font-family: verdana,arial,sans-serif;
	font-size:12px;
    text-align: right;
}

p.foot {
	font-family: verdana,arial,sans-serif;
	font-size:7px;
    position: fixed;
    top: 750px;
    left: 5px;
}

p.footer {
	font-family: verdana,arial,sans-serif;
	font-size:10px;
    position: fixed;
    bottom: 7px;    
}

p.ln {
	font-family: verdana,arial,sans-serif;
	font-size:9px;
    position: fixed;
    bottom: 1px;
    left: 2px;
}

#hrnew {
    border: 0;
    border-bottom: 1px solid #ccc;
    background: #999;
}
</style>
<?php
$Header	="
<div id='space'></div>
<div id='space'></div>
<table class='noborder2' width='100%'>
	<tr>
		<td width='25%' align='left'>
			<img src='".$img_file."' width='90' height='70'/>
		</td>
		<td width='50%' align='center'>
			<div style='font-size:17px;font-weight: bold;'>PT. SENTRAL TEHNOLOGI MANAGEMEN</div>
		</td>
		<td width='25%' align='right'>
			<img src='".$img_file2."' width='90' height='70'/>
		</td>
	</tr>
	
	
	<tr>
		<td colspan='3'><img src='".$img_file3."'/></td>
	</tr>
</table>
<div id='space'></div>
<div id='space'></div>
";

$Footer	="<p style='font-family: verdana,arial,sans-serif;font-size:10px;text-align:center;position:fixed;bottom:5px;width:100%;' class='footer'>
		<b>www.sentralkalibrasi.co.id</b><br>Cikarang Square Blok B No. 11, Jl. Cibarusah Cikarang Selatan - Jawa Barat 17530<br>Telp. 021-89321314-15,89321323-24 - <b><br>E-mail</b> : <i>cs@sentralkalibrasi.co.id</i>
	</p>";
$intLoop	= 0;
foreach($rows_header as $keyHead=>$valHeader){
	$intLoop++;
	if($intLoop > 1){
		echo"<pagebreak>";
	}
	
	echo $Header;
		
	$inf_serah		= $inf_terima	= '';
	
	if($valHeader['type_bast']=='DEL'){
		$inf_serah	= 'PT. Sentral Tehnologi Managemen';
		$Judul		= 'BUKTI PENYERAHAN ALAT ';
		if($valHeader['flag_type']=='CUST'){
			
			$Judul		.= 'KE CUSTOMER';
		}else{
			$Judul		.= 'KE SUBCON';
		}
		
	}else{
		$Judul		= 'BUKTI PENGAMBILAN ALAT ';
		$inf_terima	= 'PT. Sentral Tehnologi Managemen';
		if($valHeader['flag_type']=='CUST'){
			$Judul		.= 'DARI CUSTOMER';
		}else{
			$Judul		.= 'DARI SUBCON';
		}
	}
	$Tanggal	= $valHeader['datet'];
	$day		= date('D',strtotime($Tanggal));
	$tahun		= date('Y',strtotime($Tanggal));
	$bulan		= date('n',strtotime($Tanggal));
	$hari		= date('d',strtotime($Tanggal));
	$tanggal	= $ArrHari[$day].', '.$hari.' '.$ArrBulan[$bulan].' '.$tahun;
	
	$penerima	= $valHeader['receive_by'];
	$pemberi	= $valHeader['sending_by'];
	if(empty($penerima)){
		$penerima	='---------------------';
	}
	
	if(empty($pemberi)){
		$pemberi	='---------------------';
	}
	
	$rows_so		= $this->db->get_where('letter_orders',array('id'=>$valHeader['letter_order_id']))->row_array();
	$rows_quot		= $this->db->get_where('quotations',array('id'=>$rows_so['quotation_id']))->row_array();
	
	if($valHeader['flag_type'] == 'CUST'){
		$Query_Cust		= "SELECT contact,hp FROM customers WHERE id = '".$valHeader['kode']."'";
		
	}else{
		$Query_Cust		= "SELECT cp AS contact,hp FROM suppliers WHERE id = '".$valHeader['kode']."'";
		
	}
	
	$rows_cust			= $this->db->query($Query_Cust)->row_array();
	
	$rows_detail		= $this->master_model->getArray('bast_details',array('bast_header_id'=>$valHeader['id']));
	$Arr_Detail			= array();
	$Cond_Umum			= "";
	if($rows_detail){
		$intL		= 0;
		foreach($rows_detail as $keyDetail=>$valDetail){
			$WHERE_New		= $Cond_Umum;
			if(!empty($WHERE_New))$WHERE_New	.=" AND ";
			$WHERE_New	.="id = '".$valDetail['quotation_detail_id']."'";
			
			$Qry_Trans		= "SELECT * FROM trans_details WHERE ".$WHERE_New." ORDER BY id DESC LIMIT 1";
			$rows_Trans		= $this->db->query($Qry_Trans)->result();
			if($rows_Trans){
				$quot_det_id	= $rows_Trans[0]->quotation_detail_id;
				if(isset($Arr_Detail[$quot_det_id]) && $Arr_Detail[$quot_det_id]){
					$Arr_Detail[$quot_det_id]['qty']				+= $valDetail['qty'];
					if(isset($Arr_Detail[$quot_det_id]['keterangan']) && $Arr_Detail[$quot_det_id]['keterangan']){
						$Arr_Detail[$quot_det_id]['keterangan']			.=', '.$valDetail['descr'];
					}else{
						$Arr_Detail[$quot_det_id]['keterangan']			= $valDetail['descr'];
					}
				}else{
					$Arr_Detail[$quot_det_id]['tool_id']				= $rows_Trans[0]->tool_id;
					$Arr_Detail[$quot_det_id]['tool_name']				= $rows_Trans[0]->tool_name;
					$Arr_Detail[$quot_det_id]['range']					= $rows_Trans[0]->range;
					$Arr_Detail[$quot_det_id]['piece_id']				= $rows_Trans[0]->piece_id;
					$Arr_Detail[$quot_det_id]['keterangan']				= $valDetail['descr'];
					$Arr_Detail[$quot_det_id]['plan_subcon_pick_date']	= $rows_Trans[0]->plan_subcon_pick_date;
					$Arr_Detail[$quot_det_id]['subcon_bast_send_no']	= $rows_Trans[0]->subcon_bast_send_no;
					$Arr_Detail[$quot_det_id]['pono']					= $rows_Trans[0]->pono;
					$Arr_Detail[$quot_det_id]['customer_id']			= $rows_Trans[0]->customer_id;
					$Arr_Detail[$quot_det_id]['customer_name']			= $rows_Trans[0]->customer_name;
					$Arr_Detail[$quot_det_id]['qty']					= $valDetail['qty'];
				}
				$Arr_Detail[$quot_det_id]['so_descr']					= $rows_Trans[0]->so_descr;
				unset($rows_Trans);
			}
		}
		
		unset($rows_detail);
	}
		
	?>
	<table class="noborder3" width='100%'>	
		<tr>
			<td align='center' valign='top' colspan='3'  width='100%' style="font-size:13px;font-family:calibri,arial,sans-serif;"><b><?php echo $Judul;?></b></td>
		</tr>
		<tr>
			<td align='left' valign='top' colspan='3' height='6' width='100%'></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='24%'>No Berita Acara</td>
			<td align='center' valign='top' width='4%'>:</td>
			<td align='left' valign='top' width='72%'><b><?php echo $valHeader['nomor'] ?></b></td>		
		</tr>
		<?php
		if($valHeader['flag_type']=='CUST'){
		?>
		<tr>
			<td align='left' valign='top' width='24%'>No SO</td>
			<td align='center' valign='top' width='4%'>:</td>
			<td align='left' valign='top' width='72%'><b><?php echo $rows_so['no_so'] ?></b></td>		
		</tr>
		<tr>
			<td align='left' valign='top' width='24%'>No Quotation</td>
			<td align='center' valign='top' width='4%'>:</td>
			<td align='left' valign='top' width='72%'><b><?php echo $rows_quot['nomor'] ?></b></td>		
		</tr>
		<tr>
			<td align='left' valign='top' width='24%'>No PO</td>
			<td align='center' valign='top' width='4%'>:</td>
			<td align='left' valign='top' width='72%'><b><?php echo $rows_quot['pono'] ?></b></td>		
		</tr>
		<?php
		}
		?>
		<tr>
			<td align='left' valign='top' width='24%'>Nama Perusahaan</td>
			<td align='center' valign='top' width='4%'>:</td>
			<td align='left' valign='top' width='72%'><b><?php echo $valHeader['name'] ?></b></td>		
		</tr>
		<tr>
			<td align='left' valign='top' width='24%'>Alamat</td>
			<td align='center' valign='top' width='4%'>:</td>
			<td align='left' valign='top' width='72%'><b><?php echo $valHeader['address'] ?></b></td>		
		</tr>	
		<tr>
			<td align='left' valign='top' colspan='3' height='3' width='100%'></td>
		</tr>
		<tr>
			<?php
			$Kontak		= $rows_cust['contact'];
			if($valHeader['flag_type']=='CUST'){
				$Kontak		= $rows_so['pic'];
			}
			if($rows_cust['hp']){
				$Kontak	.=' / '.$rows_cust['hp'];
			}
			?>
			<td align='left' valign='top' width='24%'><i>Contact Person</i></td>
			<td align='center' valign='top' width='4%'>:</td>
			<td align='left' valign='top' width='72%'><b><?php echo $Kontak; ?></b></td>		
		</tr>	
		<tr>
			<td align='left' valign='top' colspan='3' height='3' width='100%'></td>
		</tr>
	</table>
			
	<table class="gridtable" width='100%'>
		<tr>
			<th width='5%' align='center' valign='middle'>NO.</th>
			<th width='40%' align='center' valign='middle'>NAMA ALAT</th>
			<?php 
			if($valHeader['flag_type']=='SUPP'){
				echo"<th width='25%' align='center' valign='middle'>Perusahaan</th>";
			
			}else{
				echo"<th width='20%' align='center' valign='middle'>MERK</th>";
			
			}
			?>
			<!--<th width='10%' align='center' valign='middle'>KAPASITAS</th>!-->
			<th width='7%' align='center' valign='middle'>JUMLAH</th>
			<?php 
			if($valHeader['flag_type']=='SUPP' && $valHeader['type_bast']=='DEL'){
				echo"<th width='9%' align='center' valign='middle'>TGL AMBIL</th>
					<th width='14%' align='center' valign='middle'>KETERANGAN</th>";
			
			}else{
				echo"<th width='23%' align='center' valign='middle'>KETERANGAN</th>";		
			}
			?>
			
		</tr>
		
		<?php
			//echo "<pre>";print_r($records);
			//echo"<pre>";print_r($Arr_Detail);
			//exit;
			$loop		= 0;
			$Batas		= 17;
			if($Arr_Detail){
				$Page		= 0;
				foreach($Arr_Detail as $key=>$val){
					$loop++;
					
					echo"<tr>";
						echo "<td width='5%' align='center'>$loop</td>";
						echo "<td width='40%' align='left'>".$val['tool_name']."</td>";
						if($valHeader['flag_type']=='SUPP'){
							echo "<td width='25%' align='left'>".$val['customer_name']."</td>";
						}else{
							echo "<td width='20%' align='left'></td>";
						}
						//echo "<td width='10%' align='center'>".$val[range]." ".$val[piece_id]."</td>";
						echo "<td width='7%' align='center'>".number_format(floatval($val['qty']))."</td>";
						if($valHeader['flag_type']=='SUPP'){
							if($valHeader['type_bast']=='DEL'){
								echo "<td width='9%' align='center'>".$val['plan_subcon_pick_date']."</td>";
								echo "<td width='14%' align='left'>".$val['keterangan']."</td>";
							}else{
								echo "<td width='23%' align='left'>BAST Kirim : <i><b>".$val['subcon_bast_send_no']."</b></i></td>";
							}
						}else{
							$Keterangan_SO	= $val['keterangan'];
							if(!empty($val['so_descr']) && $val['so_descr'] !=='-'){
								$Keterangan_SO	= $val['so_descr'];
							}
							echo "<td width='23%' align='left'>".$Keterangan_SO."</td>";
							
						}
					echo"</tr>";
					if($loop >=$Batas){
						$Page++;
						$Batas	= 25;
						echo"</table>";
						echo $Footer;
						echo "<pagebreak>";
						echo $Header;
						echo"<table class='gridtable' width='100%'>
							<tr>
								<th width='5%' align='center' valign='middle'>NO.</th>
								<th width='37%' align='center' valign='middle'>NAMA ALAT</th>";
								if($valHeader['flag_type']=='SUPP'){
									echo"<th width='18%' align='center' valign='middle'>Perusahaan</th>";
								}else{
									echo"<th width='18%' align='center' valign='middle'>MERK</th>";
								}
								echo"
								<th width='10%' align='center' valign='middle'>KAPASITAS</th>
								<th width='7%' align='center' valign='middle'>JUMLAH</th>";
								if($valHeader['flag_type']=='SUPP' && $valHeader['type_bast']=='DEL'){
									echo"<th width='9%' align='center' valign='middle'>TGL AMBIL</th>
										<th width='14%' align='center' valign='middle'>KETERANGAN</th>";
								}else{
									echo"<th width='23%' align='center' valign='middle'>KETERANGAN</th>";
								}
							echo"</tr>";
						$loop=0;
					}
				}
			}
		?>
		
		
	</table>
			
	<?php
		$Next		= $loop + 5;
		if($Next >= $Batas){		
			echo $Footer;
			echo "<pagebreak>";
			echo $Header;
			
		}
	?>
	<div id='space'></div>
	<table class="noborder3" width='100%'>
		<tr>
			<td colspan='3' align='left'></td>
		</tr>
		<tr>
			<td width='10%' valign='top' align='left'>Note :</td>
			<td width='3%' valign='top' align='left'>1.</td>
			<td width='87%' valign='top' align='left'>Agar dapat ditelusuri dengan baik, jika ada permasalahan pada alat harap disampaikan kepada Sentral Sistem Calibration tidak lebih dari 2 hari kerja.</td>
		</tr>
		<!--
		<tr>
			<td width='10%' valign='top' align='left'>Note :</td>
			<td width='3%' valign='top' align='left'>1.</td>
			<td width='87%' valign='top' align='left'>Formulir ini mohon diperlihatkan saat penyerahan alat dan sertifikat setelah kalibrasi.</td>
		</tr>
		<?php 
		
		$Comment	='Alat akan dilakukan pengecekan secara visual dan apabila pada proses kalibrasi alat tersebut tidak memenuhi spesifikasi teknis maka akan ada pemberitahuan.';
		if($valHeader['flag_type']=='CUST' && $valHeader['type_bast']=='SEND'){
			$Comment	='Alat akan dilakukan pengecekan secara visual dan fungsinya oleh kedua belah pihak.';
		}
		?>
		<tr>
			<td width='10%' valign='top' align='left'></td>
			<td width='3%' valign='top' align='left'>2.</td>
			<td width='87%' valign='top' align='left'><?php echo $Comment;?></td>
		</tr>
		<?php
		if($valHeader['flag_type']=='CUST'){
			$Info		='Apabila pada saat melakukan Kalibrasi terdapat kekurangan alat pendukung maka costumer wajib mengirimkan alat bantu tersebut.';
			if($valHeader['type_bast']=='SEND'){
				//$Info	='Setelah alat ukur diterima kembali oleh pihak kedua, maksimum complain 2x24 jam.';
				$Info	= 'Agar dapat ditelusuri dengan baik, jika ada permasalahan pada alat harap disampaikan kepada Sentral Sistem Calibration tidak lebih dari 2 hari kerja.';
			}
		?>
		<tr>
			<td width='10%' valign='top' align='left'></td>
			<td width='3%' valign='top' align='left'>3.</td>
			<td width='87%' valign='top' align='left'><?php echo $Info;?></td>
		</tr>
		
		<?php 
		}
		?>
		!-->
		<tr>
			<td colspan='3' align='left' height='6px'></td>
		</tr>
			
	</table>
	<div id='space'></div>
	<table class="noborder3" width='100%'>
		<tr>
			<td colspan='3' align='left'>Tanggal : <?php echo $tanggal;?></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='35%'></td>
			<td align='center' valign='top' width='30%'>Diserahkan oleh<br><?php echo $inf_serah; ?><br><br><br><br><br><br><?php echo $pemberi;?></td>
			<td align='center' valign='top' width='35%'>Diterima oleh<br><?php echo $inf_terima; ?><br><br><br><br><br><br><?php echo $penerima;?></td>
		</tr>	
	</table>
	<div id='space'></div>
	<div id='space'></div>
	<div id='space'></div>
	<div id='space'></div>

		<p style="font-family: verdana,arial,sans-serif;font-size:10px;text-align:left;position:fixed;bottom:45px;width:100%;">
			<b><i><?php echo $valHeader['nomor'];?>.</i></b>
		</p>

	<?php
	echo $Footer;
}
$html = ob_get_contents();
ob_end_clean();

$mpdf->WriteHTML($html);
//$mpdf->addPage();
//$mpdf->WriteHTML($html);
$mpdf->Output("BAST DRIVER ORDER ".$code_process.".pdf" ,'I');
//$mpdf->Error();
exit;
?>