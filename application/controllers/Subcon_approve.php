<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subcon_approve extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->database();
        if (!$this->session->userdata('isSISCALlogin')) {
            redirect('login');
        }
        $controller            = ucfirst(strtolower($this->uri->segment(1)));
        $this->Arr_Akses            = getAcccesmenu($controller);
    }

    public function index()
    {
        $Arr_Akses = $this->Arr_Akses;
        if ($Arr_Akses['read'] != '1') {
            $this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
            redirect(site_url('dashboard'));
        }

        $get_Data_Open            = $this->master_model->getData('        subcon_purchase_orders', 'sts_subcon', 'APV');


        $data = array(
            'title' => 'Data Approve',
            'action'        => 'index',
            'row'            => $get_Data_Open,
            'akses_menu'    => $Arr_Akses,
        );

        // var_dump($data);
        // die;

        $this->load->view('Subcon_approve/index', $data);
    }


    public function edit($id = '')
    {
        $Arr_Akses = $this->Arr_Akses;
        if ($Arr_Akses['update'] != '1') {
            $this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
            redirect(site_url('dashboard'));
        }
        if ($this->input->post()) {
            $data_subcon                    = array(
                // 'subcon_pono' => $this->input->post('subcon_pono'),
                // 'datet' => $this->input->post('datet'),
                // 'supplier_name' => $this->input->post('supplier_name'),
                // 'pic_name' => $this->input->post('pic_name'),
                // 'exc_ppn' => $this->input->post('exc_ppn'),
                // 'dpp' => $this->input->post('dpp'),
                // 'ppn' => $this->input->post('ppn'),
                // 'grand_tot' => $this->input->post('grand_tot'),
                'id' => $this->input->post('id'),
                // 'item' => $this->input->post('item'),
            );
            $Arr_Kembali            = array();
            // unset($data_subcon['item']);
            $data_subcon['modified_by']    = $this->session->userdata('siscal_username');
            $data_subcon['modified_date']    = date('Y-m-d H:i:s');
            $data_subcon['sts_subcon'] = 'OPN';
            if ($this->master_model->getUpdate('subcon_purchase_orders', $data_subcon, 'id', $data_subcon['id'])) {
                $Arr_Kembali        = array(
                    'status'        => '1',
                    'pesan'            => 'Edit Data Success. Thank you & have a nice day.......'
                );
                // var_dump($Arr_Kembali);
                // die();
                history('Edit Data Approve' . $data_subcon['id']);
            } else {
                $Arr_Kembali        = array(
                    'status'        => '2',
                    'pesan'            => 'Edit Data failed. Please try again later......'
                );
            }
            echo json_encode($Arr_Kembali);
        } else {
            $controller            = ucfirst(strtolower($this->uri->segment(1)));
            $Arr_Akses            = getAcccesmenu($controller);
            if ($Arr_Akses['read'] != '1') {
                $this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
                redirect(site_url('dashboard'));
            }

            $get_Edit_Data_Open            = $this->master_model->getData('subcon_purchase_orders', 'id', $id);
            $get_Detail_Data_Open            = $this->master_model->getData('subcon_purchase_order_details', 'subcon_purchase_order_id', $id);
            $get_Akomodasi                 = $this->master_model->getData('subcon_accommodations', 'subcon_purchase_order_id', $id);





            $data = array(
                'title' => 'Edit Data Approve',
                'action'        => 'index',
                'row'            => $get_Edit_Data_Open,
                'detail'        =>  $get_Detail_Data_Open,
                'akses_menu'    => $Arr_Akses,
                'akomodasi' => $get_Akomodasi

            );

            // var_dump($data);
            // die();

            $this->load->view('Subcon_approve/edit', $data);
        }
    }
}
