<?php
$sroot = $_SERVER['DOCUMENT_ROOT'];
include $sroot . '/Siscal_Dashboard/application/libraries/MPDF57/mpdf.php';
$mpdf	= new mPDF('utf-8', 'A4-L');
$ArrBulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
//Beginning Buffer to save PHP variables and HTML tags
ob_start();
$img_file = './assets/img/logo.jpg';
$img_file2 = './assets/img/line.jpg';
$img_file3 = './assets/img/kan.png';

$TandaTangan	='<br><br><br><br>';
//echo"<pre>";print_r($rows_approve);
if($rows_user){
	if(!empty($rows_user['ttd_file'])){
		$TandaTangan	= '<img src="'.$this->file_location . 'signature/'.$rows_user['ttd_file'].'" width="70px" height="70px"/>';
	}	
	$TandaTangan .='<br>'.strtoupper($rows_user['nama']);
}else{

	$TandaTangan .='<br>-----------------------------------';
}

?>

<style type="text/css">
	@page {
		margin-top: 0.5cm;
		margin-left: 1cm;
		margin-right: 1cm;
		margin-bottom: 0.5cm;
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
		top: 85px;
		left: 375px;
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
		bottom: 3px;    
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
<table class='noborder2' width='100%'>
	<tr>
		<td width='25%' align='left'>
			<img src='".$img_file."' width='100' height='50'/>
		</td>
		<td width='50%' align='center'>
			<div style='font-size:17px;font-weight: bold;'>PT. SENTRAL TEHNOLOGI MANAGEMEN</div>
		</td>
		<td width='25%' align='right'>
			<img src='".$img_file3."' width='100' height='50'/>
		</td>
	</tr>
	<tr>
		<td colspan='3'><img src='".$img_file2."'/ ></td>
	</tr>
</table>
<div id='space'></div>
<div id='space'></div>
";

$Footer	="<p style='font-family: verdana,arial,sans-serif;font-size:10px;text-align:center;position:fixed;bottom:3px;width:100%;' class='footer'>
		<b>www.sentralkalibrasi.co.id</b><br>Cikarang Square Blok B No. 11, Jl. Cibarusah Cikarang Selatan - Jawa Barat 17530<br>Telp. 021-89321314-15,89321323-24 - <b>E-mail</b> : <i>cs@sentralkalibrasi.co.id</i>
	</p>";
echo $Header;
?>

<h2 align='center'><u>PURCHASE ORDER<u></h2>
<div id='space'></div>
<?php
	$tahun	=substr($rows_header[datet],0,4);
	$bulan	=intval(substr($rows_header[datet],5,2));
	$hari	=substr($rows_header[datet],8,2);
	$tanggal=$hari.' '.$ArrBulan[$bulan].' '.$tahun;
?>
<table class="noborder3" width='100%'>	
	<tr>
		<td align='right' valign='top' colspan='6' width='100%'><?php echo 'Cikarang, '.$tanggal ?></td>
	</tr>
	<tr>
		<td align='left' valign='top' width='14%'>No PO</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='34%'><?php echo $rows_header[subcon_pono] ?></td>
		<td align='left' valign='top' width='14%'>Telp / Fax</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='34%'><?php echo $rows_supplier[phone] ?></td>		
	</tr>
	<tr>
		<td align='left' valign='top' width='14%'>Subcon</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='34%'><b><?php echo $rows_header[supplier_name] ?></b></td>	
		<td align='left' valign='top' width='14%'>Attn</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='34%'><b><?php echo $rows_supplier[cp] ?></b></td>
	</tr>
	<tr>
		<td align='left' valign='top' width='14%'>Alamat</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='34%'><?php echo $rows_header[address] ?></td>
		<td align='left' valign='top' width='14%'></td>
		<td align='center' valign='top' width='4%'></td>
		<td align='left' valign='top' width='34%'><b></b></td>
	</tr>
	
	<tr>
		<td align='left' valign='top' colspan='6' height='3' width='100%'></td>
	</tr>
</table>	
<table class="gridtable" width='100%'>
	<tr>
		<td align='left' valign='top' colspan='11' width='100%'>Detail Alat :</td>
	</tr>
	<tr>
		<th width='4%' align='center'>No.</th>
		<th width='15%' align='center'>Nama Alat</th>
		<th width='15%' align='center'>Customer</th>
		<th width='5%' align='center'>Qty</th>
		<th width='9%' align='center'>Harga</th>
		<th width='5%' align='center'>Disc (%)</th>
		<th width='12%' align='center'>Total</th>
		<th width='5%' align='center'>Insitu</th>
		<th width='15%' align='center'>Ket</th>
		<th width='15%' align='center'>Notes</th>
	</tr>
	<?php
		$loop		= 0;
		$Batas		= 20;
		if($rows_detail){
			
			$Sub_Total	= 0;
			$Page		= 0;
			
			foreach($rows_detail as $key=>$val){
				$loop++;
				
				$Harga_After	= round($val[price] * ((100 - $val[discount])/100));
				$Sub_Total		+=($Harga_After * $val[qty]);
				$Nama_Alat		= $val['tool_name'];
				$Keterangan		= $val['descr'];
				$Query_SO		= "SELECT no_so FROM letter_orders WHERE id = '".$val['letter_order_id']."'";
				$rows_SO		= $this->db->query($Query_SO)->row();
				if($rows_SO){
					$Keterangan	= $rows_SO->descr;
				}
				
				echo"<tr>";
					echo "<td width='4%' align='center'>$loop</td>";
					echo "<td width='15%' align='left'>$Nama_Alat</td>";
					echo "<td width='15%' align='left'>".$val['customer_name']."</td>";
					echo "<td width='5%' align='center'>".number_format(floatval($val[qty]))."</td>";
					echo "<td width='9%' align='right'>".number_format(floatval($val[price]))."</td>";
					echo "<td width='5%' align='center'>".number_format(floatval($val[discount]))."</td>";
					echo "<td width='12%' align='right'>".number_format(floatval($Harga_After * $val[qty]))."</td>";
					echo "<td width='5%' align='center'>".$val[flag_insitu]."</td>";
					echo "<td width='15%' align='left'>".$Keterangan."</td>";
					echo "<td width='15%' align='left'>".$val[notes]."</td>";
				echo"</tr>";
				
				if($loop >=$Batas){
					$Page++;
					$Batas	= 35;
					echo"</table>";
					echo $Footer;
					echo "<pagebreak>";
					echo $Header;
					echo"<table class='gridtable' width='100%'>
							<tr>
								<th width='4%' align='center'>No.</th>
								<th width='15%' align='center'>Nama Alat</th>
								<th width='15%' align='center'>Customer</th>
								<th width='5%' align='center'>Qty</th>
								<th width='9%' align='center'>Harga</th>
								<th width='5%' align='center'>Disc (%)</th>
								<th width='12%' align='center'>Total</th>
								<th width='5%' align='center'>Insitu</th>
								<th width='15%' align='center'>Ket</th>
								<th width='15%' align='center'>Notes</th>
							</tr>";
					$loop=0;
				}
			}
		}
	$loop++;
	?>

	<tr>
		<th align='right' colspan='6'><b>Sub Total</b></th>					
		<th width='12%' align='right'><?php echo number_format(floatval($Sub_Total)); ?></th>
		<th width='5%' align='center'></th>
		<th width='15%' align='center'></th>
		<th width='15%' align='center'></th>
	</tr>
	<tr>
		<th align='right' colspan='6'><b>PPN</b></th>					
		<th width='12%' align='right'><?php echo number_format(floatval($rows_header['ppn'])); ?></th>
		<th width='5%' align='center'></th>
		<th width='15%' align='center'></th>
		<th width='15%' align='center'></th>
	</tr>
	<tr>
		<th align='right' colspan='6'><b>Insitu</b></th>					
		<th width='12%' align='right'><?php echo number_format(floatval($rows_header['insitu'])); ?></th>
		<th width='5%' align='center'></th>
		<th width='15%' align='center'></th>
		<th width='15%' align='center'></th>
	</tr>
	<tr>
		<th align='right' colspan='6'><b>Akomodasi</b></th>					
		<th width='12%' align='right'><?php echo number_format(floatval($rows_header['akomodasi'])); ?></th>
		<th width='5%' align='center'></th>
		<th width='15%' align='center'></th>
		<th width='15%' align='center'></th>
	</tr>
	<tr>
		<th align='right' colspan='6'><b>Grand  Total</b></th>					
		<th width='12%' align='right'><?php echo number_format(floatval($rows_header['grand_tot'])); ?></th>
		<th width='5%' align='center'></th>
		<th width='15%' align='center'></th>
		<th width='15%' align='center'></th>
	</tr>
	<?php
	echo"</table>";
	/*
	if($loop >= $Batas){
		
		echo $Footer;
		echo "<pagebreak>";
		echo $Header;
		$loop	=0;
	}
	echo"<div id='space'></div>";
	if($rows_delivery){
		$loop++;
		echo"<table class='gridtable' width='100%'>
				<tr>
					<td align='left' valign='top' colspan='7' width='100%'>Detail Insitu :</td>
				</tr>
				<tr>
					<th width='5%' align='center'>No.</th>
					<th width='30%' align='center'>Area</th>
					<th width='15%' align='center'>Biaya</th>
					<th width='10%' align='center'>Lama (Hari)</th>
					<th width='15%' align='center'>Total</th>
					<th width='10%' align='center'>Diskon</th>
					<th width='15%' align='center'>Grand Total</th>
				</tr>";
				$awal			=0;
				$loop++;
				$Total_Insitu	=0;
				foreach($rows_delivery as $keyI=>$valI){
					$awal++;
					$loop++;
					$sub_tot			= $valI['fee'] * $valI['day'];
					$Total_Insitu		+= $valI['total'];
					echo"<tr>";
						echo "<td width='5%' align='center'>$awal</td>";
						echo "<td width='30%' align='left'>".$valI['delivery_name']."</td>";
						echo "<td width='15%' align='right'>".number_format(floatval($valI[fee]))."</td>";
						echo "<td width='10%' align='center'>".number_format(floatval($valI[day]))."</td>";
						echo "<td width='15%' align='right'>".number_format(floatval($sub_tot))."</td>";
						echo "<td width='10%' align='right'>".number_format(floatval($valI[diskon]))."</td>";
						echo "<td width='15%' align='right'>".number_format(floatval($valI[total]))."</td>";
					echo"<tr>";
					if($loop >=$Batas){
						echo"</table>";
						echo $Footer;
						echo "<pagebreak>";
						echo $Header;
						echo"<table class='gridtable' width='100%'>
								<tr>
									<th width='5%' align='center'>No.</th>
									<th width='30%' align='center'>Area</th>
									<th width='15%' align='center'>Biaya</th>
									<th width='10%' align='center'>Lama (Hari)</th>
									<th width='15%' align='center'>Total</th>
									<th width='10%' align='center'>Diskon</th>
									<th width='15%' align='center'>Grand Total</th>
								</tr>";
						$loop	= 0;
						$awal	= 0;
					}
				}
			$loop++;
			echo"<tr>
				<th colspan='6' align='right'><b>Sub Total</b></th>
				<th width='15%' align='right'>".number_format($Total_Insitu)."</th>
			</tr>
		</table>";
	}
	if($loop >= $Batas){
		
		echo $Footer;
		echo "<pagebreak>";
		echo $Header;
		$loop	=0;
	}
	echo"<div id='space'></div>";
	if($rows_akomodasi){
		$loop++;
		echo"<table class='gridtable' width='100%'>
				<tr>
					<td align='left' valign='top' colspan='5' width='100%'>Detail Akomodasi :</td>
				</tr>
				<tr>
					<th width='5%' align='center'>No.</th>
					<th width='40%' align='center'>Akomodasi</th>
					<th width='20%' align='center'>Biaya</th>
					<th width='15%' align='center'>Diskon</th>
					<th width='20%' align='center'>Grand Total</th>
				</tr>";
				$awal			=0;
				$loop++;
				$Total_Akom		=0;
				foreach($rows_akomodasi as $keyA=>$valA){
					$awal++;
					$loop++;					
					$Total_Akom			+= $valA['total'];
					echo"<tr>";
						echo "<td width='5%' align='center'>$awal</td>";
						echo "<td width='40%' align='left'>".$valA['accommodation_name']."</td>";
						echo "<td width='20%' align='right'>".number_format(floatval($valA[nilai]))."</td>";
						echo "<td width='15%' align='right'>".number_format(floatval($valA[diskon]))."</td>";
						echo "<td width='20%' align='right'>".number_format(floatval($valA[total]))."</td>";
					echo"<tr>";
					if($loop >=$Batas){
						echo"</table>";
						echo $Footer;
						echo "<pagebreak>";
						echo $Header;
						echo"<table class='gridtable' width='100%'>
								<tr>
									<th width='5%' align='center'>No.</th>
									<th width='40%' align='center'>Akomodasi</th>
									<th width='20%' align='center'>Biaya</th>
									<th width='15%' align='center'>Diskon</th>
									<th width='20%' align='center'>Grand Total</th>
								</tr>";
						$loop	= 0;
						$awal	= 0;
					}
				}
			$loop++;
			echo"<tr>
				<th colspan='4' align='right'><b>Sub Total</b></th>
				<th width='15%' align='right'>".number_format($Total_Akom)."</th>
			</tr>
		</table>";
	}
	$Mulai = $loop + 3;
	if($Mulai >= $Batas){		
		echo $Footer;
		echo "<pagebreak>";
		echo $Header;
		$Mulai	= 0;
	}
	echo"<div id='space'></div>";
	echo"<table class='gridtable' width='100%'>
			<tr>
				<td align='left' valign='top' colspan='2' width='100%'>Total PO :</td>
			</tr>
			<tr>
				<th width='75%' align='right'><b>Total</b></th>
				<th width='25%' align='right'><b>".number_format($rows_header['dpp_after_discount'])."</b></th>					
			</tr>
			<tr>
				<th width='75%' align='right'><b>PPN</b></th>
				<th width='25%' align='right'><b>".number_format($rows_header['ppn'])."</b></th>					
			</tr>
			<tr>
				<th width='75%' align='right'>Grand Total</th>
				<th width='25%' align='right'><b>".number_format($rows_header['grand_tot'])."</b></th>					
			</tr>
	</table>";
	*/
	
	$Mulai = $Mulai + 7;
	if($Mulai >= $Batas){		
		echo $Footer;
		echo "<pagebreak>";
		echo $Header;
		$Mulai	= 0;
	}
	
	?>
	
		
</table>
<div id='space'></div>
<table class="noborder3" width='100%'>
	<tr>
		<td colspan='3' align='left' height='2px'></td>
	</tr>
	<tr>
		<td colspan='3' align='left'>Dokumen yang harus dilengkapi saat penagihan :</td>
	</tr>
	<tr>
		<td width='3%' valign='top' align='left'></td>
		<td width='5%' valign='top' align='left'>1.</td>
		<td width='42%' valign='top' align='left'>Invoice.</td>
		<td width='5%' valign='top' align='left'>4.</td>
		<td width='45%' valign='top' align='left'>Surat jalan dan berita acara pengerjaan.</td>
	</tr>
	<tr>
		<td width='3%' valign='top' align='left'></td>
		<td width='5%' valign='top' align='left'>2.</td>
		<td width='42%' valign='top' align='left'>Faktur pajak 2 lembar.</td>
		<td width='5%' valign='top' align='left'>5.</td>
		<td width='45%' valign='top' align='left'>Copy sertifikat.</td>
	</tr>
	<tr>
		<td width='3%' valign='top' align='left'></td>
		<td width='3%' valign='top' align='left'>3.</td>
		<td width='42%' valign='top' align='left'>Copy PO yang sudah ditandatangan.</td>
		<td width='5%' valign='top' align='left'></td>
		<td width='45%' valign='top' align='left'></td>
	</tr>
	
	<tr>
		<td colspan='5' align='left' height='2px'></td>
	</tr>
	<tr>
		<td colspan='5' align='left'>Terima kasih atas perhatian dan kerjasamanya.</td>
	</tr>
	<?php
	$Mulai = $Mulai + 3;
		if($Mulai >= $Batas){
			echo "</table>";
			echo $Footer;
			echo "<pagebreak>";
			echo $Header;
			$Mulai	= 0;
			echo"<table class='noborder3' width='100%'>";
		}
	?>
	<tr>
		<td colspan='5' align='left' height='2px'></td>
	</tr>
	<tr>
		<td colspan='5'>
			<table class='noborder' width='100%'>
				<tr>
					
					<td align='left' valign='top' width='70%'>Hormat kami,<br>Sentral Tehnologi Managemen<br><?php echo $TandaTangan;?></td>
					<td align='left' valign='top' width='30%'><br><?php echo $rows_header['supplier_name'];?><br><br><br><br><br>-----------------------------------</td>
				
				</tr>	
			</table>
		</td>
	</tr>
</table>


<div id='space'></div><p class='reg'><font color='#6b6b6b'>
<i>* PO ini di-generate otomatis oleh sistem komputer, dianggap sah tanpa memerlukan tanda tangan basah & Cap Perusahaan</i></font>
</p>
<div id='space'></div>


<?php
echo $Footer;
$html = ob_get_contents();
ob_end_clean();

$mpdf->WriteHTML($html);

$mpdf->Output($rows_header[subcon_pono].".pdf" ,'I');
//$mpdf->Error();
exit;
?>
