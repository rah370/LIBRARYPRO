<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-exchange-alt me-2"></i>Manage Borrows</h3>
    <span class="badge bg-info fs-6">
        <?php echo count($borrows); ?> total borrows
    </span>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Student</th>
                        <th>Book</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($borrows)): ?>
                        <?php foreach ($borrows as $borrow): 
                            $is_overdue = strtotime($borrow->return_date) < strtotime('now') && $borrow->status == 'borrowed';
                        ?>
                            <tr class="<?php echo $is_overdue ? 'table-danger' : ($borrow->status == 'returned' ? 'table-success' : ''); ?>">
                                <td>
                                    <strong><?php echo htmlspecialchars($borrow->first_name . ' ' . $borrow->last_name); ?></strong>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($borrow->student_id ?? ($borrow->username ?? '')); ?></small>
                                    <br><small>
                                        <?php if (!empty($borrow->user_email)): ?>
                                            <a href="mailto:<?php echo htmlspecialchars($borrow->user_email); ?>" class="text-decoration-none"><?php echo htmlspecialchars($borrow->user_email); ?></a>
                                        <?php else: ?>
                                            <span class="text-muted">No email</span>
                                        <?php endif; ?>
                                    </small>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($borrow->title); ?></strong>
                                    <br><small class="text-muted">by <?php echo htmlspecialchars($borrow->author); ?></small>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($borrow->borrow_date)); ?></td>
                                <td>
                                    <?php echo date('M d, Y', strtotime($borrow->return_date)); ?>
                                    <?php if ($is_overdue): ?>
                                        <br><small class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Overdue
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($borrow->status == 'returned'): ?>
                                        <span class="badge bg-success">Returned</span>
                                        <br><small class="text-muted">
                                            <?php echo date('M d, Y', strtotime($borrow->actual_return_date)); ?>
                                        </small>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Borrowed</span>
                                        <?php if ($is_overdue): ?>
                                            <br><span class="badge bg-danger">Overdue</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($borrow->status == 'borrowed'): ?>
                                        <a href="<?php echo base_url('admin/return_book/' . $borrow->id); ?>" 
                                           class="btn btn-sm btn-outline-success"
                                           onclick="return confirm('Mark this book as returned?')">
                                            <i class="fas fa-check"></i> Return
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">
                                            <i class="fas fa-check-circle"></i> Completed
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No borrows recorded yet.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>