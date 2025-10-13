<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Library_simple extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }
    
    public function index() {
        $data['title'] = 'Library - Browse Books';
        $data['books'] = array(); // Empty array for now
        
        $this->load->view('library/header', $data);
        $this->load->view('library/index', $data);
        $this->load->view('library/footer');
    }
    
    public function test() {
        echo "Library Simple controller is working!";
        echo "<br>Views should load properly.";
        echo "<br><a href='" . base_url('library_simple') . "'>Go to Library</a>";
    }
}
