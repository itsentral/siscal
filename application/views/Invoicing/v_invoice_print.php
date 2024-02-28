<?php
$sroot = $_SERVER['DOCUMENT_ROOT'];
include $sroot . '/Siscal_Dashboard/application/libraries/MPDF57/mpdf.php';
$mpdf	= new mPDF('utf-8', 'A4');
$ArrBulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
//Beginning Buffer to save PHP variables and HTML tags
ob_start();
$img_file = './assets/img/logo.jpg';
$img_file2 = './assets/img/line.jpg';
?>

<style type="text/css">
	@page {
		margin-top: 0.8cm;
		margin-left: 1cm;
		margin-right: 1cm;
		margin-bottom: 0.8cm;
	}

	.font {
		font-family: verdana, arial, sans-serif;
		font-size: 14px;
	}

	.fontheader {
		font-family: verdana, arial, sans-serif;
		font-size: 13px;
		color: #333333;
		border-width: 1px;
		border-color: #666666;
		border-collapse: collapse;
	}

	table.noborder2 th {
		font-size: 11px;
		padding: 1px;
		border-color: #666666;
	}

	table.noborder2 td {
		padding: 1px;
		border-color: #666666;
		background-color: #ffffff;
		//font-size:10px;
		font-family: verdana, arial, sans-serif;
	}

	table.noborder3 td {
		padding: 1px;
		border-color: #666666;
		background-color: #ffffff;
		font-size: 10px;
		font-family: verdana, arial, sans-serif;
	}

	table.noborder,
	.noborder2,
	noborder3 {
		font-family: verdana, arial, sans-serif;
	}

	table.noborder th {
		font-size: 12px;
		padding: 1px;
		border-color: #666666;
	}

	table.noborder td {
		padding: 1px;
		border-color: #666666;
		background-color: #ffffff;
		font-size: 13px;
		font-family: verdana, arial, sans-serif;
	}

	table.gridtable {
		font-family: verdana, arial, sans-serif;
		font-size: 11px;
		color: #333333;
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
		font-size: 12px;
		font-family: verdana, arial, sans-serif;
		border-width: 1px;
		border-style: solid;
	}

	table.cooltabs th.reg {
		font-family: verdana, arial, sans-serif;
		border-radius: 5px 5px 5px 5px;
		background: #e3e0e4;
		padding: 5px;
	}

	table.cooltabs td.reg {
		font-family: verdana, arial, sans-serif;
		border-radius: 5px 5px 5px 5px;
		padding: 5px;
		border-width: 1px;
	}

	#cooltabs {
		font-family: verdana, arial, sans-serif;
		border-width: 1px;
		border-style: solid;
		border-radius: 5px 5px 5px 5px;
		background: #e3e0e4;
		padding: 5px;
		width: 800px;
		height: 20px;
	}

	#cooltabs2 {
		font-family: verdana, arial, sans-serif;
		border-width: 1px;
		border-style: solid;
		border-radius: 5px 5px 5px 5px;
		background: #e3e0e4;
		padding: 5px;
		width: 180px;
		height: 10px;
	}

	#space {
		padding: 3px;
		width: 180px;
		height: 1px;
	}

	#cooltabshead {
		font-size: 12px;
		font-family: verdana, arial, sans-serif;
		border-width: 1px;
		border-style: solid;
		border-radius: 5px 5px 0 0;
		background: #dfdfdf;
		padding: 5px;
		width: 162px;
		height: 10px;
		float: left;
	}

	#cooltabschild {
		font-size: 10px;
		font-family: verdana, arial, sans-serif;
		border-width: 1px;
		border-style: solid;
		border-radius: 0 0 5px 5px;
		padding: 5px;
		width: 162px;
		height: 10px;
		float: left;
	}

	p {
		margin: 0 0 0 0;
	}

	p.pos_fixed {
		font-family: verdana, arial, sans-serif;
		position: fixed;
		top: 50px;
		left: 230px;
	}

	p.pos_fixed2 {
		font-family: verdana, arial, sans-serif;
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
		font-family: verdana, arial, sans-serif;
		font-size: 7px;
		position: fixed;
		top: 62px;
		left: 5px;
	}

	h3.pt {
		font-family: calibri, arial, sans-serif;
		position: fixed;
		top: 175px;
		left: 250px;
	}

	h3 {
		font-family: calibri, arial, sans-serif;
		position: fixed;
		top: 65px;
		left: 200px;
	}

	h2 {
		font-family: calibri, arial, sans-serif;
		position: fixed;
		top: 50px;
		left: 280px;
	}

	p.reg {
		font-family: verdana, arial, sans-serif;
		font-size: 11px;
	}

	p.sub {
		font-family: verdana, arial, sans-serif;
		font-size: 13px;
		position: fixed;
		top: 55px;
		left: 220px;
		color: #6b6b6b;
	}

	p.header {
		font-family: verdana, arial, sans-serif;
		font-size: 11px;
		color: #330000;
	}

	p.barcs {
		font-family: verdana, arial, sans-serif;
		font-size: 11px;
		position: fixed;
		top: 13px;
		right: 1px;
	}

	p.alamat {
		font-family: verdana, arial, sans-serif;
		font-size: 7px;
		position: fixed;
		top: 71px;
		left: 5px;
	}

	p.tlp {
		font-family: verdana, arial, sans-serif;
		font-size: 7px;
		position: fixed;
		top: 80px;
		left: 5px;
	}

	p.date {
		font-family: verdana, arial, sans-serif;
		font-size: 12px;
		text-align: right;
	}

	p.foot {
		font-family: verdana, arial, sans-serif;
		font-size: 7px;
		position: fixed;
		top: 750px;
		left: 5px;
	}

	p.footer {
		font-family: verdana, arial, sans-serif;
		font-size: 10px;
		position: fixed;
		bottom: 7px;
	}

	p.ln {
		font-family: verdana, arial, sans-serif;
		font-size: 9px;
		position: fixed;
		bottom: 1px;
		left: 2px;
	}

	#hrnew {
		border: 0;
		border-bottom: 1px solid #ccc;
		background: #999;
	}

	.textleft {
		text-align: left;
	}

	.textright {
		text-align: right;
	}

	.textcenter {
		text-align: center;
	}

	.bold {
		font-weight: bold;
	}
