<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Book_model');
        $this->load->model('Borrow_model');
        
        // Check if user is logged in and is admin
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            redirect('auth/login');
        }
    }
    
    public function index() {
        $data['title'] = 'Admin Dashboard - Library Management';
        $data['user'] = $this->session->userdata();
        
        // Get comprehensive statistics
        try {
            $data['book_stats'] = $this->Book_model->get_book_statistics();
            $data['borrow_stats'] = $this->Borrow_model->get_borrow_statistics();
            $data['user_stats'] = $this->User_model->get_user_statistics();
            $data['overdue_borrows'] = $this->Borrow_model->get_overdue_borrows();
            $data['recent_activities'] = $this->get_recent_activities();
        } catch (Exception $e) {
            // Handle errors gracefully
            $data['book_stats'] = ['total_books' => 0, 'available_books' => 0];
            $data['borrow_stats'] = ['active_borrows' => 0, 'overdue_borrows' => 0, 'todays_borrows' => 0];
            $data['user_stats'] = ['total_users' => 0, 'students' => 0, 'active_users' => 0];
            $data['overdue_borrows'] = [];
            $data['recent_activities'] = [];
            $data['error'] = 'Error loading dashboard data: ' . $e->getMessage();
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/footer');
    }
    
    // Book Management
    public function books() {
        $data['title'] = 'Manage Books';
        $data['user'] = $this->session->userdata();
        $data['books'] = $this->Book_model->get_all_books_admin();
        
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
            $this->form_validation->set_rules('title', 'Title', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('author', 'Author', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('isbn', 'ISBN', 'required|trim|max_length[20]');
            $this->form_validation->set_rules('description', 'Description', 'trim');
            $this->form_validation->set_rules('category', 'Category', 'trim|max_length[100]');
            $this->form_validation->set_rules('copies_total', 'Total Copies', 'integer|greater_than[0]');
            
            if ($this->form_validation->run() === TRUE) {
                $isbn = $this->input->post('isbn');
                
                if ($this->Book_model->isbn_exists($isbn)) {
                    $data['error'] = 'A book with this ISBN already exists.';
                } else {
                    $book_data = array(
                        'title' => $this->input->post('title'),
                        'author' => $this->input->post('author'),
                        'isbn' => $isbn,
                        'description' => $this->input->post('description'),
                        'category' => $this->input->post('category'),
                        'copies_total' => $this->input->post('copies_total') ?: 1,
                        'copies_available' => $this->input->post('copies_total') ?: 1,
                        'status' => 'available'
                    );
                    
                    if ($this->Book_model->add_book($book_data)) {
                        $data['success'] = 'Book added successfully!';
                        $data['form_data'] = array(); // Clear form
                    } else {
                        $data['error'] = 'Failed to add book. Please try again.';
                    }
                }
            }
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/add_book', $data);
        $this->load->view('admin/footer');
    }
    
    public function edit_book($id) {
        $book = $this->Book_model->get_book_by_id($id);
        if (!$book) {
            show_404();
        }
        
        $data['title'] = 'Edit Book';
        $data['user'] = $this->session->userdata();
        $data['book'] = $book;
        $data['error'] = '';
        $data['success'] = '';
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'Title', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('author', 'Author', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('isbn', 'ISBN', 'required|trim|max_length[20]');
            $this->form_validation->set_rules('description', 'Description', 'trim');
            
            if ($this->form_validation->run() === TRUE) {
                $isbn = $this->input->post('isbn');
                
                if ($this->Book_model->isbn_exists($isbn, $id)) {
                    $data['error'] = 'A book with this ISBN already exists.';
                } else {
                    $book_data = array(
                        'title' => $this->input->post('title'),
                        'author' => $this->input->post('author'),
                        'isbn' => $isbn,
                        'description' => $this->input->post('description')
                    );
                    
                        // Handle cover upload if present
                        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
                            $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/avif', 'image/svg+xml'];
                            $maxBytes = 2 * 1024 * 1024; // 2MB
                            if ($_FILES['cover']['size'] > $maxBytes) {
                                $data['error'] = 'Cover file too large. Max 2MB.';
                            } elseif (!in_array($_FILES['cover']['type'], $allowed)) {
                                $data['error'] = 'Invalid cover file type.';
                            } else {
                                $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
                                $safe = preg_replace('/[^a-z0-9\-_.]/i', '_', pathinfo($_FILES['cover']['name'], PATHINFO_FILENAME));
                                $targetDir = FCPATH . 'frontend/assets/books/';
                                if (!is_dir($targetDir)) @mkdir($targetDir, 0755, true);
                                $targetName = $safe . '_' . time() . '.' . $ext;
                                $targetPath = $targetDir . $targetName;
                                if (move_uploaded_file($_FILES['cover']['tmp_name'], $targetPath)) {
                                    $book_data['cover'] = 'frontend/assets/books/' . $targetName;
                                } else {
                                    $data['error'] = 'Failed to move uploaded cover file.';
                                }
                            }
                        }

                        if (empty($data['error'])) {
                            if ($this->Book_model->update_book($id, $book_data)) {
                                $data['success'] = 'Book updated successfully!';
                                $data['book'] = $this->Book_model->get_book_by_id($id); // Refresh data
                            } else {
                                $data['error'] = 'Failed to update book. Please try again.';
                            }
                        }
                }
            }
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/edit_book', $data);
        $this->load->view('admin/footer');
    }
    
    public function delete_book($id) {
        $book = $this->Book_model->get_book_by_id($id);
        if (!$book) {
            show_404();
        }
        
        if ($this->Book_model->delete_book($id)) {
            $this->session->set_flashdata('success', 'Book deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete book.');
        }
        
        redirect('admin/books');
    }
    
    // Borrow Management
    public function borrows() {
        $data['title'] = 'Manage Borrows';
        $data['user'] = $this->session->userdata();
        $data['borrows'] = $this->Borrow_model->get_all_borrows();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/borrows', $data);
        $this->load->view('admin/footer');
    }
    
    public function return_book($borrow_id) {
        if ($this->Borrow_model->return_book($borrow_id)) {
            $this->session->set_flashdata('success', 'Book returned successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to return book.');
        }
        
        redirect('admin/borrows');
    }
    
    // Student Management - Redirect to unified user management
    public function students() {
        // Redirect to users page with student filter
        redirect('admin/users?role=student');
    }
    
    public function add_student() {
        // Redirect to unified user management with student role pre-selected
        redirect('admin/add_user?role=student');
    }
    
    // Enhanced Features
    public function get_recent_activities() {
        $activities = array();
        
        try {
            // Recent borrows
            $recent_borrows = $this->Borrow_model->get_recent_borrows(5);
            foreach ($recent_borrows as $borrow) {
                $activities[] = array(
                    'type' => 'borrow',
                    'message' => $borrow->first_name . ' ' . $borrow->last_name . ' borrowed "' . $borrow->title . '"',
                    'time' => $borrow->borrow_date,
                    'icon' => 'fas fa-book-open text-success'
                );
            }
            
            // Recent returns
            $recent_returns = $this->Borrow_model->get_recent_returns(5);
            foreach ($recent_returns as $return) {
                $activities[] = array(
                    'type' => 'return',
                    'message' => $return->first_name . ' ' . $return->last_name . ' returned "' . $return->title . '"',
                    'time' => $return->return_date,
                    'icon' => 'fas fa-undo text-info'
                );
            }
            
            // Sort by time
            usort($activities, function($a, $b) {
                return strtotime($b['time']) - strtotime($a['time']);
            });
            
            return array_slice($activities, 0, 10);
        } catch (Exception $e) {
            return array();
        }
    }
    
    public function statistics() {
        $data['title'] = 'Library Statistics';
        $data['user'] = $this->session->userdata();
        
        try {
            $data['book_stats'] = $this->Book_model->get_book_statistics();
            $data['borrow_stats'] = $this->Borrow_model->get_borrow_statistics();
            $data['user_stats'] = $this->User_model->get_user_statistics();
            $data['monthly_stats'] = $this->get_monthly_statistics();
        } catch (Exception $e) {
            $data['error'] = 'Error loading statistics: ' . $e->getMessage();
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/statistics', $data);
        $this->load->view('admin/footer');
    }
    
    public function get_monthly_statistics() {
        $stats = array();
        
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $month_start = date('Y-m-01', strtotime("-$i months"));
            $month_end = date('Y-m-t', strtotime("-$i months"));
            
            // Count borrows for this month
            $this->db->select('COUNT(*) as count');
            $this->db->from('borrows');
            $this->db->where('borrow_date >=', $month_start);
            $this->db->where('borrow_date <=', $month_end . ' 23:59:59');
            $query = $this->db->get();
            
            $stats[] = array(
                'month' => date('M Y', strtotime($month_start)),
                'borrows' => $query->row()->count
            );
        }
        
        return $stats;
    }
    
    public function reports() {
        $data['title'] = 'Reports';
        $data['user'] = $this->session->userdata();
        
        // Get filter parameters
        $data['start_date'] = $this->input->get('start_date') ?: date('Y-m-01');
        $data['end_date'] = $this->input->get('end_date') ?: date('Y-m-d');
        $data['report_type'] = $this->input->get('report_type') ?: 'borrows';
        
        try {
            switch ($data['report_type']) {
                case 'borrows':
                    $data['report_data'] = $this->get_borrow_report($data['start_date'], $data['end_date']);
                    break;
                case 'overdue':
                    $data['report_data'] = $this->get_overdue_report();
                    break;
                case 'popular_books':
                    $data['report_data'] = $this->get_popular_books_report($data['start_date'], $data['end_date']);
                    break;
                case 'users':
                    $data['report_data'] = $this->get_user_report();
                    break;
                default:
                    $data['report_data'] = array();
            }
        } catch (Exception $e) {
            $data['error'] = 'Error generating report: ' . $e->getMessage();
            $data['report_data'] = array();
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/reports', $data);
        $this->load->view('admin/footer');
    }
    
    public function get_borrow_report($start_date, $end_date) {
        $this->db->select('borrows.*, books.title, books.author, books.isbn, users.first_name, users.last_name, users.username');
        $this->db->from('borrows');
        $this->db->join('books', 'books.id = borrows.book_id');
        $this->db->join('users', 'users.id = borrows.user_id');
        $this->db->where('DATE(borrows.borrow_date) >=', $start_date);
        $this->db->where('DATE(borrows.borrow_date) <=', $end_date);
        $this->db->order_by('borrows.borrow_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_overdue_report() {
        return $this->Borrow_model->get_overdue_borrows();
    }
    
    public function get_popular_books_report($start_date, $end_date) {
        $this->db->select('books.title, books.author, books.isbn, COUNT(borrows.id) as borrow_count');
        $this->db->from('books');
        $this->db->join('borrows', 'borrows.book_id = books.id');
        $this->db->where('DATE(borrows.borrow_date) >=', $start_date);
        $this->db->where('DATE(borrows.borrow_date) <=', $end_date);
        $this->db->group_by('books.id');
        $this->db->order_by('borrow_count', 'DESC');
        $this->db->limit(20);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_user_report() {
        $this->db->select('users.*, COUNT(borrows.id) as total_borrows');
        $this->db->from('users');
        $this->db->join('borrows', 'borrows.user_id = users.id', 'left');
        $this->db->where('users.role', 'student');
        $this->db->group_by('users.id');
        $this->db->order_by('total_borrows', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function system_status() {
        $data['title'] = 'System Status';
        $data['user'] = $this->session->userdata();
        
        // System health checks
        $data['database_status'] = $this->check_database_status();
        $data['file_permissions'] = $this->check_file_permissions();
        $data['server_info'] = $this->get_server_info();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/system_status', $data);
        $this->load->view('admin/footer');
    }
    
    private function check_database_status() {
        try {
            $query = $this->db->query("SELECT COUNT(*) as count FROM users");
            return array('status' => 'OK', 'message' => 'Database connection successful');
        } catch (Exception $e) {
            return array('status' => 'ERROR', 'message' => $e->getMessage());
        }
    }
    
    private function check_file_permissions() {
        $paths = array(
            APPPATH . 'sessions/',
            FCPATH . 'library.db'
        );
        
        $results = array();
        foreach ($paths as $path) {
            if (file_exists($path)) {
                $results[] = array(
                    'path' => $path,
                    'writable' => is_writable($path),
                    'readable' => is_readable($path)
                );
            }
        }
        
        return $results;
    }
    
    private function get_server_info() {
        return array(
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size')
        );
    }
    
    public function export_data() {
        $type = $this->input->get('type') ?: 'books';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $type . '_export_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        switch ($type) {
            case 'books':
                $this->export_books($output);
                break;
            case 'users':
                $this->export_users($output);
                break;
            case 'borrows':
                $this->export_borrows($output);
                break;
        }
        
        fclose($output);
    }
    
    private function export_books($output) {
        fputcsv($output, array('ID', 'Title', 'Author', 'ISBN', 'Category', 'Copies Total', 'Copies Available', 'Status'));
        
        $books = $this->Book_model->get_all_books_admin();
        foreach ($books as $book) {
            fputcsv($output, array(
                $book->id,
                $book->title,
                $book->author,
                $book->isbn,
                $book->category,
                $book->copies_total,
                $book->copies_available,
                $book->status
            ));
        }
    }
    
    private function export_users($output) {
        fputcsv($output, array('ID', 'Username', 'Email', 'First Name', 'Last Name', 'Role', 'Status', 'Created At'));
        
        $users = $this->User_model->get_all_students();
        foreach ($users as $user) {
            fputcsv($output, array(
                $user->id,
                $user->username,
                $user->email,
                $user->first_name,
                $user->last_name,
                $user->role,
                $user->status,
                $user->created_at
            ));
        }
    }
    
    private function export_borrows($output) {
        fputcsv($output, array('ID', 'Book Title', 'User Name', 'Borrow Date', 'Due Date', 'Return Date', 'Status'));
        
        $borrows = $this->Borrow_model->get_all_borrows();
        foreach ($borrows as $borrow) {
            fputcsv($output, array(
                $borrow->id,
                $borrow->title,
                $borrow->first_name . ' ' . $borrow->last_name,
                $borrow->borrow_date,
                $borrow->due_date,
                $borrow->return_date,
                $borrow->status
            ));
        }
    }
    
    /**
     * User Management - Admin Only Registration
     */
    public function users() {
        $data['title'] = 'User Management';
        $data['user'] = $this->session->userdata();
        
        // Get all users with pagination
        $config['base_url'] = base_url('admin/users');
        $config['total_rows'] = $this->User_model->count_all_users();
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['users'] = $this->User_model->get_users($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/users', $data);
        $this->load->view('admin/footer');
    }
    
    /**
     * Add New User - Admin Only
     */
    public function add_user() {
        $data['title'] = 'Add New User';
        $data['user'] = $this->session->userdata();
        $data['error'] = '';
        $data['success'] = '';
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|min_length[2]|max_length[50]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|min_length[2]|max_length[50]');
            $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[3]|max_length[50]|callback_check_username');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[100]|callback_check_email');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[255]');
            $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,student,librarian]');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]');
            $this->form_validation->set_rules('address', 'Address', 'trim|max_length[255]');
            
            if ($this->form_validation->run() === TRUE) {
                $user_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'username' => $this->input->post('username'),
                    'email' => $this->input->post('email'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'role' => $this->input->post('role'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->User_model->create_user($user_data)) {
                    $data['success'] = 'User created successfully!';
                    redirect('admin/users');
                } else {
                    $data['error'] = 'Failed to create user. Please try again.';
                }
            }
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/add_user', $data);
        $this->load->view('admin/footer');
    }
    
    /**
     * Edit User
     */
    public function edit_user($user_id) {
        if (!$user_id) {
            redirect('admin/users');
        }
        
        $data['title'] = 'Edit User';
        $data['user'] = $this->session->userdata();
        $data['edit_user'] = $this->User_model->get_user_by_id($user_id);
        $data['error'] = '';
        $data['success'] = '';
        
        if (!$data['edit_user']) {
            redirect('admin/users');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|min_length[2]|max_length[50]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|min_length[2]|max_length[50]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[100]');
            $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,student,librarian]');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive,suspended]');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]');
            $this->form_validation->set_rules('address', 'Address', 'trim|max_length[255]');
            
            if ($this->form_validation->run() === TRUE) {
                $update_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'role' => $this->input->post('role'),
                    'status' => $this->input->post('status'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                
                // Update password if provided
                if ($this->input->post('password')) {
                    $update_data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                }
                
                if ($this->User_model->update_user($user_id, $update_data)) {
                    $data['success'] = 'User updated successfully!';
                    $data['edit_user'] = $this->User_model->get_user_by_id($user_id); // Refresh data
                } else {
                    $data['error'] = 'Failed to update user. Please try again.';
                }
            }
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/edit_user', $data);
        $this->load->view('admin/footer');
    }
    
    /**
     * Delete User
     */
    public function delete_user($user_id) {
        if (!$user_id || $user_id == $this->session->userdata('user_id')) {
            // Can't delete yourself
            $this->session->set_flashdata('error', 'Cannot delete your own account.');
            redirect('admin/users');
        }
        
        if ($this->User_model->delete_user($user_id)) {
            $this->session->set_flashdata('success', 'User deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user.');
        }
        
        redirect('admin/users');
    }
    
    /**
     * Validation callback for username
     */
    public function check_username($username) {
        if ($this->User_model->username_exists($username)) {
            $this->form_validation->set_message('check_username', 'This username is already taken.');
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Validation callback for email
     */
    public function check_email($email) {
        if ($this->User_model->email_exists($email)) {
            $this->form_validation->set_message('check_email', 'This email is already registered.');
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Advanced Book Management
     */
    public function book_management() {
        $data['title'] = 'Advanced Book Management';
        $data['user'] = $this->session->userdata();
        
        // Search and filter functionality
        $search = $this->input->get('search');
        $category = $this->input->get('category');
        $status = $this->input->get('status');
        
        $data['books'] = $this->Book_model->get_books_advanced($search, $category, $status);
        $data['categories'] = $this->Book_model->get_all_categories();
        $data['search'] = $search;
        $data['selected_category'] = $category;
        $data['selected_status'] = $status;
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/book_management', $data);
        $this->load->view('admin/footer');
    }
    
    /**
     * Library Analytics Dashboard
     */
    public function analytics() {
        $data['title'] = 'Library Analytics';
        $data['user'] = $this->session->userdata();
        
        // Get comprehensive analytics data
        $data['monthly_stats'] = $this->get_monthly_statistics();
        $data['popular_books'] = $this->Book_model->get_popular_books(10);
        $data['active_users'] = $this->User_model->get_most_active_users(10);
        $data['category_stats'] = $this->Book_model->get_category_statistics();
        $data['overdue_analysis'] = $this->Borrow_model->get_overdue_analysis();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/analytics', $data);
        $this->load->view('admin/footer');
    }
}