<!-- Reports Section -->
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Library Reports
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Report Filters -->
                    <form method="get" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="report_type" class="form-label">Report Type</label>
                            <select class="form-select" id="report_type" name="report_type">
                                <option value="borrows" <?php echo ($report_type == 'borrows') ? 'selected' : ''; ?>>Borrow History</option>
                                <option value="overdue" <?php echo ($report_type == 'overdue') ? 'selected' : ''; ?>>Overdue Books</option>
                                <option value="popular_books" <?php echo ($report_type == 'popular_books') ? 'selected' : ''; ?>>Popular Books</option>
                                <option value="users" <?php echo ($report_type == 'users') ? 'selected' : ''; ?>>User Activity</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Generate Report
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Export Options -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="btn-group">
                                <a href="<?php echo base_url('admin/export_data?type=books'); ?>" class="btn btn-success">
                                    <i class="fas fa-download me-2"></i>Export Books
                                </a>
                                <a href="<?php echo base_url('admin/export_data?type=users'); ?>" class="btn btn-info">
                                    <i class="fas fa-download me-2"></i>Export Users
                                </a>
                                <a href="<?php echo base_url('admin/export_data?type=borrows'); ?>" class="btn btn-warning">
                                    <i class="fas fa-download me-2"></i>Export Borrows
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Report Results -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php elseif (!empty($report_data)): ?>
                        <?php if ($report_type == 'borrows'): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Book Title</th>
                                            <th>Author</th>
                                            <th>Borrower</th>
                                            <th>Borrow Date</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($report_data as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item->title); ?></td>
                                            <td><?php echo htmlspecialchars($item->author); ?></td>
                                            <td><?php echo htmlspecialchars($item->first_name . ' ' . $item->last_name); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($item->borrow_date)); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($item->due_date)); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo ($item->status == 'active') ? 'warning' : 'success'; ?>">
                                                    <?php echo ucfirst($item->status); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                        <?php elseif ($report_type == 'overdue'): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Book Title</th>
                                            <th>Author</th>
                                            <th>Borrower</th>
                                            <th>Due Date</th>
                                            <th>Days Overdue</th>
                                            <th>Contact</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($report_data as $item): ?>
                                        <?php $days_overdue = floor((time() - strtotime($item->due_date)) / (60 * 60 * 24)); ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item->title); ?></td>
                                            <td><?php echo htmlspecialchars($item->author); ?></td>
                                            <td><?php echo htmlspecialchars($item->first_name . ' ' . $item->last_name); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($item->due_date)); ?></td>
                                            <td><span class="badge bg-danger"><?php echo $days_overdue; ?> days</span></td>
                                            <td><?php echo htmlspecialchars($item->email); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                        <?php elseif ($report_type == 'popular_books'): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Rank</th>
                                            <th>Book Title</th>
                                            <th>Author</th>
                                            <th>ISBN</th>
                                            <th>Times Borrowed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $rank = 1; foreach ($report_data as $item): ?>
                                        <tr>
                                            <td><?php echo $rank++; ?></td>
                                            <td><?php echo htmlspecialchars($item->title); ?></td>
                                            <td><?php echo htmlspecialchars($item->author); ?></td>
                                            <td><?php echo htmlspecialchars($item->isbn); ?></td>
                                            <td><span class="badge bg-primary"><?php echo $item->borrow_count; ?></span></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                        <?php elseif ($report_type == 'users'): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Total Borrows</th>
                                            <th>Status</th>
                                            <th>Member Since</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($report_data as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item->first_name . ' ' . $item->last_name); ?></td>
                                            <td><?php echo htmlspecialchars($item->username); ?></td>
                                            <td><?php echo htmlspecialchars($item->email); ?></td>
                                            <td><span class="badge bg-info"><?php echo $item->total_borrows; ?></span></td>
                                            <td>
                                                <span class="badge bg-<?php echo ($item->status == 'active') ? 'success' : 'secondary'; ?>">
                                                    <?php echo ucfirst($item->status); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($item->created_at)); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No data available for the selected criteria. Please adjust your filters and try again.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>