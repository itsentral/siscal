<?php 
$sroot = $_SERVER['DOCUMENT_ROOT'];
include $sroot.'/Siscal_Dashboard/application/libraries/MPDF57/mpdf.php';
$mpdf=new mPDF('utf-8', 'A4-P');				// Create new mPDF Document
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
    top: 50px;
    left: 105px;
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
		<td width='50%' align='left'>
			<img src='".$img_file."' width='90' height='70'/>
		</td>
		
		<td width='50%' align='right'>
			<img src='".$img_file2."' width='90' height='70'/>
		</td>
	</tr>
	
</table>
<div id='space'></div>
<div id='space'></div>
";

$Footer	="<p style='font-family: verdana,arial,sans-serif;font-size:10px;text-align:center;position:fixed;bottom:5px;width:100%;' class='footer'>
		<b>www.sentralkalibrasi.co.id</b><br>Cikarang Square Blok B No. 11, Jl. Cibarusah Cikarang Selatan - Jawa Barat 17530<br>Telp. 021-89321314-15,89321323-24 - <b><br>E-mail</b> : <i>cs@sentralkalibrasi.co.id</i>
	</p>";
echo $Header;
?>

<?php
	$rows_Letter	= $this->db->get_where('letter_orders',array('id'=>$rows_header['letter_order_id']))->row();
	$Keterangan	= 'Mohon konfirmasinya apabila sudah setuju dengan schedule pengambilan dan pengembalian alat';
	$Ambil	= 'Pengambilan';
	if($rows_Letter->get_tool =='CUSTOMER'){
		$Ambil	= 'Diantar Customer';
		$Keterangan	= 'Mohon konfirmasinya apabila sudah setuju dengan schedule pengantaran (oleh customer) dan pengembalian alat';
	}
	$Tanggal	= $rows_header['datet'];
	$day		= date('D',strtotime($Tanggal));
	$tahun		= date('Y',strtotime($Tanggal));
	$bulan		= date('n',strtotime($Tanggal));
	$hari		= date('d',strtotime($Tanggal));
	$tanggal	= $ArrHari[$day].', '.$hari.' '.$ArrBulan[$bulan].' '.$tahun;
	
	
	
?>
<div id='space'></div>
<h2 align='center'><u>FORM PERSETUJUAN SCHEDULE KALIBRASI<u></h2>
<div id='space'></div>
<table class="noborder3" width='100%'>		
	<tr>
		<td align='left' valign='top' width='24%'>Nama Perusahaan</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='72%'><b><?php echo $rows_header['customer_name']; ?></b></td>		
	</tr>
	<tr>
		<td align='left' valign='top' width='24%'>Alamat</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='72%'><?php echo $rows_Letter->address; ?></td>		
	</tr>
	<tr>
		<td align='left' valign='top' width='24%'>No PO / Penawaran</td>
		<td align='center' valign='top' width='4%'>:</td>
		<td align='left' valign='top' width='72%'><?php echo $rows_quot['pono'] ?></td>		
	</tr>	
	<tr>
		<td align='left' valign='top' colspan='3' height='3' width='100%'></td>
	</tr>
</table>
<div id='space'></div>
<table class="gridtable" width='100%'>
	<tr>
		<th width='5%' align='center' valign='middle' rowspan='2'>No.</th>
		<th width='35%' align='center' valign='middle' rowspan='2'>Nama Alat</th>
		<th width='7%' align='center' valign='middle' rowspan='2'>Qty</th>						
		<th width='25%' align='center'  colspan='3'>Tanggal</th>
		<th width='22%' align='center' valign='middle' rowspan='2'>Keterangan Kond. Alat</th>					
	</tr>
	<tr>					
		<th width='12%' align='center'><?php echo $Ambil;?></th>
		<th width='13%' align='center'>Pengembalian</th>
		<th width='13%' align='center'>Insitu</th>
	</tr>			
	<?php
		$loop		= 0;
		$Batas		= 20;
		if($rows_detail){
			$intG		= 0;
			$Page		= 0;
			foreach($rows_detail as $key=>$val){
				$intG++;
				$loop++;
				$bulan_pick	= date('n',strtotime($val['pick_date']));
				$tgl_pick	= date('d',strtotime($val['pick_date'])).' '.$ArrBulan[$bulan_pick].' '.date('Y',strtotime($val['pick_date']));
				
				$bulan_delv	= date('n',strtotime($val['delivery_date']));
				$tgl_delv	= date('d',strtotime($val['delivery_date'])).' '.$ArrBulan[$bulan_delv].' '.date('Y',strtotime($val['delivery_date']));
				$Tgl_Proses	= '-';
				$insitu		= 'N';
				if($val['labs']=='Y'){
					$lokasi	='Labs';
				}else if($val['insitu']=='Y'){
					$lokasi			 = 'Lokasi Client';
					$bulan_test	 	= date('n',strtotime($val['process_date']));
					$tgl_pick	 	= '-';
					$tgl_delv	 	= '-';								
					$Tgl_Proses		= date('d',strtotime($val['process_date'])).' '.$ArrBulan[$bulan_test].' '.date('Y',strtotime($val['process_date']));
				}else{
					$lokasi	='Subcon';
				}
				
				echo"<tr>";
					echo "<td width='5%' align='center'>$loop</td>";
					echo "<td width='35%' align='left'>".$val['tool_name']."</td>";
					echo "<td width='7%' align='center'>".number_format(floatval($val['qty']))."</td>";								
					echo "<td width='12%' align='center'>".$tgl_pick."</td>";
					echo "<td width='13%' align='center'>".$tgl_delv."</td>";
					echo "<td width='13%' align='center'>".$Tgl_Proses."</td>";
					echo "<td width='22%' align='center'></td>";
				echo"</tr>";
				
				
				
				if($loop >=$Batas){
					$Page++;
					$Batas	= 25;
					echo"</table>";
				
					echo "<pagebreak>";
					echo $Header;
					echo"<table class='gridtable' width='100%'>
							<tr>
								<th width='5%' align='center' valign='middle' rowspan='2'>No.</th>
								<th width='35%' align='center' valign='middle' rowspan='2'>Nama Alat</th>
								<th width='7%' align='center' valign='middle' rowspan='2'>Qty</th>						
								<th width='25%' align='center'  colspan='3'>Tanggal</th>
								<th width='22%' align='center' valign='middle' rowspan='2'>Keterangan Kond. Alat</th>					
							</tr>
							<tr>					
								<th width='12%' align='center'>".$Ambil."</th>
								<th width='13%' align='center'>Pengembalian</th>
								<th width='13%' align='center'>Insitu</th>
							</tr>			";
					$loop=0;
				}
				
			}
		}
		
	?>				
	