</style>
<?php
$Header  = "
<div id='space'></div>
<div id='space'></div>
<table class='noborder2' width='100%'>
	<tr>
		<td width='30%' align='left' rowspan='2'>
			<img src='" . $img_file . "' width='160' height='70'/>
		</td>
		<td><div style='font-size:18px;font-weight: bold;'>PT. SENTRAL TEHNOLOGI MANAGEMEN</div></td>
	</tr>
	<tr>
		<td valign='bottom'><div style='font-size:9px;font-weight: bold;'>TEMPERATURE, PRESSURE, MASS, DIMENSION, ANALITICAL INSTRUMENT, FORCE, ISO 17025</div></td>
	</tr>
	<tr>
		<td colspan='2'><img src='" . $img_file2 . "'/></td>
	</tr>
	<tr>
		<td class='textcenter bold' colspan='2'><div style='font-size:18px;font-weight: bold;'>INVOICE</div></td>
	</tr>
	<tr>
		<td height='20px' colspan='2'></td>
	</tr>
</table>";

$Header2  = "
<div id='space'></div>
<div id='space'></div>
<table class='noborder2' width='100%'>
	<tr>
		<td width='30%' align='left' rowspan='2'>
			<img src='" . $img_file . "' width='160' height='70'/>
		</td>
		<td><div style='font-size:18px;font-weight: bold;'>PT. SENTRAL TEHNOLOGI MANAGEMEN</div></td>
	</tr>
	<tr>
		<td valign='bottom'><div style='font-size:9px;font-weight: bold;'>TEMPERATURE, PRESSURE, MASS, DIMENSION, ANALITICAL INSTRUMENT, FORCE, ISO 17025</div></td>
	</tr>
	<tr>
		<td colspan='2'><img src='" . $img_file2 . "'/></td>
	</tr>
	<tr>
		<td class='textcenter bold' colspan='2'><div style='font-size:18px;font-weight: bold;'>Bukti Tanda Terima Proforma Invoice</div></td>
	</tr>
	<tr>
		<td height='20px' colspan='2'></td>
	</tr>
