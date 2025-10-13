<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-plus me-2"></i>Add New Book</h3>
    <a href="<?php echo base_url('admin/books'); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Books
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?php echo base_url('admin/add_book'); ?>">
                    <div class="mb-3">
                        <label for="title" class="form-label">Book Title *</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?php echo set_value('title'); ?>" required>
                        <?php echo form_error('title', '<small class="text-danger">', '</small>'); ?>
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Author *</label>
                        <input type="text" class="form-control" id="author" name="author" 
                               value="<?php echo set_value('author'); ?>" required>
                        <?php echo form_error('author', '<small class="text-danger">', '</small>'); ?>
                    </div>

                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN *</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" 
                               value="<?php echo set_value('isbn'); ?>" placeholder="e.g., 978-0123456789" required>
                        <?php echo form_error('isbn', '<small class="text-danger">', '</small>'); ?>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" 
                                  placeholder="Brief description of the book..."><?php echo set_value('description'); ?></textarea>
                        <?php echo form_error('description', '<small class="text-danger">', '</small>'); ?>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Add Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>