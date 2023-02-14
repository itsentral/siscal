<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('rupiah')) {
	
	function convert_to_rupiah($angka) {
		$rupiah = "Rp. " . number_format($angka,2,',','.');
		return $rupiah;
	}
	
}