</table>";

$Footer	= "<p style='font-family: verdana,arial,sans-serif;font-size:10px;text-align:center;position:fixed;bottom:5px;width:100%;' class='footer'>
		<b>www.sentralkalibrasi.co.id</b><br>Cikarang Square Blok B No. 11, Jl. Cibarusah Cikarang Selatan - Jawa Barat 17530<br>Telp. 021-89321314-15,89321323-24 - <b><br>E-mail</b> : <i>cs@sentralkalibrasi.co.id</i>
	</p>";
echo $Header;
$tahun		= date('Y', strtotime($rows_header['datet']));
$tahun_short = date('y', strtotime($rows_header['datet']));
$bulan		= date('n', strtotime($rows_header['datet']));
$hari		= date('d', strtotime($rows_header['datet']));

$tanggal	= $hari . ' ' . $ArrBulan[$bulan] . ' ' . $tahun;
$Alamat		= $rows_cust['npwp_address'];
//if (empty($Alamat)) {
//	$Alamat		= $rows_cust['address'];
//}

?>
<table class='noborder' width='100%'>
	<tr>
		<td align='left' valign='top' width='15%'>No Invoice</td>
		<td align='center' valign='top' width='2%'>:</td>
		<td align='left' valign='top' width='33%'><?php echo $rows_header['invoice_no'] ?></td>

		<td align='left' valign='top' width='8%'>Kepada</td>
		<td align='center' valign='top' width='2%'>:</td>
		<td valign='top' width='40%' class="textleft bold"><?php echo $rows_cust['name'] ?></td>
	</tr>
	<tr>
		<td align='left' valign='top' width='15%'>Tanggal Invoice</td>
		<td align='center' valign='top' width='2%'>:</td>
		<td align='left' valign='top' width='33%'><?php echo date('d-m-Y', strtotime($rows_header['datet'])) ?></td>

		<td align='left' valign='top' width='8%' rowspan="3"></td>
		<td align='center' valign='top' width='2%' rowspan="3"></td>
		<td align='left' valign='top' width='40%' rowspan="3"><?php echo $Alamat ?></td>
	</tr>
	<tr>
		<td align='left' valign='top' width='15%'>Periode</td>
		<td align='center' valign='top' width='2%'>:</td>
		<td align='left' valign='top' width='33%'><?php echo $ArrBulan[$bulan] . "-" . $tahun_short; ?></td>
	</tr>

	<tr>
		<td align='left' valign='top' colspan='6' height='20' width='100%'></td>
	</tr>
	<tr>
		<td align='left' valign='top' colspan='6' width='100%'>Attn : Finance Dept.</td>
	</tr>
