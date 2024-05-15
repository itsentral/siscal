<?php
set_time_limit(0);
$sroot 			= $_SERVER['DOCUMENT_ROOT'];
$data_url		= base_url();
$Split_Beda		= explode('/',$data_url);
$Jum_Beda		= count($Split_Beda);
$Nama_APP		= $Split_Beda[$Jum_Beda - 2];
//echo"<pre>";print_r($Split_Beda);exit;
$directory_file	= $sroot.'/assets/file/';
if(file_exists($sroot."/application/libraries/MPDF57/mpdf.php")){
	include $sroot."/application/libraries/MPDF57/mpdf.php";
	// $img_file	= $sroot.'/assets/img/logo.jpg';
	// $img_file2 = $sroot.'/assets/img/line.jpg';
}else{
	include $sroot."/".$Nama_APP."/application/libraries/MPDF57/mpdf.php";
	$directory_file	= $sroot."/".$Nama_APP.'/assets/file/';
	// $img_file		= $sroot."/".$Nama_APP.'/assets/img/logo.jpg';
	// $img_file2 		= $sroot."/".$Nama_APP.'/assets/img/line.jpg';
}
$mpdf		= new mPDF('utf-8', 'A4');
$ArrBulan	= array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','Nopember','Desember');
ob_start();

$img_file		= base_url('/assets/img/logo.jpg');
$img_file2 		= base_url('/assets/img/line.jpg');

if($type_file=='D'){
	$File_Name	= $rows_header[0]->id;
	$tipe_file	= 'I';
}else{
	$File_Name	= $directory_file.$rows_header[0]->id;
	$tipe_file	= 'F';
}

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
$Header  ="
<div id='space'></div>
<div id='space'></div>
<table class='noborder2' width='100%'>
	<tr>
		<td width='30%' align='left' rowspan='2'>
			<img src='".$img_file."' width='160' height='70'/>
		</td>
		<td><div style='font-size:18px;font-weight: bold;'>PT. SENTRAL TEHNOLOGI MANAGEMEN</div></td>
	</tr>
	<tr>
		<td valign='bottom'><div style='font-size:9px;font-weight: bold;'>TEMPERATURE, PRESSURE, MASS, DIMENSION, ANALITICAL INSTRUMENT, FORCE, ISO 17025</div></td>
	</tr>
	<tr>
		<td colspan='2'><img src='".$img_file2."'/></td>
	</tr>	
	<tr>
		<td height='10px' colspan='2'></td>
	</tr>
</table>";

$Footer	="<p style='font-family: verdana,arial,sans-serif;font-size:10px;text-align:center;position:fixed;bottom:5px;width:100%;' class='footer'>
		<b>www.sentralkalibrasi.co.id</b><br>Cikarang Square Blok B No. 11, Jl. Cibarusah Cikarang Selatan - Jawa Barat 17530<br>Telp. 021-89321314-15,89321323-24 - <b><br>E-mail</b> : <i>cs@sentralkalibrasi.co.id</i>
	</p>";
echo $Header;
?>
<div id='space'></div>
<?php
	$tahun		= date('Y',strtotime($rows_header[0]->datet));
	$bulan		= date('n',strtotime($rows_header[0]->datet));
	$hari		= date('d',strtotime($rows_header[0]->datet));
	$tanggal	= $hari.' '.$ArrBulan[$bulan].' '.$tahun;
?>
<table class="noborder3" width='100%'>	
	<tr>
		<td align='right' valign='top' colspan='3' width='100%'><?php echo 'Jakarta, '.$tanggal ?></td>
	</tr>
	<tr>
		<td align='left' valign='top' width='14%'>No</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='82%'><?php echo $rows_header[0]->nomor_surat; ?></td>		
	</tr>
	<tr>
		<td align='left' valign='top' width='14%'>Hal</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='82%'><b>Konfirmasi Status Tagihan.</b></td>		
	</tr>
	<tr>
		<td align='left' valign='top' colspan='3' height='10px' width='100%'></td>
	</tr>
	<tr>
		<td align='left' valign='top' colspan='3' width='100%'>Kepada Yth.</td>
	</tr>
	<tr>
		<td align='left' valign='top' colspan='3' width='100%'><b><?php echo strtoupper($rows_header[0]->customer_name);?></b></td>
	</tr>
	<tr>
		<td align='left' valign='top' colspan='3' height='10px' width='100%'></td>
	</tr>
	<tr>
		<td align='left' valign='top' colspan='3' width='100%'>Dengan hormat,</td>
	</tr>
	<tr>
		<td align='left' valign='top' colspan='3' width='100%'>Sehubungan dengan adanya Tagihan Jasa Kalibrasi Alat, maka bersama ini kami bermaksud menanyakan status pembayaran yang sampai sekarang belum dipenuhi atau dilunasi oleh <b><?php echo strtoupper($rows_header[0]->customer_name);?></b>.</td>
	</tr>
	<tr>
		<td align='left' valign='top' colspan='3' height='5px' width='100%'></td>
	</tr>
	<tr>
		<td align='left' valign='top' colspan='3' width='100%'>Adapun datanya sebagai berikut :</td>
	</tr>	
	<tr>
		<td align='left' valign='top' colspan='3' height='3px' width='100%'></td>
	</tr>
