<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-list me-2"></i>My Borrowed Books</h3>
    <a href="<?php echo base_url('student'); ?>" class="btn btn-primary">
        <i class="fas fa-search me-2"></i>Browse More Books
    </a>
</div>

<div class="row">
    <?php if (!empty($borrows)): ?>
        <?php foreach ($borrows as $borrow): 
            // normalize and validate return_date timestamp
            $due_ts = !empty($borrow->return_date) ? @strtotime($borrow->return_date) : false;
            $now = time();
            if ($due_ts && $borrow->status == 'borrowed') {
                $is_overdue = ($due_ts < $now);
                $days_until_due = (int) ceil(($due_ts - $now) / (60 * 60 * 24));
            } else {
                $is_overdue = false;
                $days_until_due = null; // unknown / not applicable
            }
        ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 <?php echo $is_overdue ? 'border-danger' : ($borrow->status == 'returned' ? 'border-success' : 'border-primary'); ?>">
                    <div class="card-header <?php echo $is_overdue ? 'bg-danger text-white' : ($borrow->status == 'returned' ? 'bg-success text-white' : 'bg-primary text-white'); ?>">
                        <h6 class="mb-0">
                            <i class="fas fa-book me-2"></i>
                            <?php if ($borrow->status == 'returned'): ?>
                                Returned
                            <?php elseif ($is_overdue): ?>
                                Overdue
                            <?php else: ?>
                                Borrowed
                            <?php endif; ?>
                        </h6>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($borrow->title); ?></h5>
                        <p class="card-text">
                            <strong>Author:</strong> <?php echo htmlspecialchars($borrow->author); ?>
                        </p>
                        
                        <div class="row text-center">
                            <div class="col-6">
                                <small class="text-muted">Borrowed</small>
                                <div class="fw-bold"><?php echo date('M d, Y', strtotime($borrow->borrow_date)); ?></div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted"><?php echo $borrow->status == 'returned' ? 'Returned' : 'Due Date'; ?></small>
                                <div class="fw-bold">
                                    <?php if ($borrow->status == 'returned'): ?>
                                        <?php echo date('M d, Y', strtotime($borrow->actual_return_date)); ?>
                                    <?php else: ?>
                                        <?php if ($due_ts): ?>
                                            <?php echo date('M d, Y', $due_ts); ?>
                                        <?php else: ?>
                                            <em>TBD</em>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <?php if ($borrow->status == 'borrowed' && $days_until_due !== null): ?>
                                    <small class="text-muted d-block"><?php echo $days_until_due >= 0 ? 'Due in ' . $days_until_due . ' day' . ($days_until_due == 1 ? '' : 's') : 'Overdue by ' . abs($days_until_due) . ' day' . (abs($days_until_due) == 1 ? '' : 's'); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($borrow->status == 'borrowed'): ?>
                            <hr>
                            <div class="text-center">
                                <?php if ($is_overdue): ?>
                                    <div class="alert alert-danger py-2 mb-0">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        <strong>Overdue!</strong> Please return immediately.
                                    </div>
                                <?php elseif ($days_until_due <= 3): ?>
                                    <div class="alert alert-warning py-2 mb-0">
                                        <i class="fas fa-clock me-1"></i>
                                        <strong>Due in <?php echo $days_until_due; ?> day(s)</strong>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info py-2 mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Due in <?php echo $days_until_due; ?> days
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No borrowed books</h4>
                <p class="text-muted">You haven't borrowed any books yet.</p>
                <a href="<?php echo base_url('student'); ?>" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Browse Available Books
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>