<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-book me-2"></i>Manage Books</h3>
    <a href="<?php echo base_url('admin/add_book'); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Book
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Status</th>
                        <th>Times Borrowed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($books)): ?>
                        <?php foreach ($books as $book): ?>
                            <?php
                                $available = null;
                                if (isset($book->available)) {
                                    $available = $book->available;
                                } else {
                                    $available = (isset($book->status) && $book->status === 'available' && isset($book->copies_available) && $book->copies_available > 0) ? 1 : 0;
                                }
                            ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($book->title); ?></strong>
                                    <?php if (!empty($book->description)): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars(substr($book->description, 0, 60)); ?>...</small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($book->author); ?></td>
                                <td><code><?php echo htmlspecialchars($book->isbn); ?></code></td>
                                <td>
                                    <?php if ($available): ?>
                                        <span class="badge bg-success">Available</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Borrowed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo $book->borrow_count; ?></span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo base_url('admin/edit_book/' . $book->id); ?>" 
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo base_url('admin/delete_book/' . $book->id); ?>" 
                                           class="btn btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this book?')" 
                                           title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No books found.</p>
                                <a href="<?php echo base_url('admin/add_book'); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add First Book
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>