</table>
<div id='space'></div>
<table class="noborder3" width='100%'>
	<tr>
		<td colspan='3' align='left'><?php echo $Keterangan;?></td>
	</tr>
	<tr>
		<td width='10%' valign='top' align='left'>Note :</td>
		<td width='3%' valign='top' align='left'>1.</td>
		<td width='87%' valign='top' align='left'>Sertifikat asli akan diberikan setelah pembayaran dilakukan, setelah proses kalibrasi selesai kami akan mengirimkan sertifikat dalam bentuk scan terlebih dahulu.</td>
	</tr>
	<tr>
		<td width='10%' valign='top' align='left'></td>
		<td width='3%' valign='top' align='left'>2.</td>
		<td width='87%' valign='top' align='left'>Sebagai identitas unik alat ukur Bapak/Ibu, kami akan menempelkan sticker kalibrasi pada alat ukur namun sticker kalibrasi sebelumnya akan kami dokumentasikan untuk telusur dikemudian hari dan akan dicopot supaya rapi dan bersih.</td>
	</tr>
	<tr>
		<td width='10%' valign='top' align='left'></td>
		<td width='3%' valign='top' align='left'>3.</td>
		<td width='87%' valign='top' align='left'>Apabila pada saat pengambilan alat tidak ada atau belum disiapkan maka kami tidak menjadwalkan ulang untuk pengambilan alat tesebut sehingga alat harus diantar sendiri oleh client ke lab kami.</td>
	</tr>
	<tr>
		<td width='10%' valign='top' align='left'></td>
		<td width='3%' valign='top' align='left'>*</td>
		<td width='87%' valign='top' align='left'>
			<p class='reg'><font color='#6b6b6b'><b>
				Kondisi alat wajib diisi untuk persiapan kami dalam proses kalibrasi (contoh : Penunjukan angka tidak dari nol,batrai habis, atau error)</b></font>
				<div id='space'></div>
			</p>
		</td>
	</tr>
	<tr>
		<td colspan='3' align='left' height='6px'></td>
	</tr>
		
	<tr>
		<td width='10%' valign='top' align='left'></td>
		<td width='3%' valign='top' align='left'></td>
		<td width='87%' valign='top' align='left'>
			<table class='noborder' width='100%'>
				<tr>
					<td align='left' width='50%'></td>					
					<td align='center' width='50%'>
						Mengetahui dan Menyetujui,<br><font color='#6b6b6b'><b><?php echo $rows_header['customer_name'];?></b></font><br><br><br><br><br><br><br><br><br><b><font color='#6b6b6b'>---------------------------------------<br>
					</td>
					
				</tr>
				
			</table>
		</td>
	</tr>	
</table>

<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<p style="font-family: verdana,arial,sans-serif;font-size:10px;text-align:left;position:fixed;bottom:45px;width:100%;">
	<b><i><?php echo $rows_header['nomor'];?>.</i></b>
</p>

<?php
echo $Footer;
$html = ob_get_contents();
ob_end_clean();

$nomor_SO		= explode("/",$rows_header['nomor']);
$Proses_Nomor	= explode("-",$nomor_SO[0]);
$Nomor_Baru		= $Proses_Nomor[1]."-".$Proses_Nomor[0];
$NoSO			= str_replace($nomor_SO[0],$Nomor_Baru,$records[$modelName][nomor]);

$mpdf->WriteHTML($html);
//$mpdf->addPage();
//$mpdf->WriteHTML($html);
$mpdf->Output(str_replace('/','_',$NoSO).".pdf" ,'I');
//$mpdf->Error();
exit;
?>