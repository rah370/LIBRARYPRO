<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Borrow_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function borrow_book($book_id, $user_id, $days = 14) {
        // Get user info
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $user_id);
        $user = $this->db->get()->row();
        
        if (!$user) {
            return false;
        }
        
        // Clamp days to a reasonable range
        $days = (int) $days;
        if ($days < 1) $days = 1;
        if ($days > 30) $days = 30; // enforce maximum borrowing period

        $data = array(
            'book_id' => $book_id,
            'user_id' => $user_id,
            'borrow_date' => date('Y-m-d H:i:s'),
            'due_date' => date('Y-m-d H:i:s', strtotime('+' . $days . ' days')),
            'status' => 'active'
        );
        
        $this->db->insert('borrows', $data);
        
        if ($this->db->affected_rows() > 0) {
            // Decrease available copies
            $this->load->model('Book_model');
            $book = $this->Book_model->get_book_by_id($book_id);
            if ($book && $book->copies_available > 0) {
                $new_copies = $book->copies_available - 1;
                $this->Book_model->update_book_availability($book_id, $new_copies);
            }
            return true;
        }
        return false;
    }
    
    public function return_book($borrow_id) {
        $this->db->select('book_id');
        $this->db->from('borrows');
        $this->db->where('id', $borrow_id);
        $query = $this->db->get();
        $borrow = $query->row();
        
        if ($borrow) {
            // Update borrow status
            $data = array(
                'status' => 'returned',
                'return_date' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->db->where('id', $borrow_id);
            $this->db->update('borrows', $data);
            
            // Increase available copies
            $this->load->model('Book_model');
            $book = $this->Book_model->get_book_by_id($borrow->book_id);
            if ($book) {
                $new_copies = $book->copies_available + 1;
                $this->Book_model->update_book_availability($borrow->book_id, $new_copies);
            }
            return true;
        }
        return false;
    }
    
    public function get_user_borrows($user_id) {
        $this->db->select('borrows.*, books.title, books.author');
        $this->db->from('borrows');
        $this->db->join('books', 'books.id = borrows.book_id');
        $this->db->where('borrows.user_id', $user_id);
        $this->db->order_by('borrows.borrow_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_all_borrows() {
        // Select extra user fields. Do NOT alias non-existent columns (some DBs
        // may not have users.student_id). Views should fallback to username when
        // student_id is not available.
    $this->db->select('borrows.*, books.title, books.author, users.first_name, users.last_name, users.username, users.student_id as student_id, users.email as user_email');
        $this->db->from('borrows');
        $this->db->join('books', 'books.id = borrows.book_id');
        $this->db->join('users', 'users.id = borrows.user_id');
        $this->db->order_by('borrows.borrow_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_overdue_borrows() {
        $this->db->select('borrows.*, books.title, books.author, users.first_name, users.last_name, users.username, users.email as user_email');
        $this->db->from('borrows');
        $this->db->join('books', 'books.id = borrows.book_id');
        $this->db->join('users', 'users.id = borrows.user_id');
        $this->db->where('borrows.status', 'active');
        $this->db->where('borrows.due_date <', date('Y-m-d H:i:s'));
        $this->db->order_by('borrows.due_date', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_monthly_statistics() {
        $stats = array();
        
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $month_start = date('Y-m-01', strtotime("-$i months"));
            $month_end = date('Y-m-t', strtotime("-$i months"));
            
            $this->db->select('COUNT(*) as total_borrows');
            $this->db->from('borrows');
            $this->db->where('borrow_date >=', $month_start);
            $this->db->where('borrow_date <=', $month_end . ' 23:59:59');
            $query = $this->db->get();
            $result = $query->row();
            
            $stats[] = array(
                'month' => $month,
                'month_name' => date('M Y', strtotime($month)),
                'total_borrows' => $result->total_borrows
            );
        }
        
        return $stats;
    }
    
    public function count_active() {
        $this->db->select('COUNT(*) as count');
        $this->db->from('borrows');
        $this->db->where('returned_at IS NULL');
        $query = $this->db->get();
        return $query->row()->count;
    }
    
    public function count_overdue() {
        $this->db->select('COUNT(*) as count');
        $this->db->from('borrows');
        $this->db->where('status', 'active');
        $this->db->where('due_date <', date('Y-m-d H:i:s'));
        $query = $this->db->get();
        return $query->row()->count;
    }
    
    public function count_today() {
        $this->db->select('COUNT(*) as count');
        $this->db->from('borrows');
        $this->db->where('DATE(borrow_date)', date('Y-m-d'));
        $query = $this->db->get();
        return $query->row()->count;
    }
    
    public function count_returns_today() {
        $this->db->select('COUNT(*) as count');
        $this->db->from('borrows');
        $this->db->where('DATE(return_date)', date('Y-m-d'));
        $this->db->where('status', 'returned');
        $query = $this->db->get();
        return $query->row()->count;
    }
    
    public function get_borrow_statistics() {
        $stats = array();
        
        // Total borrows
        $this->db->select('COUNT(*) as total');
        $this->db->from('borrows');
        $query = $this->db->get();
        $stats['total_borrows'] = $query->row()->total ?: 0;
        
        // Active borrows
        $this->db->select('COUNT(*) as active');
        $this->db->from('borrows');
        $this->db->where('status', 'active');
        $query = $this->db->get();
        $stats['active_borrows'] = $query->row()->active ?: 0;
        
        // Returned books
        $this->db->select('COUNT(*) as returned');
        $this->db->from('borrows');
        $this->db->where('status', 'returned');
        $query = $this->db->get();
        $stats['returned_books'] = $query->row()->returned ?: 0;
        
        // Overdue books
        $this->db->select('COUNT(*) as overdue');
        $this->db->from('borrows');
        $this->db->where('status', 'active');
        $this->db->where('due_date <', date('Y-m-d H:i:s'));
        $query = $this->db->get();
        $stats['overdue_books'] = $query->row()->overdue ?: 0;
        
        // Today's borrows
        $this->db->select('COUNT(*) as today');
        $this->db->from('borrows');
        $this->db->where('DATE(borrow_date)', date('Y-m-d'));
        $query = $this->db->get();
        $stats['todays_borrows'] = $query->row()->today ?: 0;
        
        // Today's returns
        $this->db->select('COUNT(*) as today_returns');
        $this->db->from('borrows');
        $this->db->where('DATE(return_date)', date('Y-m-d'));
        $this->db->where('status', 'returned');
        $query = $this->db->get();
        $stats['todays_returns'] = $query->row()->today_returns ?: 0;
        
        // Most borrowed books (top 5)
        $this->db->select('books.title, books.author, COUNT(borrows.id) as borrow_count');
        $this->db->from('borrows');
        $this->db->join('books', 'borrows.book_id = books.id');
        $this->db->group_by('borrows.book_id');
        $this->db->order_by('borrow_count', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get();
        $stats['popular_books'] = $query->result();
        
        // Recent borrows (last 10)
        $this->db->select('borrows.*, books.title, books.author, users.first_name, users.last_name, users.username');
        $this->db->from('borrows');
        $this->db->join('books', 'borrows.book_id = books.id');
        $this->db->join('users', 'borrows.user_id = users.id');
        $this->db->order_by('borrows.borrow_date', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        $stats['recent_borrows'] = $query->result();
        
        return $stats;
    }
    
    public function get_recent_borrows($limit = 10) {
        $this->db->select('borrows.*, books.title, books.author, users.first_name, users.last_name, users.username');
        $this->db->from('borrows');
        $this->db->join('books', 'books.id = borrows.book_id');
        $this->db->join('users', 'users.id = borrows.user_id');
        $this->db->order_by('borrows.borrow_date', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_recent_returns($limit = 10) {
        $this->db->select('borrows.*, books.title, books.author, users.first_name, users.last_name, users.username');
        $this->db->from('borrows');
        $this->db->join('books', 'books.id = borrows.book_id');
        $this->db->join('users', 'users.id = borrows.user_id');
        $this->db->where('borrows.status', 'returned');
        $this->db->where('borrows.return_date IS NOT NULL');
        $this->db->order_by('borrows.return_date', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Get overdue analysis data
     */
    public function get_overdue_analysis() {
        $analysis = array();
        
        // Get overdue statistics by days
        $this->db->select('
            COUNT(*) as count,
            AVG(JULIANDAY("now") - JULIANDAY(due_date)) as avg_days_overdue,
            MIN(JULIANDAY("now") - JULIANDAY(due_date)) as min_days_overdue,
            MAX(JULIANDAY("now") - JULIANDAY(due_date)) as max_days_overdue
        ');
        $this->db->from('borrows');
        $this->db->where('status', 'active');
        $this->db->where('due_date < datetime("now")');
        
        $query = $this->db->get();
        $stats = $query->row();
        
        $analysis['total_overdue'] = $stats->count ?? 0;
        $analysis['avg_days_overdue'] = round($stats->avg_days_overdue ?? 0, 1);
        $analysis['min_days_overdue'] = round($stats->min_days_overdue ?? 0);
        $analysis['max_days_overdue'] = round($stats->max_days_overdue ?? 0);
        
        // Get overdue by user
        $this->db->select('users.first_name, users.last_name, COUNT(*) as overdue_count');
        $this->db->from('borrows');
        $this->db->join('users', 'borrows.user_id = users.id');
        $this->db->where('borrows.status', 'active');
        $this->db->where('borrows.due_date < datetime("now")');
        $this->db->group_by('users.id');
        $this->db->order_by('overdue_count', 'DESC');
        $this->db->limit(10);
        
        $query = $this->db->get();
        $analysis['top_overdue_users'] = $query->result();
        
        return $analysis;
    }
}
