<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">
                            <i class="fas fa-sun me-2"></i>
                            Good <?php echo date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening'); ?>, 
                            <?php echo htmlspecialchars($user['first_name'] ?? 'Administrator'); ?>!
                        </h4>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <?php echo date('l, F j, Y'); ?> | 
                            <i class="fas fa-clock me-2"></i>
                            <?php echo date('g:i A'); ?>
                        </p>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark me-2">
                                <i class="fas fa-shield-alt me-1"></i>Admin Access
                            </span>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-database me-1"></i>System Active
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="fas fa-user-shield fa-4x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard: left Quick Actions column + right content column -->
<div class="row">
    <div class="col-12 col-md-3 mb-4">
        <div class="card position-sticky" style="top:90px;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="qa-stack">
                    <a href="<?php echo base_url('admin/add_book'); ?>" class="btn btn-primary qa-btn d-flex align-items-center">
                        <i class="fas fa-plus-circle me-3"></i>
                        <span>Add New Book</span>
                    </a>

                    <a href="<?php echo base_url('admin/add_student'); ?>" class="btn btn-success qa-btn d-flex align-items-center">
                        <i class="fas fa-user-plus me-3"></i>
                        <span>Add New Student</span>
                    </a>

                    <a href="<?php echo base_url('admin/borrows'); ?>" class="btn btn-info qa-btn d-flex align-items-center text-white">
                        <i class="fas fa-list me-3"></i>
                        <span>View All Borrows</span>
                    </a>

                    <a href="<?php echo base_url('admin/statistics'); ?>" class="btn btn-warning qa-btn d-flex align-items-center">
                        <i class="fas fa-chart-bar me-3"></i>
                        <span>View Statistics</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-9 mb-4">
        <!-- Dashboard Stats -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card stat-card-info">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stat-label">Total Books</div>
                                <div class="stat-number"><?php echo number_format($book_stats['total_books']); ?></div>
                                <div class="text-xs mt-1 opacity-75">
                                    <i class="fas fa-arrow-up me-1"></i>In Collection
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card stat-card-success">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stat-label">Available Books</div>
                                <div class="stat-number"><?php echo number_format($book_stats['available_books']); ?></div>
                                <div class="text-xs mt-1 opacity-75">
                                    <i class="fas fa-check-circle me-1"></i>Ready to Borrow
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book-open fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card stat-card-warning">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stat-label">Active Borrows</div>
                                <div class="stat-number"><?php echo number_format($borrow_stats['active_borrows']); ?></div>
                                <div class="text-xs mt-1 opacity-75">
                                    <i class="fas fa-exchange-alt me-1"></i>Currently Borrowed
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hand-holding fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card stat-card-danger">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stat-label">Overdue Books</div>
                                <div class="stat-number"><?php echo number_format(isset($borrow_stats['overdue_books']) ? $borrow_stats['overdue_books'] : 0); ?></div>
                                <div class="text-xs mt-1 opacity-75">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Needs Attention
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Popular Books -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Most Popular Books</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($book_stats['popular_books'])): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($book_stats['popular_books'] as $book): ?>
                                    <div class="list-group-item border-0 px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1"><?php echo htmlspecialchars($book->title); ?></h6>
                                                <small class="text-muted">by <?php echo htmlspecialchars($book->author); ?></small>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">
                                                <?php echo $book->borrow_count; ?> borrows
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center py-3">No borrowing data available yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overdue Books -->
<?php if (!empty($overdue_borrows)): ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Overdue Books Alert</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Book</th>
                                <th>Due Date</th>
                                <th>Days Overdue</th>
                                <th>Contact</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($overdue_borrows as $borrow): 
                                $days_overdue = (strtotime('now') - strtotime($borrow->due_date)) / (60 * 60 * 24);
                                $days_overdue = floor($days_overdue);
                            ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($borrow->first_name . ' ' . $borrow->last_name); ?></strong>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($borrow->username ?? 'N/A'); ?></small>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($borrow->title); ?></strong>
                                        <br><small class="text-muted">by <?php echo htmlspecialchars($borrow->author); ?></small>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($borrow->due_date)); ?></td>
                                    <td><span class="badge bg-danger"><?php echo $days_overdue; ?> days</span></td>
                                    <td><a href="mailto:<?php echo htmlspecialchars($borrow->user_email ?? ''); ?>" class="text-decoration-none"><?php echo htmlspecialchars($borrow->user_email ?? 'N/A'); ?></a></td>
                                    <td>
                                        <a href="<?php echo base_url('admin/return_book/' . $borrow->id); ?>" 
                                           class="btn btn-sm btn-outline-primary"
                                           onclick="return confirm('Mark this book as returned?')">
                                            <i class="fas fa-check"></i> Return
                                        </a>
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
<?php endif; ?>