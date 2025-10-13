<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Ensure book objects include an `available` boolean-like property.
     * Accepts an array of objects or a single object and returns the same
     * structure with the `available` property set (1 or 0).
     */
    public function add_available_property($items) {
        if (is_array($items)) {
            foreach ($items as $it) {
                if (is_object($it)) {
                    $it->available = (isset($it->status) && $it->status === 'available' && isset($it->copies_available) && $it->copies_available > 0) ? 1 : 0;
                    if (!isset($it->cover)) $it->cover = null;
                }
            }
        } elseif (is_object($items) && $items) {
            $items->available = (isset($items->status) && $items->status === 'available' && isset($items->copies_available) && $items->copies_available > 0) ? 1 : 0;
            if (!isset($items->cover)) $items->cover = null;
        }

        return $items;
    }
    
    // Student functions
    public function get_all_books() {
        $this->db->select('*');
        $this->db->from('books');
        $this->db->where('status', 'available');
        $this->db->where('copies_available >', 0);
        $query = $this->db->get();
        $result = $query->result();
        return $this->add_available_property($result);
    }
    
    public function get_book_by_id($id) {
        $this->db->select('*');
        $this->db->from('books');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $row = $query->row();
        return $this->add_available_property($row);
    }
    
    public function search_books($keyword) {
        $this->db->select('*');
        $this->db->from('books');
        $this->db->like('title', $keyword);
        $this->db->or_like('author', $keyword);
        $this->db->or_like('isbn', $keyword);
        $this->db->where('status', 'available');
        $this->db->where('copies_available >', 0);
        $query = $this->db->get();
        $result = $query->result();
        return $this->add_available_property($result);
    }
    
    public function update_book_availability($book_id, $copies_available) {
        $status = ($copies_available > 0) ? 'available' : 'unavailable';
        $data = array(
            'copies_available' => $copies_available,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $book_id);
        return $this->db->update('books', $data);
    }
    
    // Admin functions
    public function get_all_books_admin() {
        $this->db->select('books.*, COUNT(borrows.id) as borrow_count');
        $this->db->from('books');
        $this->db->join('borrows', 'books.id = borrows.book_id', 'left');
        $this->db->group_by('books.id');
        $this->db->order_by('books.created_at', 'DESC');
        $query = $this->db->get();
        $result = $query->result();
        return $this->add_available_property($result);
    }
    
    public function add_book($data) {
        return $this->db->insert('books', $data);
    }
    
    public function update_book($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('books', $data);
    }
    
    public function delete_book($id) {
        $this->db->where('id', $id);
        return $this->db->delete('books');
    }
    
    public function isbn_exists($isbn, $exclude_id = null) {
        $this->db->select('id');
        $this->db->from('books');
        $this->db->where('isbn', $isbn);
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $query = $this->db->get();
        return $query->num_rows() > 0;
    }
    
    public function get_book_statistics() {
        $stats = array();
        
        // Total books
        $this->db->select('COUNT(*) as total');
        $this->db->from('books');
        $query = $this->db->get();
        $stats['total_books'] = $query->row()->total;
        
        // Available books
        $this->db->select('SUM(copies_available) as available');
        $this->db->from('books');
        $this->db->where('status', 'available');
        $query = $this->db->get();
        $stats['available_books'] = $query->row()->available ?: 0;
        
        // Borrowed books (active borrows)
        $this->db->select('COUNT(*) as borrowed');
        $this->db->from('borrows');
        $this->db->where('status', 'active');
        $query = $this->db->get();
        $stats['borrowed_books'] = $query->row()->borrowed ?: 0;
        
        // Most popular books (top 5)
        $this->db->select('books.title, books.author, COUNT(borrows.id) as borrow_count');
        $this->db->from('books');
        $this->db->join('borrows', 'books.id = borrows.book_id', 'inner');
        $this->db->group_by('books.id');
        $this->db->order_by('borrow_count', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get();
        $stats['popular_books'] = $query->result();
        
        return $stats;
    }
    
    public function count_all() {
        $this->db->select('COUNT(*) as count');
        $this->db->from('books');
        $query = $this->db->get();
        return $query->row()->count;
    }
    
    public function count_available() {
        $this->db->select('SUM(copies_available) as count');
        $this->db->from('books');
        $this->db->where('status', 'available');
        $query = $this->db->get();
        return $query->row()->count ?: 0;
    }
    
    public function count_borrowed() {
        $this->db->select('COUNT(*) as count');
        $this->db->from('borrows');
        $this->db->where('status', 'active');
        $query = $this->db->get();
        return $query->row()->count;
    }
    
    /**
     * Advanced book search with filters
     */
    public function get_books_advanced($search = null, $category = null, $status = null, $limit = null, $offset = null) {
        $this->db->select('*');
        $this->db->from('books');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('title', $search);
            $this->db->or_like('author', $search);
            $this->db->or_like('isbn', $search);
            $this->db->or_like('description', $search);
            $this->db->group_end();
        }
        
        if ($category) {
            $this->db->where('category', $category);
        }
        
        if ($status) {
            $this->db->where('status', $status);
        }
        
        $this->db->order_by('title', 'ASC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        $result = $query->result();
        return $this->add_available_property($result);
    }
    
    /**
     * Get all categories
     */
    public function get_all_categories() {
        $this->db->select('DISTINCT(category) as category');
        $this->db->from('books');
        $this->db->where('category IS NOT NULL');
        $this->db->order_by('category', 'ASC');
        $query = $this->db->get();
        return array_column($query->result_array(), 'category');
    }
    
    /**
     * Get popular books based on borrow count
     */
    public function get_popular_books($limit = 10) {
        $this->db->select('b.*, COUNT(br.id) as borrow_count');
        $this->db->from('books b');
        $this->db->join('borrows br', 'b.id = br.book_id', 'left');
        $this->db->group_by('b.id');
        $this->db->order_by('borrow_count', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Get category statistics
     */
    public function get_category_statistics() {
        $this->db->select('category, COUNT(*) as book_count, SUM(copies_total) as total_copies, SUM(copies_available) as available_copies');
        $this->db->from('books');
        $this->db->where('category IS NOT NULL');
        $this->db->group_by('category');
        $this->db->order_by('book_count', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Create new book
     */
    public function create_book($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('books', $data);
    }
    

}
