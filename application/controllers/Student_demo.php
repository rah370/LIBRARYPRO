<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        
        // Check if user is logged in and is student
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'student') {
            redirect('auth/login');
        }
    }
    
    public function index() {
        $data['title'] = 'Student Library - Browse Books';
        $data['user'] = $this->session->userdata();
        
        // Mock books data
        $data['books'] = array(
            (object) array(
                'id' => 1,
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => '978-0743273565',
                'description' => 'A story of the fabulously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan.',
                'available' => 1
            ),
            (object) array(
                'id' => 2,
                'title' => '1984',
                'author' => 'George Orwell', 
                'isbn' => '978-0451524935',
                'description' => 'A dystopian novel about totalitarianism and surveillance society.',
                'available' => 1
            ),
            (object) array(
                'id' => 3,
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '978-0446310789',
                'description' => 'The story of young Scout Finch and her father Atticus in a racially divided Alabama town.',
                'available' => 0
            )
        );
        
        $this->load->view('student/header', $data);
        $this->load->view('student/browse_books', $data);
        $this->load->view('student/footer');
    }
    
    public function borrow($book_id) {
        $this->session->set_flashdata('success', 'Book borrowed successfully! (Demo mode - no database)');
        redirect('student');
    }
    
    public function my_borrows() {
        $data['title'] = 'My Borrowed Books';
        $data['user'] = $this->session->userdata();
        
        // Mock borrow data
        $data['borrows'] = array(
            (object) array(
                'id' => 1,
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'borrow_date' => '2024-01-15 10:00:00',
                'return_date' => '2024-01-29 10:00:00',
                'status' => 'borrowed',
                'actual_return_date' => null
            )
        );
        
        $this->load->view('student/header', $data);
        $this->load->view('student/my_borrows', $data);
        $this->load->view('student/footer');
    }
}