</table>
<table class="gridtable" width='100%'>
	<tr>
		<td align='left' valign='top' colspan='8' width='100%'>Detail Invoice :</td>
	</tr>
	<tr>
		<th width='5%' align='center'>No.</th>
		<th width='30%' align='center'>Nama Barang / Pesanan</th>
		<th width='12%' align='center'>No PO</th>
		<th width='13%' align='center'>No SO</th>
		<th width='7%' align='center'>Qty</th>
		<th width='10%' align='center'>Harga @</th>
		<th width='10%' align='center'>Diskon</th>
		<th width='13%' align='center'>Sub Total</th>
	</tr>
	<?php
	$loop		= 0;
	$Batas		= 20;
	$arr_PO		= $arr_SO	= array();
	$intP		= $intS		= 0;
	if ($rows_detail) {
		$Sub_Total	= 0;
		$Page		= 0;
		$Arr_Detail	= array();
		foreach ($rows_detail as $key => $val) {
			$Nomor_PO = $Nomor_SO = '- ';
			$Query_SO	= "SELECT no_so FROM letter_orders WHERE id = '" . $val['letter_order_id'] . "'";
			$rows_SO	= $this->db->query($Query_SO)->result();
			if ($rows_SO) {
				$Nomor_SO	= $rows_SO[0]->no_so;
			}

			$Query_PO	= "SELECT pono FROM quotations WHERE id = '" . $val['quotation_id'] . "'";
			$rows_PO	= $this->db->query($Query_PO)->result();
			if ($rows_PO) {
				$Nomor_PO	= $rows_PO[0]->pono;
			}


			if (!in_array($Nomor_PO, $arr_PO) || empty($arr_PO)) {
				$intP++;
				$arr_PO[$intP]		= $Nomor_PO;
			}
			if (!in_array($Nomor_SO, $arr_SO) || empty($arr_SO)) {
				$intS++;
				$arr_SO[$intS]		= $Nomor_SO;
			}
			$kode_Unik		= $val['tipe'] . '-' . $val['detail_id'];
			$Harga_Item		= str_replace(',', '', $val['price']);
			$Diskon_Item	= str_replace(',', '', $val['discount']);
			if (!isset($Arr_Detail[$kode_Unik]) || empty($Arr_Detail)) {
				$Arr_Detail[$kode_Unik]				= $val;
				$Arr_Detail[$kode_Unik]['noso'] 	= $Nomor_SO;
				$Arr_Detail[$kode_Unik]['pono'] 	= $Nomor_PO;
				unset($Arr_Detail[$kode_Unik]['total']);
				$Arr_Detail[$kode_Unik]['price']	= $Harga_Item;
				$Arr_Detail[$kode_Unik]['discount'] = $Diskon_Item;
			} else {
				$Arr_Detail[$kode_Unik]['qty']				+= $val['qty'];
				$Arr_Detail[$kode_Unik]['total_discount']	+= $val['total_discount'];
				$Arr_Detail[$kode_Unik]['total_harga']		+= $val['total_harga'];
			}
		}
		//echo"<pre>";print_r($Arr_Detail);exit;
		foreach ($Arr_Detail as $key => $val) {
			$loop++;

			$Sub_Total		+= ($val[total_harga] - $val[total_discount]);

			echo "<tr>";
			echo "<td width='5%' align='center'>$loop</td>";
			echo "<td width='30%' align='left'>" . $val['tool_name'] . "</td>";
			echo "<td width='12%' align='left'>" . $val['pono'] . "</td>";
			echo "<td width='13%' align='left'>" . $val['noso'] . "</td>";
			echo "<td width='7%' align='center'>" . number_format(floatval($val['qty'])) . "</td>";
			echo "<td width='10%' align='right'>" . number_format(floatval($val['price'])) . "</td>";
			echo "<td width='10%' align='right'>" . number_format(floatval($val['total_discount'])) . "</td>";
			echo "<td width='13%' align='right'>" . number_format(floatval($val['total_harga'] - $val['total_discount'])) . "</td>";
			echo "</tr>";
			if ($loop >= $Batas) {
				$Page++;
				$Batas	= 35;
				echo "</table>";
				echo $Footer;
				echo "<pagebreak>";
				echo $Header;
				echo "<table class='gridtable' width='100%'>
							<tr>
								<th width='5%' align='center'>No.</th>
								<th width='30%' align='center'>Nama Barang / Pesanan</th>
								<th width='12%' align='center'>No PO</th>
								<th width='13%' align='center'>No SO</th>
								<th width='7%' align='center'>Qty</th>
								<th width='10%' align='center'>Harga @</th>
								<th width='10%' align='center'>Diskon</th>
								<th width='13%' align='center'>Sub Total</th>
							</tr>";
				$loop = 0;
			}
		}
	}
	$loop++;

	?>


	<tr>
		<th align='right' colspan='7'><b>Sub Total</b></th>
		<th width='15%' class="textright bold"><?php echo number_format(floatval(str_replace(',', '', $rows_header['total_dpp']))); ?></th>
	</tr>
	<?php
	$nilai_ppn = (floatval($rows_header['ppn']) / floatval($rows_header['total_dpp'])) * 100;
	?>
	<tr>
		<th align='right' colspan='7'><b>Pajak <?= round($nilai_ppn); ?>%</b></th>
		<th width='15%' class="textright bold"><?php echo number_format(floatval(str_replace(',', '', $rows_header['ppn']))); ?></th>
	</tr>
	<tr>
		<th align='right' colspan='7'><b>Total</b></th>
		<th width='15%' class="textright bold"><?php echo number_format(floatval(str_replace(',', '', $rows_header['grand_tot']))); ?></th>
	</tr>

