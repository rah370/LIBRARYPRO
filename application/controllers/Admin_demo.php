<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        
        // Check if user is logged in and is admin
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            redirect('auth/login');
        }
    }
    
    public function index() {
        $data['title'] = 'Admin Dashboard';
        $data['user'] = $this->session->userdata();
        
        // Mock statistics
        $data['book_stats'] = array(
            'total_books' => 10,
            'available_books' => 7,
            'borrowed_books' => 3,
            'popular_books' => array()
        );
        
        $data['borrow_stats'] = array(
            'total_borrows' => 15,
            'active_borrows' => 3,
            'overdue_borrows' => 1
        );
        
        $data['overdue_borrows'] = array();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/footer');
    }
    
    public function books() {
        $data['title'] = 'Manage Books';
        $data['user'] = $this->session->userdata();
        
        // Mock books data
        $data['books'] = array(
            (object) array(
                'id' => 1,
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => '978-0743273565',
                'description' => 'A classic American novel',
                'available' => 1,
                'borrow_count' => 5
            ),
            (object) array(
                'id' => 2,
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '978-0446310789',
                'description' => 'A gripping tale of racial injustice',
                'available' => 0,
                'borrow_count' => 3
            )
        );
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/books', $data);
        $this->load->view('admin/footer');
    }
    
    public function add_book() {
        $data['title'] = 'Add New Book';
        $data['user'] = $this->session->userdata();
        $data['error'] = '';
        $data['success'] = '';
        
        if ($this->input->post()) {
            $data['success'] = 'Book added successfully! (Demo mode - no database)';
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/add_book', $data);
        $this->load->view('admin/footer');
    }
}