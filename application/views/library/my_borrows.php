<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="fas fa-list me-2"></i>My Borrowed Books</h2>
            <p class="text-muted">Track your borrowed books and return dates</p>
            <a href="<?php echo base_url('library'); ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i>Back to Library
            </a>
        </div>
    </div>

    <?php if(empty($borrows)): ?>
        <div class="text-center py-5">
            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No books borrowed yet</h4>
            <p class="text-muted">Start exploring our library to borrow your first book!</p>
            <a href="<?php echo base_url('library'); ?>" class="btn btn-primary">
                <i class="fas fa-search me-1"></i>Browse Books
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Borrowed Books (<?php echo count($borrows); ?>)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Book</th>
                                        <th>Author</th>
                                        <th>Borrow Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($borrows as $borrow): ?>
                                        <tr>
                                            <td>
                                                <strong class="text-primary"><?php echo htmlspecialchars($borrow->title); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($borrow->author); ?></td>
                                            <td>
                                                <i class="fas fa-calendar-plus me-1 text-success"></i>
                                                <?php echo date('M d, Y', strtotime($borrow->borrow_date)); ?>
                                            </td>
                                            <td>
                                                <?php 
                                                // validate return_date
                                                $due_ts = !empty($borrow->return_date) ? @strtotime($borrow->return_date) : false;
                                                $today = time();
                                                if ($borrow->status === 'returned') {
                                                    echo '<span class="text-muted">Returned</span>';
                                                } elseif (!$due_ts) {
                                                    echo '<span class="text-muted">TBD</span>';
                                                } else {
                                                    $days_left = (int) ceil(($due_ts - $today) / (60 * 60 * 24));
                                                    if ($days_left < 0) {
                                                        echo '<span class="text-danger fw-bold">Overdue by ' . abs($days_left) . ' day' . (abs($days_left) == 1 ? '' : 's') . '</span>';
                                                    } elseif ($days_left <= 3) {
                                                        echo '<span class="text-warning fw-bold">Due in ' . $days_left . ' day' . ($days_left == 1 ? '' : 's') . '</span>';
                                                    } else {
                                                        echo '<span class="text-success">Due in ' . $days_left . ' day' . ($days_left == 1 ? '' : 's') . '</span>';
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if($borrow->status === 'borrowed'): ?>
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-hand-holding me-1"></i>Borrowed
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Returned
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($borrow->status === 'borrowed'): ?>
                                                    <a href="<?php echo base_url('library/return_book/' . $borrow->id); ?>" 
                                                       class="btn btn-sm btn-success"
                                                       onclick="return confirm('Are you sure you want to return this book?')">
                                                        <i class="fas fa-undo me-1"></i>Return
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">Returned on <?php echo date('M d, Y', strtotime($borrow->actual_return_date)); ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card border-info">
                    <div class="card-body">
                        <h6 class="card-title text-info">
                            <i class="fas fa-info-circle me-2"></i>Borrowing Summary
                        </h6>
                        <p class="card-text">
                            You have borrowed <strong><?php echo count(array_filter($borrows, function($b) { return $b->status === 'borrowed'; })); ?></strong> book(s) currently.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-warning">
                    <div class="card-body">
                        <h6 class="card-title text-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Important Reminders
                        </h6>
                        <ul class="card-text mb-0">
                            <li>Return books on time to avoid late fees</li>
                            <li>Maximum borrowing period is 14 days</li>
                            <li>Contact librarian for extensions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