</table>
<?php
$Tambahan		= 0;
$Text_Tambah	= '';
if ($rows_header['flag_qr'] == 'Y') {
	$Tambahan		= 2;
	$Text_Tambah	= '<tr>
						<td class="textleft" width="70%">&nbsp;</td>
						<td class="textcenter" width="30%"><br><br><br><img src="' . $this->file_location . 'QRCode/' . $rows_header['code_hash'] . '.png" width="100px" height="100px"/></td>
					   </tr>';
}
$Mulai = $loop + $Tambahan + 7;
if ($Mulai >= $Batas) {
	echo $Footer;
	echo "<pagebreak>";
	echo $Header;
	$Mulai	= 0;
}

$TandaTangan	= '<br><br><br><br><br><br><br><br><br><br>';
//echo"<pre>";print_r($rows_approve);
if ($rows_approve) {
	if (!empty($rows_approve[0]['ttd_file'])) {
		$TandaTangan	= '<img src="' . $this->file_location . 'signature/' . $rows_approve[0]['ttd_file'] . '" width="175px" height="175px"/>';
	}
}




?>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<table class="noborder3" width='100%'>
	<tr>
		<td class="textleft bold" colspan="2">Keterangan :</td>
	</tr>
	<tr>
		<td class="textleft" width="70%">Pembayaran harus dilakukan paling lambat 14 hari setelah Invoice diterima</td>
		<td class="textcenter bold" width="30%">PT. SENTRAL TEHNOLOGI MANAGEMEN</td>
	</tr>
	<tr>
		<td class="textleft" width="70%">Pembayaran dimohon mencantumkan Nomor Invoice dan transfer dengan virtual account :</td>
		<td class="textcenter" width="30%" rowspan="3"><?php echo $TandaTangan; ?></td>
	</tr>
	<tr>
		<td class="textleft" width="70%">
			<table class='noborder3' width='100%'>
				<tr>
					<td align='left' valign='top' width='15%'>Bank</td>
					<td align='left' valign='top' width='85%'>: Bank OCBC NISP</td>
				</tr>
				<?php if(substr($rows_cust['va_no'], 0, 4) == '6889'){ ?> 
					<tr>
						<td align='left' valign='top' width='15%'>No Virtual Account</td>
						<td align='left' valign='top' width='85%'>:
							<?php
							echo $rows_cust['va_no'];
							?>
						</td>
					</tr>
					<tr>
						<td align='left' valign='top' width='15%'>Atas Nama</td>
						<td align='left' valign='top' width='85%'>:
							<?php
							echo $rows_cust['name'];
							?>
						</td>
					</tr>
				<?php }else{ ?>
					<tr>
						<td align='left' valign='top' width='15%'>No Rekening</td>
						<td align='left' valign='top' width='85%'>:
							029800006099
						</td>
					</tr>
					<tr>
						<td align='left' valign='top' width='15%'>Atas Nama</td>
						<td align='left' valign='top' width='85%'>:
							PT. SENTRAL TEHNOLOGI MANAGEMEN
						</td>
					</tr>
				<?php } ?>

			</table>
		</td>
	</tr>
	<tr>
		<td class="textleft" width="70%">Bukti pembayaran mohon di Fax ke no : (021) 29067204</td>
	</tr>

	<tr>
		<td width="70%"></td>
		<td class="textcenter bold" width="30%">Tati Rina<br>____________________________<br>Manager Accounting</td>
	</tr>
	<?php
	echo $Text_Tambah;
	?>
