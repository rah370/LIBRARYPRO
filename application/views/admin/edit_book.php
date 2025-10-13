<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-book-open text-primary"></i> Edit Book</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/books'); ?>">Books</a></li>
                    <li class="breadcrumb-item active">Edit Book</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo base_url('admin/books'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Books
            </a>
        </div>
    </div>

    <!-- Error/Success Messages -->
    <?php if(isset($success) && !empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($error) && !empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(validation_errors()): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> 
            <strong>Please correct the following errors:</strong>
            <?php echo validation_errors('<ul><li>', '</li></ul>'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Edit Book Form -->
    <?php if(isset($book) && $book): ?>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Book Information</h5>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('admin/edit_book/' . $book->id, ['class' => 'needs-validation', 'novalidate' => '']); ?>
                            
                            <!-- Book Basic Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-info-circle"></i> Basic Information
                                    </h6>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="title" class="form-label">
                                        Book Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="title" 
                                           name="title" 
                                           value="<?php echo set_value('title', $book->title); ?>" 
                                           required>
                                    <div class="invalid-feedback">Please provide a book title.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="author" class="form-label">
                                        Author <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="author" 
                                           name="author" 
                                           value="<?php echo set_value('author', $book->author); ?>" 
                                           required>
                                    <div class="invalid-feedback">Please provide the author name.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="isbn" class="form-label">
                                        ISBN <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="isbn" 
                                           name="isbn" 
                                           value="<?php echo set_value('isbn', $book->isbn); ?>" 
                                           required>
                                    <div class="invalid-feedback">Please provide a valid ISBN.</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" 
                                              id="description" 
                                              name="description" 
                                              rows="3"><?php echo set_value('description', $book->description); ?></textarea>
                                </div>
                            </div>

                            <!-- Publication Details -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-calendar"></i> Publication Details
                                    </h6>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="publisher" class="form-label">Publisher</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="publisher" 
                                           name="publisher" 
                                           value="<?php echo set_value('publisher', $book->publisher); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="publication_year" class="form-label">Publication Year</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="publication_year" 
                                           name="publication_year" 
                                           min="1800" 
                                           max="<?php echo date('Y'); ?>"
                                           value="<?php echo set_value('publication_year', $book->publication_year); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="">Select Category</option>
                                        <option value="Fiction" <?php echo set_select('category', 'Fiction', $book->category == 'Fiction'); ?>>Fiction</option>
                                        <option value="Non-Fiction" <?php echo set_select('category', 'Non-Fiction', $book->category == 'Non-Fiction'); ?>>Non-Fiction</option>
                                        <option value="Science" <?php echo set_select('category', 'Science', $book->category == 'Science'); ?>>Science</option>
                                        <option value="Technology" <?php echo set_select('category', 'Technology', $book->category == 'Technology'); ?>>Technology</option>
                                        <option value="History" <?php echo set_select('category', 'History', $book->category == 'History'); ?>>History</option>
                                        <option value="Biography" <?php echo set_select('category', 'Biography', $book->category == 'Biography'); ?>>Biography</option>
                                        <option value="Reference" <?php echo set_select('category', 'Reference', $book->category == 'Reference'); ?>>Reference</option>
                                        <option value="Children" <?php echo set_select('category', 'Children', $book->category == 'Children'); ?>>Children</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pages" class="form-label">Number of Pages</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="pages" 
                                           name="pages" 
                                           min="1"
                                           value="<?php echo set_value('pages', $book->pages); ?>">
                                </div>
                            </div>

                            <!-- Inventory Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-boxes"></i> Inventory Information
                                    </h6>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="copies_total" class="form-label">
                                        Total Copies <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="copies_total" 
                                           name="copies_total" 
                                           min="1"
                                           value="<?php echo set_value('copies_total', $book->copies_total); ?>" 
                                           required>
                                    <div class="invalid-feedback">Please specify total copies.</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="copies_available" class="form-label">
                                        Available Copies <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="copies_available" 
                                           name="copies_available" 
                                           min="0"
                                           value="<?php echo set_value('copies_available', $book->copies_available); ?>" 
                                           required>
                                    <div class="invalid-feedback">Please specify available copies.</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="location" class="form-label">Location/Shelf</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="location" 
                                           name="location" 
                                           value="<?php echo set_value('location', $book->location); ?>" 
                                           placeholder="e.g., A-001, Fiction-02">
                                </div>
                            </div>

                            <!-- Book Status -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-toggle-on"></i> Status
                                    </h6>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="status" class="form-label">Book Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="available" <?php echo set_select('status', 'available', $book->status == 'available'); ?>>Available</option>
                                        <option value="unavailable" <?php echo set_select('status', 'unavailable', $book->status == 'unavailable'); ?>>Unavailable</option>
                                        <option value="maintenance" <?php echo set_select('status', 'maintenance', $book->status == 'maintenance'); ?>>Under Maintenance</option>
                                        <option value="retired" <?php echo set_select('status', 'retired', $book->status == 'retired'); ?>>Retired</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Cover Upload -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-image"></i> Book Cover
                                    </h6>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <?php if (!empty($book->cover)): ?>
                                        <div class="mb-2">
                                            <img src="<?php echo base_url($book->cover); ?>" alt="Cover" style="height:120px; object-fit:cover; border-radius:6px;" />
                                        </div>
                                    <?php endif; ?>
                                    <label for="cover" class="form-label">Upload Cover Image</label>
                                    <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
                                    <small class="text-muted">Optional. Max 2MB. Files will be stored in frontend/assets/books/</small>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Book
                                        </button>
                                        <a href="<?php echo base_url('admin/books'); ?>" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                        <button type="button" class="btn btn-outline-danger ms-auto" onclick="confirmDelete()">
                                            <i class="fas fa-trash"></i> Delete Book
                                        </button>
                                    </div>
                                </div>
                            </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> Book not found or you don't have permission to edit it.
        </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this book?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i> This action cannot be undone. The book will be permanently removed from the library.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="<?php echo base_url('admin/delete_book/' . (isset($book) ? $book->id : '')); ?>" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Book
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.form-label {
    font-weight: 600;
    color: #495057;
}

.card {
    border: none;
    border-radius: 10px;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.text-danger {
    font-weight: 600;
}

.border-bottom {
    border-bottom: 2px solid #e9ecef !important;
}
</style>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        const forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Ensure copies_available doesn't exceed copies_total
document.getElementById('copies_total').addEventListener('input', function() {
    const total = parseInt(this.value);
    const availableField = document.getElementById('copies_available');
    const available = parseInt(availableField.value);
    
    if (available > total) {
        availableField.value = total;
    }
    availableField.max = total;
});

document.getElementById('copies_available').addEventListener('input', function() {
    const available = parseInt(this.value);
    const total = parseInt(document.getElementById('copies_total').value);
    
    if (available > total) {
        this.value = total;
    }
});

// Delete confirmation
function confirmDelete() {
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Auto-hide alerts
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert.classList.contains('alert-dismissible')) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    });
}, 5000);
</script>