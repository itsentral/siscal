<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_invoice_subcon extends CI_Model
{

	var $table 			= 'subcon_cpr_details';
	var $column_order 	= array('subcon_cpr_headers.supplier_name', 'subcon_cpr_details.total', 'subcon_cpr_headers.descr', 'subcon_cpr_headers.payment_date', 'subcon_cpr_headers.payment_reff', null); 
	var $column_search 	= array('subcon_cpr_headers.supplier_name', 'subcon_cpr_details.total', 'subcon_cpr_headers.descr', 'subcon_cpr_headers.payment_date', 'subcon_cpr_headers.payment_reff'); 
	var $order 			= array('subcon_cpr_details.subcon_cpr_header_id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		
	}

	private function _get_datatables_query()
	{

		$this->db->select('	subcon_cpr_headers.supplier_name,
							subcon_cpr_details.cust_invoice_no,
							subcon_cpr_details.subcon_cpr_header_id,
							subcon_cpr_details.total,
							subcon_cpr_headers.descr,
							subcon_cpr_headers.payment_date,
							subcon_cpr_headers.payment_reff ');
		$this->db->from($this->table);
		$this->db->join('subcon_cpr_headers', 'subcon_cpr_details.subcon_cpr_header_id = subcon_cpr_headers.id');
		$this->db->where('YEAR(subcon_cpr_headers.datet) >', "2021");

		if ($this->input->post('noInvoice') != "") {
            $this->db->where('subcon_cpr_details.cust_invoice_no =', $this->input->post('noInvoice'));
        }

		if ($this->input->post('noPCR') != "") {
            $this->db->where('subcon_cpr_details.subcon_cpr_header_id =', $this->input->post('noPCR'));
        }
		
		if ($this->input->post('noRef') == "P") {
            $this->db->where('subcon_cpr_headers.payment_reff !=', "");
        }

		if ($this->input->post('noRef') == "U") {
            $this->db->where('subcon_cpr_headers.payment_reff =', null);
        }



		$i = 0;

		foreach ($this->column_search as $item)
		{
			if ($_POST['search']['value'])
			{
				if ($i === 0)
				{
					$this->db->group_start(); 
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) 
					$this->db->group_end(); 
			}
			$i++;
		}

		if (isset($_POST['order']))
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->select('	subcon_cpr_headers.supplier_name,
							subcon_cpr_details.cust_invoice_no,
							subcon_cpr_details.subcon_cpr_header_id,
							subcon_cpr_details.total,
							subcon_cpr_headers.descr,
							subcon_cpr_headers.payment_date,
							subcon_cpr_headers.payment_reff ');
		$this->db->from($this->table);
		$this->db->join('subcon_cpr_headers', 'subcon_cpr_details.subcon_cpr_header_id = subcon_cpr_headers.id');
		return $this->db->count_all_results();
	}

}