</table>
<div id='space'></div>
<div id='space'></div>

<?php
echo $Footer;
if (isset($rows_require) && $rows_require) {
	$Total_Baris	= count($rows_require) % 2;
	if ((count($rows_require) % 2) > 0) {
		$Total_Baris	= intval(count($rows_require) / 2) + 1;
	} else {
		$Total_Baris	= count($rows_require) / 2;
	}
	echo "<pagebreak>";
	echo "<table class='noborder' width='100%'>	
			<tr>
				<th colspan='8' align='left'>Persyaratan Penagihan Invoice " . $records[$modelName]['customer_name'] . "</th>
			</tr>";
	$mulai		= -1;
	for ($x = 1; $x <= $Total_Baris; $x++) {
		echo "<tr>";
		for ($y = 1; $y <= 2; $y++) {
			$mulai++;
			$Ono		= $kotak	= '';
			if (isset($rows_require[$mulai]['file_name']) && $rows_require[$mulai]['file_name']) {
				$Ono		= $rows_require[$mulai]['file_name'];
				$kotak		= "<table class='gridtable' width='100%'><tr><td height='10px'>&nbsp;&nbsp;&nbsp;</tr></table>";
			}
			echo "<td align='left' width='45%' valign='top'>" . $Ono . "</td>";
			echo "<td align='center' width='5%' valign='top'>" . $kotak . "</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}

## TANDA TERIMA INVOICE ##
if ($rows_invletter) {
	echo "<pagebreak>";
	echo $Header2;
	$Alamat_Inv	= $rows_cust['inv_address'];
	if ($Alamat_Inv == '' || empty($Alamat_Inv) || $Alamat_Inv == '-') {
		$Alamat_Inv	= $rows_cust['inv_address'];
	}
?>
	<table class='noborder' width='100%'>
		<tr>
			<td align='left' valign='top' width='17%'>No Surat Jalan</td>
			<td align='center' valign='top' width='3%'>:</td>
			<td align='left' valign='top' width='80%' class="textleft bold"><?php echo $rows_invletter[0]['nomor'] ?></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='17%'>Perusahaan</td>
			<td align='center' valign='top' width='3%'>:</td>
			<td valign='top' width='80%' class="textleft bold"><?php echo $rows_cust['name'] ?></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='17%'>Alamat</td>
			<td align='center' valign='top' width='3%'>:</td>
			<td align='left' valign='top' width='80%' class="textleft bold"><?php echo $Alamat_Inv ?></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='17%'>Nama PIC</td>
			<td align='center' valign='top' width='3%'>:</td>
			<td valign='top' width='80%' class="textleft bold"><?php echo $rows_cust['finance'] ?></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='17%'>Nomor Telpon</td>
			<td align='center' valign='top' width='3%'>:</td>
			<td align='left' valign='top' width='80%' class="textleft bold"><?php echo $rows_cust['phone'] ?></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='17%'>Nomor HP</td>
			<td align='center' valign='top' width='3%'>:</td>
			<td valign='top' width='80%' class="textleft bold"><?php echo $rows_cust['hp_finance'] ?></td>
		</tr>
		<tr>
			<td align='left' valign='top' colspan='3' height='20' width='100%'></td>
		</tr>

	</table>
	<?php
	$arr_Ket	= array(
		'Invoice',
		'PO',
		'Surat Jalan',
		'Berita Acara',
		'Faktur Pajak'
	);

	$No_PO		= '-';
	$No_SO		= '-';
	if ($arr_PO) {
		$No_PO	= implode(',', $arr_PO);
	}
	if ($arr_SO) {
		$No_SO	= implode(',', $arr_SO);
	}
	$arr_Val	= array(
		$rows_header['invoice_no'],
		$No_PO,
		$No_SO,
		'-',
		$rows_header['no_faktur']

	);

	?>
	<table class="gridtable" width='100%'>
		<tr>
			<td align='left' valign='top' colspan='8' width='100%'>Detail Dokumen :</td>
		</tr>
		<tr>
			<th width='5%' align='center'>No.</th>
			<th width='50%' align='center'>Keterangan</th>
			<th width='35%' align='center'>Nomor</th>
			<th width='10%' align='center'>Qty</th>
		</tr>
		<?php
		$intL	= 0;
		foreach ($arr_Ket as $key => $vals) {
			$intL++;
			echo "<tr>";
			echo "<td width='5%' align='center'>" . $intL . "</td>";
			echo "<td width='50%' align='left'>" . $vals . "</td>";
			echo "<td width='35%' align='left'>" . $arr_Val[$key] . "</td>";
			echo "<td width='10%' align='center'></td>";
			echo "</tr>";
		}
		?>

	</table>

	<div id='space'></div>
	<div id='space'></div>
	<table class='noborder' width='100%'>
		<tr>
			<td align="left" colspan="2">Tanggal : <?php echo date('d F Y', strtotime($rows_header['datet'])); ?></td>
		</tr>
		<tr>
			<td align='left' valign='top' width='70%'>Sentral Tehnologi Managemen,<br>Pengirim,<br><br><br><br><br><br>-----------------------------------</td>
			<td align='left' valign='top' width='30%'><br>Penerima,<br><br><br><br><br><br><br>-----------------------------------</td>
		</tr>
	</table>
	<div id='space'></div>
	<div id='space'></div>
<?php
}
## END TANDA TERIMA INVOICE ##

## NOTES INVOICE ##
$Imp_Order		= implode("','",$arr_SO);
$Query_Notes	= "SELECT * FROM letter_orders WHERE no_so IN('".$Imp_Order."') AND NOT(notes_invoice IS NULL OR notes_invoice = '' OR notes_invoice ='-')";

$rows_Notes		= $this->db->query($Query_Notes)->result();
if($rows_Notes){
	$Header3  = "
				<div id='space'></div>
				<div id='space'></div>
				<table class='noborder2' width='100%'>
					<tr>
						<td width='30%' align='left' rowspan='2'>
							<img src='" . $img_file . "' width='160' height='70'/>
						</td>
						<td><div style='font-size:18px;font-weight: bold;'>PT. SENTRAL TEHNOLOGI MANAGEMEN</div></td>
					</tr>
					<tr>
						<td valign='bottom'><div style='font-size:9px;font-weight: bold;'>TEMPERATURE, PRESSURE, MASS, DIMENSION, ANALITICAL INSTRUMENT, FORCE, ISO 17025</div></td>
					</tr>
					<tr>
						<td colspan='2'><img src='" . $img_file2 . "'/></td>
					</tr>
					<tr>
						<td class='textcenter bold' colspan='2'><div style='font-size:18px;font-weight: bold;'>Notes Invoice</div></td>
					</tr>
					<tr>
						<td height='20px' colspan='2'></td>
					</tr>
				</table>";
	echo "<pagebreak>";
	echo $Header3;
	
	echo"
		<table class='gridtable' width='100%'>
			<tr>
				<td align='left' valign='top' colspan='3' width='100%'>Detail Notes :</td>
			</tr>
			<tr>
				<th width='5%' align='center'>No.</th>
				<th width='20%' align='center'>No SO</th>
				<th width='75%' align='center'>Notes Invoice</th>
			</tr>";
			
			$intL	= 0;
			foreach ($rows_Notes as $key => $vals) {
				$intL++;
				echo "<tr>";
				echo "<td width='5%' align='center'>" . $intL . "</td>";
				echo "<td width='20%' align='left'>" . $vals->no_so . "</td>";
				echo "<td width='75%' align='left'>" . $vals->notes_invoice . "</td>";
				echo "</tr>";
			}
			
echo"
		</table>
	";
}

$html = ob_get_contents();
ob_end_clean();
//echo $html;exit;
$mpdf->WriteHTML($html);
$mpdf->Output($rows_header['id'] . ".pdf", 'I');
//$mpdf->Error();
exit;
?>
