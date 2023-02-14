<?php 
$sroot = $_SERVER['DOCUMENT_ROOT'];
include $sroot.'/Siscal_Dashboard/application/libraries/MPDF57/mpdf.php';
$mpdf	= new mPDF('utf-8','A4');
$ArrBulan=array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','Nopember','Desember');
//Beginning Buffer to save PHP variables and HTML tags
ob_start();
$img_file = './assets/img/logo.jpg';
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
    left: 225px;
	}

h2 {
	font-family: calibri,arial,sans-serif;
	position: fixed;
    top: 35px;
    left: 170px;
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
<div>
<img src='".$img_file."' style='float:left' width='90' height='60'/>
</div>
<p class='barcs'>
	<table class='noborder'>
		<tr>		
			<td align='left'><barcode code='".$rows_header->id."' type='QR' size='1.0' error='M'/></td>
		</tr>	
	</table>
</p>
<h2 align='center'>CASH PAYMENT REQUEST</h2>
<h3 align='center'>".$rows_header->id."</h3>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<table class='noborder' width='100%'>
	
	<tr>
		<td align='left' width='15%'>Tanggal Pengajuan</td>
		<td align='left' width='2%'>:</td>
		<td align='left' width='78%'>".date('d M Y',strtotime($rows_header->datet))."</td>
	</tr>
	
	<tr>
		<td align='left' width='15%'>Dibayarkan Kepada</td>
		<td align='left' width='2%'>:</td>
		<td align='left' width='78%'>".$rows_header->member_name."</td>
	</tr>
	
	<tr>
		<td align='left' width='20%'>Untuk Keperluan</td>
		<td align='left' width='2%'>:</td>
		<td align='left' width='78%'>".$rows_header->descr."</td>
	</tr>	
	<tr>
		<td align='left' width='15%'>Jumlah</td>
		<td align='left' width='2%'>:</td>
		<td align='left' width='78%'>Rp. ".number_format($rows_header->total)."</td>
	</tr>	
</table>
<div id='space'></div>
";
echo $Header;
?>
<table class="gridtable" width='100%'>
	<tr>
		<th align='left' colspan='8'># Detail</th>
	</tr>
	<tr>
		<th width='5%' align='center'>No.</th>
		<th width='12%' align='center'>No SO</th>
		<th width='15%' align='center'>Quotation No</th>
		<th width='25%' align='center'>Customer</th>
		<th width='13%' align='center'>No Invoice</th>
		<th width='11%' align='center'>Nett</th>
		<th width='8%' align='center'>Insentif</th>
		<th width='11%' align='center'>Total</th>
	</tr>

<?php
	$batas		= 21;
	$page		=0;
	if($rows_detail){
		$int		= 0;		
		$loop		= 0;
		$tot_pindah	= $sub_total	= 0;
		$batas_page	= intval(count($rows_detail) / $batas);
		if($batas_page==0)$batas_page=1;
		
		foreach($rows_detail as $key=>$vals){
			$loop++;
			$int++;
			$rows_SO	= $this->db->get_where('letter_orders',array('id'=>$vals->letter_order_id))->row();
			if($int > $batas){
				echo "<tr>";
					echo "<td align='right' colspan='7'><b>Sub Total</b></td>";
					echo "<td align='right'><b>".number_format($sub_total)."</b></td>";
				echo "</tr>";
				$tot_pindah	= $sub_total;
				$sub_total=0;
			}
			$sub_total		+=$vals->tot_incentive;
			if($int > $batas){
				$page++;
				
				echo"</table>";
				echo"<pagebreak>";
				echo $Header;
				echo"<table class='gridtable' width='100%'>
						<tr>
							<th align='left' colspan='8'># Detail</th>
						</tr>
						<tr>
							<th width='5%' align='center'>No.</th>
							<th width='12%' align='center'>No SO</th>
							<th width='15%' align='center'>Quotation No</th>
							<th width='25%' align='center'>Customer</th>
							<th width='13%' align='center'>No Invoice</th>
							<th width='11%' align='center'>Nett</th>
							<th width='8%' align='center'>Insentif</th>
							<th width='11%' align='center'>Total</th>
						</tr>";
				$int		= 0;
				$tot_pindah	= 0;
			}
			echo"<tr>";
				echo"<td width='5%' align='center'>".$loop."</td>";
				echo"<td width='12%' align='left'>".$rows_SO->no_so."</td>";
				echo"<td width='15%' align='left'>".$vals->quotation_nomor."</td>";
				echo"<td width='25%' align='left'>".$vals->customer_name."</td>";
				echo"<td width='13%' align='center'>".$vals->invoice_no."</td>";
				echo"<td width='11%' align='right'>".number_format($vals->net_total)."</td>";
				echo"<td width='8%' align='center'>".number_format($vals->nil_incentive)."</td>";
				echo"<td width='11%' align='right'>".number_format($vals->tot_incentive)."</td>";
			echo"</tr>";
			if($batas_page==($page + 1)){
				$batas		= 20;
			}
		}
		
		$int++;
		echo "<tr>";
			echo "<th align='right' colspan='7'><b>Grand Total</b></th>";
			echo "<th align='right'><b>".number_format($rows_header->total)."</b></th>";
		echo "</tr>";
		if($int==$batas || $int==21){
			echo"</table>";
			echo"<pagebreak>";
			echo $Header;
		}
	}

?>

</table>

<div id='space'></div>
<div id='space'></div>

<table class="gridtable" width='100%'>

<tr>
	<th width='30%' align='center'>Diajukan oleh</th>
	<th width='35%' align='center'>Diperiksa Oleh</th>
	<th width='35%' align='center'>Disetujui Oleh</th>
</tr>
<tr>	
	<td align='center' width='30%'><br><br><br><br><br><br><br></td>
	<td align='center' width='35%'><br><br><br><br><br><br><br></td>
	<td align='center' width='35%'><br><br><br><br><br><br><br></td>
</tr>
</table>

<div id='space'></div>
<?php 	
echo "<i>Printed by : ".$printby.", ".$today."</i>";
?>
<div id='space'></div>
<p class='footer'>
<?php 
echo "<i>No cpr : ".$rows_header->id."</i>";
?>
</p>

<?php
$html = ob_get_contents();
ob_end_clean();

$mpdf->WriteHTML($html);
$mpdf->Output($rows_header->id.".pdf" ,'I');
exit;
?>