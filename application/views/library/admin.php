<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="fas fa-cog me-2"></i>Admin Panel</h2>
            <p class="text-muted">Monitor all borrowing activities and manage the library</p>
            <a href="<?php echo base_url('library'); ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i>Back to Library
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-book-open fa-2x mb-2"></i>
                    <h5>Total Books</h5>
                    <h3><?php echo count($borrows); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-hand-holding fa-2x mb-2"></i>
                    <h5>Currently Borrowed</h5>
                    <h3><?php echo count(array_filter($borrows, function($b) { return $b->status === 'borrowed'; })); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-undo fa-2x mb-2"></i>
                    <h5>Returned</h5>
                    <h3><?php echo count(array_filter($borrows, function($b) { return $b->status === 'returned'; })); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                    <h5>Overdue</h5>
                    <h3><?php 
                        $overdue = 0;
                        foreach($borrows as $borrow) {
                            if ($borrow->status === 'borrowed') {
                                $due_date = strtotime($borrow->return_date);
                                $today = time();
                                if ($due_date < $today) {
                                    $overdue++;
                                }
                            }
                        }
                        echo $overdue;
                    ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>All Borrowing Activities
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Book</th>
                                    <th>Author</th>
                                    <th>Borrower</th>
                                    <th>Email</th>
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
                                        <td><?php echo htmlspecialchars($borrow->user_name); ?></td>
                                        <td>
                                            <a href="mailto:<?php echo htmlspecialchars($borrow->user_email); ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($borrow->user_email); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar-plus me-1 text-success"></i>
                                            <?php echo date('M d, Y', strtotime($borrow->borrow_date)); ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $due_date = strtotime($borrow->return_date);
                                            $today = time();
                                            $days_left = ceil(($due_date - $today) / (60 * 60 * 24));
                                            
                                            if ($borrow->status === 'returned') {
                                                echo '<span class="text-muted">Returned</span>';
                                            } elseif ($days_left < 0) {
                                                echo '<span class="text-danger fw-bold">Overdue by ' . abs($days_left) . ' days</span>';
                                            } elseif ($days_left <= 3) {
                                                echo '<span class="text-warning fw-bold">Due in ' . $days_left . ' days</span>';
                                            } else {
                                                echo '<span class="text-success">Due in ' . $days_left . ' days</span>';
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
                                                   onclick="return confirm('Mark this book as returned?')">
                                                    <i class="fas fa-undo me-1"></i>Mark Returned
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
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="card-title text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Quick Statistics
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Total Transactions:</strong> <?php echo count($borrows); ?></li>
                        <li><strong>Active Borrows:</strong> <?php echo count(array_filter($borrows, function($b) { return $b->status === 'borrowed'; })); ?></li>
                        <li><strong>Completed Returns:</strong> <?php echo count(array_filter($borrows, function($b) { return $b->status === 'returned'; })); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="card-title text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Overdue Alerts
                    </h6>
                    <?php 
                    $overdue_books = array_filter($borrows, function($b) {
                        if ($b->status === 'borrowed') {
                            $due_date = strtotime($b->return_date);
                            $today = time();
                            return $due_date < $today;
                        }
                        return false;
                    });
                    ?>
                    <?php if(empty($overdue_books)): ?>
                        <p class="text-success mb-0">No overdue books at the moment!</p>
                    <?php else: ?>
                        <ul class="list-unstyled mb-0">
                            <?php foreach(array_slice($overdue_books, 0, 3) as $overdue): ?>
                                <li class="text-danger">
                                    <strong><?php echo htmlspecialchars($overdue->title); ?></strong> - 
                                    <?php echo htmlspecialchars($overdue->user_name); ?>
                                </li>
                            <?php endforeach; ?>
                            <?php if(count($overdue_books) > 3): ?>
                                <li class="text-muted">... and <?php echo count($overdue_books) - 3; ?> more</li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