</table>	
<table class="gridtable" width='100%'>	
	<tr>
		<th width='5%' align='center'>No.</th>
		<th width='18%' align='center'>No Invoice</th>
		<th width='15%' align='center'>Tgl Invoice</th>
		<th width='15%' align='center'>Total</th>
		<th width='17%' align='center'>Diterima Oleh</th>
		<th width='15%' align='center'>Tgl Terima</th>
		<th width='15%' align='center'>Piutang</th>		
	</tr>
	<?php
		$loop		= 0;
		$Batas		= 25;
		if($rows_detail){			
			$Sub_Total	= 0;
			$Page		= 0;			
			foreach($rows_detail as $key=>$vals){
				$loop++;
				$no_Inv		= $vals->invoice_no;
				$tot_Inv	= $vals->total_invoice;
				$tot_Pay	= $vals->total_bayar;
				$tot_Debt	= $vals->total_piutang;
				$date_Inv	= date('d M Y',strtotime($vals->invoice_date));
				$rec_By = $rec_Date ='-';
				if($vals->receive_date){
					$rec_Date	= date('d M Y',strtotime($vals->receive_date));
					$rec_By		= $vals->receive_by;
				}
				$Sub_Total	+= $tot_Debt;
				echo"<tr>";
					echo "<td width='5%' align='center'>$loop</td>";
					echo "<td width='18%' align='left'>$no_Inv</td>";
					echo "<td width='15%' align='center'>$date_Inv</td>";
					echo "<td width='15%' align='right'>".number_format($tot_Inv)."</td>";
					echo "<td width='17%' align='left'>$rec_By</td>";
					echo "<td width='15%' align='center'>$rec_Date</td>";
					echo "<td width='15%' align='right'>".number_format($tot_Debt)."</td>";
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
								<th width='5%' align='center'>No.</th>
								<th width='18%' align='center'>No Invoice</th>
								<th width='15%' align='center'>Tgl Invoice</th>
								<th width='15%' align='center'>Total</th>
								<th width='17%' align='center'>Diterima Oleh</th>
								<th width='15%' align='center'>Tgl Terima</th>
								<th width='15%' align='center'>Piutang</th>		
							</tr>";
					$loop=0;
				}
			}
		}
		$loop++;
	?>
	<tr>
		<th align='right' colspan='6'><b>Grand Total</b></th>					
		<th width='15%' align='right'><?php echo number_format(floatval($Sub_Total)); ?></th>		
	</tr>		
</table>
<?php
$Mulai = $loop + 10;
if($Mulai >= $Batas){
	echo $Footer;
	echo "<pagebreak>";
	echo $Header;
	$Mulai	= 0;	
}
?>
<div id='space'></div>
<div id='space'></div>
<table class="noborder3" width='100%'>
	<tr>
		<td colspan='3' align='left'>Mengingat.</td>
	</tr>
	<tr>
		<td align='left' valign='top' width='7%'></td>
		<td align='left' valign='top' width='3%'>1.</td>
		<td align='left' valign='top' width='90%'>Penundaan kewajiban pembayaran <b><?php echo strtoupper($rows_header[0]->customer_name);?></b> sudah melebihi batas dari tanggal jatuh tempo pembayaran.</td>		
	</tr>
	<tr>
		<td align='left' valign='top' width='7%'></td>
		<td align='left' valign='top' width='3%'>2.</td>
		<td align='left' valign='top' width='90%'>Hingga saat ini belum ada kepastian kapan tagihan tersebut akan dibayarkan atau dilunasi.</td>		
	</tr>
	<tr>
		<td colspan='3' align='left' height='3px'></td>
	</tr>
	<tr>
		<td colspan='3' align='left'>Maka kami mohon bantuan Bapak/Ibu untuk dapat memberikan informasi kepada kami mengenai status kekurangan bayar tersebut secara tertulis. Dan apabila sudah melakukan pembayaran, maka surat ini diabaikan.</td>
	</tr>
		
	<tr>
		<td colspan='3' align='left' height='3px'></td>
	</tr>
	<tr>
		<td colspan='3' align='left'>Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami sampaikan terima kasih.</td>
	</tr>
<?php
$Mulai = $Mulai + 5;
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
		<td colspan='3' align='left' height='5px'></td>
	</tr>
	<tr>
		<td colspan='3' align='left'>Hormat kami,<br><b>PT. Sentral Tehnologi Managemen</b><br><br><br><br><br><br><u>Tati Rina</u><br>Manager Accounting</td>		
	</tr>
</table>

<div id='space'></div>
<div id='space'></div><p class='reg'><font color='#6b6b6b'>
<i>* Surat ini di-generate otomatis oleh sistem komputer, dianggap sah tanpa memerlukan tanda tangan basah & Cap Perusahaan</i></font>
</p>
<div id='space'></div>


<?php
echo $Footer;
$html = ob_get_contents();
ob_end_clean();

$mpdf->WriteHTML($html);

$mpdf->Output($File_Name.".pdf" ,$tipe_file);
//$mpdf->Error();
//exit;
?>
