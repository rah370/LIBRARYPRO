<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Library extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        
        // Load models with error handling
        try {
            $this->load->model('Book_model');
            $this->load->model('Borrow_model');
        } catch (Exception $e) {
            // Models failed to load, but continue
        }
    }
    
    public function index() {
        $data['title'] = 'Library - Browse Books';
        
        try {
            if (isset($this->Book_model)) {
                $data['books'] = $this->Book_model->get_all_books();
            } else {
                $data['books'] = array();
                $data['db_error'] = 'Database connection not available. Please check your configuration.';
            }
        } catch (Exception $e) {
            $data['books'] = array();
            $data['db_error'] = 'Error loading books: ' . $e->getMessage();
        }
        
        $this->load->view('library/header', $data);
        $this->load->view('library/index', $data);
        $this->load->view('library/footer');
    }
    
    public function search() {
        $keyword = $this->input->get('keyword');
        $data['keyword'] = $keyword;
        $data['title'] = 'Search Results';
        
        try {
            if (isset($this->Book_model)) {
                $data['books'] = $this->Book_model->search_books($keyword);
            } else {
                $data['books'] = array();
                $data['db_error'] = 'Database connection not available.';
            }
        } catch (Exception $e) {
            $data['books'] = array();
            $data['db_error'] = 'Error searching books: ' . $e->getMessage();
        }
        
        $this->load->view('library/header', $data);
        $this->load->view('library/search_results', $data);
        $this->load->view('library/footer');
    }
    
    public function borrow($book_id = null) {
        if (!$book_id) {
            redirect('library');
        }
        
        try {
            if (isset($this->Book_model)) {
                $data['book'] = $this->Book_model->get_book_by_id($book_id);
                if (!$data['book'] || !$data['book']->available) {
                    $this->session->set_flashdata('error', 'Book not available for borrowing.');
                    redirect('library');
                }
            } else {
                $this->session->set_flashdata('error', 'Database connection not available.');
                redirect('library');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error loading book: ' . $e->getMessage());
            redirect('library');
        }
        
        $data['title'] = 'Borrow Book';
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('user_name', 'Name', 'required|trim');
            $this->form_validation->set_rules('user_email', 'Email', 'required|valid_email|trim');
            
            if ($this->form_validation->run()) {
                $user_name = $this->input->post('user_name');
                $user_email = $this->input->post('user_email');
                
                try {
                    if (isset($this->Borrow_model)) {
                        if ($this->Borrow_model->borrow_book($book_id, $user_name, $user_email)) {
                            $this->session->set_flashdata('success', 'Book borrowed successfully! Please return within 14 days.');
                            redirect('library/my_borrows');
                        } else {
                            $this->session->set_flashdata('error', 'Failed to borrow book. Please try again.');
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Database connection not available.');
                    }
                } catch (Exception $e) {
                    $this->session->set_flashdata('error', 'Error borrowing book: ' . $e->getMessage());
                }
            }
        }
        
        $this->load->view('library/header', $data);
        $this->load->view('library/borrow_form', $data);
        $this->load->view('library/footer');
    }
    
    public function my_borrows() {
        $user_email = $this->session->userdata('user_email');
        if (!$user_email) {
            $this->session->set_flashdata('error', 'Please enter your email to view your borrows.');
            redirect('library');
        }
        
        $data['title'] = 'My Borrowed Books';
        
        try {
            if (isset($this->Borrow_model)) {
                $data['borrows'] = $this->Borrow_model->get_user_borrows($user_email);
            } else {
                $data['borrows'] = array();
                $data['db_error'] = 'Database connection not available.';
            }
        } catch (Exception $e) {
            $data['borrows'] = array();
            $data['db_error'] = 'Error loading borrows: ' . $e->getMessage();
        }
        
        $this->load->view('library/header', $data);
        $this->load->view('library/my_borrows', $data);
        $this->load->view('library/footer');
    }
    
    public function return_book($borrow_id = null) {
        if (!$borrow_id) {
            redirect('library');
        }
        
        try {
            if (isset($this->Borrow_model)) {
                if ($this->Borrow_model->return_book($borrow_id)) {
                    $this->session->set_flashdata('success', 'Book returned successfully!');
                } else {
                    $this->session->set_flashdata('error', 'Failed to return book. Please try again.');
                }
            } else {
                $this->session->set_flashdata('error', 'Database connection not available.');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error returning book: ' . $e->getMessage());
        }
        
        redirect('library/my_borrows');
    }
    
    public function admin() {
        $data['title'] = 'Admin - All Borrows';
        
        try {
            if (isset($this->Borrow_model)) {
                $data['borrows'] = $this->Borrow_model->get_all_borrows();
            } else {
                $data['borrows'] = array();
                $data['db_error'] = 'Database connection not available.';
            }
        } catch (Exception $e) {
            $data['borrows'] = array();
            $data['db_error'] = 'Error loading borrows: ' . $e->getMessage();
        }
        
        $this->load->view('library/header', $data);
        $this->load->view('library/admin', $data);
        $this->load->view('library/footer');
    }
    
    public function set_user() {
        $user_email = $this->input->post('user_email');
        if ($user_email) {
            $this->session->set_userdata('user_email', $user_email);
            redirect('library/my_borrows');
        }
        redirect('library');
    }
    
    public function test() {
        echo "Library controller is working!";
        echo "<br>Basic functionality is working.";
        echo "<br><a href='" . base_url('library') . "'>Go to Library</a>";
    }
}
