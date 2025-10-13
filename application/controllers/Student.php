<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Book_model');
        $this->load->model('Borrow_model');
        
        // Check if user is logged in and is student
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'student') {
            redirect('auth/login');
        }
    }
    
    public function index() {
        $data['title'] = 'Student Library - Browse Books';
        $data['user'] = $this->session->userdata();
        
        try {
            $data['books'] = $this->Book_model->get_all_books();
        } catch (Exception $e) {
            $data['books'] = array();
            $data['db_error'] = 'Error loading books: ' . $e->getMessage();
        }
        
        $this->load->view('student/header', $data);
        $this->load->view('student/browse_books', $data);
        $this->load->view('student/footer');
    }
    
    public function search() {
        $keyword = $this->input->get('keyword');
        $data['keyword'] = $keyword;
        $data['title'] = 'Search Results';
        $data['user'] = $this->session->userdata();
        
        try {
            $data['books'] = $this->Book_model->search_books($keyword);
        } catch (Exception $e) {
            $data['books'] = array();
            $data['db_error'] = 'Error searching books: ' . $e->getMessage();
        }
        
        $this->load->view('student/header', $data);
        $this->load->view('student/search_results', $data);
        $this->load->view('student/footer');
    }
    
    public function borrow($book_id) {
        $book = $this->Book_model->get_book_by_id($book_id);
        
        if (!$book) {
            show_404();
        }
        
        // If copies_available or status indicate no availability, reject the borrow.
        $is_available = true;
        if (!$book || !isset($book->status) || !isset($book->copies_available)) {
            $is_available = false;
        } else {
            $is_available = ($book->status === 'available' && $book->copies_available > 0);
        }

        if (!$is_available) {
            $this->session->set_flashdata('error', 'This book is currently not available.');
            redirect('student');
        }
        
        $user_id = $this->session->userdata('user_id');
        
        // Allow students to request how many days they want to borrow the book for.
        $requested_days = $this->input->post('days') ?? $this->input->get('days') ?? 14;
        $requested_days = (int) $requested_days;
        if ($requested_days < 1) $requested_days = 1;
        if ($requested_days > 30) $requested_days = 30; // server-side cap

        if ($this->Borrow_model->borrow_book($book_id, $user_id, $requested_days)) {
            $this->session->set_flashdata('success', 'Book borrowed successfully! Return date: ' . date('Y-m-d', strtotime('+' . $requested_days . ' days')));
        } else {
            $this->session->set_flashdata('error', 'Failed to borrow book. Please try again.');
        }
        
        redirect('student');
    }
    
    public function my_borrows() {
        $data['title'] = 'My Borrowed Books';
        $data['user'] = $this->session->userdata();
        
        $user_id = $this->session->userdata('user_id');
        $data['borrows'] = $this->Borrow_model->get_user_borrows($user_id);
        
        $this->load->view('student/header', $data);
        $this->load->view('student/my_borrows', $data);
        $this->load->view('student/footer');
    }
    
    public function profile() {
        $data['title'] = 'My Profile';
        $data['user_data'] = $this->session->userdata();
        $data['user'] = $this->User_model->get_user_by_id($this->session->userdata('user_id'));
        $data['error'] = '';
        $data['success'] = '';
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[255]');
            $this->form_validation->set_rules('password', 'New Password', 'trim|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|matches[password]');
            
            if ($this->form_validation->run() === TRUE) {
                $email = $this->input->post('email');
                $user_id = $this->session->userdata('user_id');
                
                if ($this->User_model->email_exists($email, $user_id)) {
                    $data['error'] = 'Email already exists.';
                } else {
                    $update_data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'email' => $email
                    );
                    
                    // Add password to update if provided
                    $password = $this->input->post('password');
                    if (!empty($password)) {
                        $update_data['password'] = $password;
                    }
                    
                    if ($this->User_model->update_user($user_id, $update_data)) {
                        // Update session data
                        $this->session->set_userdata('first_name', $update_data['first_name']);
                        $this->session->set_userdata('last_name', $update_data['last_name']);
                        $this->session->set_userdata('email', $update_data['email']);
                        
                        $data['success'] = 'Profile updated successfully!';
                        $data['user'] = $this->User_model->get_user_by_id($user_id); // Refresh data
                        $data['user_data'] = $this->session->userdata(); // Refresh session data
                    } else {
                        $data['error'] = 'Failed to update profile. Please try again.';
                    }
                }
            }
        }
        
        $this->load->view('student/header', $data);
        $this->load->view('student/profile', $data);
        $this->load->view('student/footer');
    }
}