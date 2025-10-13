<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-bell"></i> Notification Center</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h5><i class="fas fa-exclamation-triangle"></i> Overdue Books</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($overdue_books)): ?>
                                        <p class="text-muted">No overdue books at this time.</p>
                                    <?php else: ?>
                                        <p class="mb-3">
                                            <strong><?php echo count($overdue_books); ?></strong> book(s) are overdue.
                                        </p>
                                        <div class="table-responsive mb-3">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Student</th>
                                                        <th>Book</th>
                                                        <th>Days Overdue</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach (array_slice($overdue_books, 0, 5) as $borrow): ?>
                                                    <?php
                                                        $days_overdue = floor((time() - strtotime($borrow->due_date)) / (60 * 60 * 24));
                                                    ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($borrow->user_name); ?></td>
                                                        <td><?php echo htmlspecialchars($borrow->book_title); ?></td>
                                                        <td>
                                                            <span class="badge bg-danger"><?php echo $days_overdue; ?> days</span>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo site_url('notifications/send_individual_reminder/' . $borrow->id); ?>" 
                                                               class="btn btn-sm btn-outline-danger"
                                                               onclick="return confirm('Send overdue reminder to this student?')">
                                                                <i class="fas fa-envelope"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-grid">
                                            <a href="<?php echo site_url('notifications/send_overdue_reminders'); ?>" 
                                               class="btn btn-danger"
                                               onclick="return confirm('Send overdue reminders to all <?php echo count($overdue_books); ?> students?')">
                                                <i class="fas fa-paper-plane"></i> Send All Overdue Reminders
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h5><i class="fas fa-clock"></i> Due Soon</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($due_soon_books)): ?>
                                        <p class="text-muted">No books due in the next 3 days.</p>
                                    <?php else: ?>
                                        <p class="mb-3">
                                            <strong><?php echo count($due_soon_books); ?></strong> book(s) due within 3 days.
                                        </p>
                                        <div class="table-responsive mb-3">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Student</th>
                                                        <th>Book</th>
                                                        <th>Due In</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach (array_slice($due_soon_books, 0, 5) as $borrow): ?>
                                                    <?php
                                                        $days_until_due = floor((strtotime($borrow->due_date) - time()) / (60 * 60 * 24));
                                                    ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($borrow->user_name); ?></td>
                                                        <td><?php echo htmlspecialchars($borrow->book_title); ?></td>
                                                        <td>
                                                            <span class="badge bg-warning text-dark"><?php echo $days_until_due; ?> days</span>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo site_url('notifications/send_individual_reminder/' . $borrow->id); ?>" 
                                                               class="btn btn-sm btn-outline-warning"
                                                               onclick="return confirm('Send due soon reminder to this student?')">
                                                                <i class="fas fa-envelope"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-grid">
                                            <a href="<?php echo site_url('notifications/send_due_soon_reminders'); ?>" 
                                               class="btn btn-warning"
                                               onclick="return confirm('Send due soon reminders to all <?php echo count($due_soon_books); ?> students?')">
                                                <i class="fas fa-paper-plane"></i> Send All Due Soon Reminders
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notification Settings -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-cogs"></i> Notification Settings</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Automated Reminders</h5>
                            <div class="alert alert-info">
                                <p><strong>Note:</strong> This demo system uses basic PHP mail() function.</p>
                                <p>In production, configure proper SMTP settings in CodeIgniter's email configuration.</p>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="auto_overdue" checked disabled>
                                <label class="form-check-label" for="auto_overdue">
                                    Send overdue reminders automatically (daily)
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="auto_due_soon" checked disabled>
                                <label class="form-check-label" for="auto_due_soon">
                                    Send "due soon" reminders (3 days before)
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Email Configuration</h5>
                            <div class="mb-3">
                                <label for="from_email" class="form-label">From Email:</label>
                                <input type="email" class="form-control" id="from_email" value="noreply@library.com" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label for="smtp_host" class="form-label">SMTP Host:</label>
                                <input type="text" class="form-control" id="smtp_host" value="localhost" readonly>
                                <small class="form-text text-muted">Configure in application/config/email.php</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Notifications Log -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-history"></i> Recent Notifications</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-secondary">
                        <i class="fas fa-info-circle"></i> 
                        Notification history will be displayed here once the notifications table is created and populated.
                    </div>
                    
                    <!-- This would show recent notification history in a real system -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Student</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No notifications sent yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh notification counts every 5 minutes
    setInterval(function() {
        // In a real system, this would make an AJAX call to refresh counts
        console.log('Auto-refreshing notification counts...');
    }, 300000); // 5 minutes
    
    // Add confirmation dialogs for bulk actions
    const bulkButtons = document.querySelectorAll('[onclick*="Send"]');
    bulkButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const count = this.textContent.match(/\d+/);
            if (count && parseInt(count[0]) > 5) {
                if (!confirm(`This will send ${count[0]} emails. Are you sure?`)) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    });
});
</script>