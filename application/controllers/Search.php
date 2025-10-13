<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Book_model');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }
    
    public function index() {
        $data['title'] = 'Advanced Book Search';
        $data['user'] = $this->session->userdata();
        $data['books'] = array();
        $data['search_performed'] = false;
        
        if ($this->input->get()) {
            $filters = array(
                'title' => $this->input->get('title'),
                'author' => $this->input->get('author'),
                'isbn' => $this->input->get('isbn'),
                'available_only' => $this->input->get('available_only'),
                'sort_by' => $this->input->get('sort_by') ?: 'title',
                'sort_order' => $this->input->get('sort_order') ?: 'ASC'
            );
            
            $data['books'] = $this->advanced_search($filters);
            $data['search_performed'] = true;
            $data['filters'] = $filters;
        }
        
        if ($this->session->userdata('role') === 'admin') {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/advanced_search', $data);
            $this->load->view('admin/footer');
        } else {
            $this->load->view('student/header', $data);
            $this->load->view('student/advanced_search', $data);
            $this->load->view('student/footer');
        }
    }
    
    public function api() {
        // API endpoint for AJAX search
        header('Content-Type: application/json');
        
        $keyword = $this->input->get('q');
        if (empty($keyword)) {
            echo json_encode(['books' => []]);
            return;
        }
        
        $this->db->select('id, title, author, isbn, available');
        $this->db->from('books');
        $this->db->like('title', $keyword);
        $this->db->or_like('author', $keyword);
        $this->db->or_like('isbn', $keyword);
        $this->db->limit(10);
        
        if ($this->session->userdata('role') === 'student') {
            $this->db->where('available', 1);
        }
        
        $query = $this->db->get();
        $books = $query->result();
        
        echo json_encode(['books' => $books]);
    }
    
    private function advanced_search($filters) {
        $this->db->select('*');
        $this->db->from('books');
        
        if (!empty($filters['title'])) {
            $this->db->like('title', $filters['title']);
        }
        
        if (!empty($filters['author'])) {
            $this->db->like('author', $filters['author']);
        }
        
        if (!empty($filters['isbn'])) {
            $this->db->like('isbn', $filters['isbn']);
        }
        
        if ($filters['available_only'] === '1') {
            $this->db->where('available', 1);
        }
        
        // Sorting
        $allowed_sorts = ['title', 'author', 'created_at'];
        $sort_by = in_array($filters['sort_by'], $allowed_sorts) ? $filters['sort_by'] : 'title';
        $sort_order = ($filters['sort_order'] === 'DESC') ? 'DESC' : 'ASC';
        
        $this->db->order_by($sort_by, $sort_order);
        
        $query = $this->db->get();
        return $query->result();
    }
}