<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-chart-bar me-2"></i>Library Statistics</h3>
    <span class="text-muted">
        <i class="fas fa-clock me-1"></i>
        Updated: <?php echo date('M d, Y - H:i'); ?>
    </span>
</div>

<?php
// Ensure stats arrays exist and provide safe defaults to avoid undefined index warnings
$book_stats = isset($book_stats) && is_array($book_stats) ? $book_stats : (array) ($book_stats ?? []);
$borrow_stats = isset($borrow_stats) && is_array($borrow_stats) ? $borrow_stats : (array) ($borrow_stats ?? []);

// Local variables with defaults
$total_books = (int) ($book_stats['total_books'] ?? 0);
$available_books = (int) ($book_stats['available_books'] ?? 0);
$borrowed_books = (int) ($book_stats['borrowed_books'] ?? 0);

$total_borrows = (int) ($borrow_stats['total_borrows'] ?? 0);
$active_borrows = (int) ($borrow_stats['active_borrows'] ?? 0);
$overdue_borrows = (int) ($borrow_stats['overdue_borrows'] ?? 0);
?>

<!-- Overview Stats -->
<div class="row mb-5">
    <div class="col-md-3">
        <div class="card stat-card text-center">
            <div class="card-body">
                <i class="fas fa-book fa-3x mb-3 opacity-75"></i>
                <h2><?php echo $total_books; ?></h2>
                <p class="mb-0">Total Books</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-success text-center">
            <div class="card-body">
                <i class="fas fa-check-circle fa-3x mb-3 opacity-75"></i>
                <h2><?php echo $available_books; ?></h2>
                <p class="mb-0">Available Books</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-info text-center">
            <div class="card-body">
                <i class="fas fa-exchange-alt fa-3x mb-3 opacity-75"></i>
                <h2><?php echo $total_borrows; ?></h2>
                <p class="mb-0">Total Borrows</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-warning text-center">
            <div class="card-body">
                <i class="fas fa-exclamation-triangle fa-3x mb-3 opacity-75"></i>
                <h2><?php echo $overdue_borrows; ?></h2>
                <p class="mb-0">Overdue Books</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Book Statistics -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Book Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h3 class="text-primary"><?php echo $available_books; ?></h3>
                            <p class="text-muted mb-0">Available</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <h3 class="text-warning"><?php echo $borrowed_books; ?></h3>
                        <p class="text-muted mb-0">Borrowed</p>
                    </div>
                </div>
                
                <hr>
                
                <div class="progress mb-3" style="height: 20px;">
                    <?php 
                    $available_percent = $total_books > 0 ? ($available_books / $total_books) * 100 : 0;
                    $borrowed_percent = 100 - $available_percent;
                    ?>
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: <?php echo $available_percent; ?>%" 
                         title="Available: <?php echo round($available_percent, 1); ?>%">
                        <?php echo round($available_percent, 1); ?>%
                    </div>
                    <div class="progress-bar bg-warning" role="progressbar" 
                         style="width: <?php echo $borrowed_percent; ?>%" 
                         title="Borrowed: <?php echo round($borrowed_percent, 1); ?>%">
                        <?php echo round($borrowed_percent, 1); ?>%
                    </div>
                </div>
                
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Book availability rate: <?php echo round($available_percent, 1); ?>%
                </small>
            </div>
        </div>
    </div>

    <!-- Borrow Statistics -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Borrow Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border-end">
                            <h3 class="text-info"><?php echo $active_borrows; ?></h3>
                            <p class="text-muted mb-0">Active</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-end">
                            <h3 class="text-success"><?php echo max(0, $total_borrows - $active_borrows); ?></h3>
                            <p class="text-muted mb-0">Returned</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <h3 class="text-danger"><?php echo $overdue_borrows; ?></h3>
                        <p class="text-muted mb-0">Overdue</p>
                    </div>
                </div>
                
                <hr>
                
                <?php if ($active_borrows > 0): ?>
                    <?php $overdue_percent = ($overdue_borrows / $active_borrows) * 100; ?>
                    <div class="alert alert-<?php echo $overdue_percent > 20 ? 'danger' : ($overdue_percent > 10 ? 'warning' : 'success'); ?> mb-0">
                        <strong>Overdue Rate:</strong> <?php echo round($overdue_percent, 1); ?>%
                        <?php if ($overdue_percent > 20): ?>
                            <i class="fas fa-exclamation-triangle ms-2"></i>
                            <small>High overdue rate - consider sending reminders</small>
                        <?php elseif ($overdue_percent > 10): ?>
                            <i class="fas fa-exclamation-circle ms-2"></i>
                            <small>Moderate overdue rate</small>
                        <?php else: ?>
                            <i class="fas fa-check-circle ms-2"></i>
                            <small>Good return rate</small>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">
                        <i class="fas fa-info-circle"></i>
                        No active borrows to analyze
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Popular Books -->
<?php if (!empty($book_stats['popular_books'])): ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Most Popular Books</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Times Borrowed</th>
                                <th>Popularity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $max_borrows = !empty($book_stats['popular_books']) ? $book_stats['popular_books'][0]->borrow_count : 1;
                            foreach ($book_stats['popular_books'] as $index => $book): 
                                $popularity = ($book->borrow_count / $max_borrows) * 100;
                            ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-<?php echo $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'dark'); ?> rounded-pill">
                                            #<?php echo $index + 1; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($book->title); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($book->author); ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo $book->borrow_count; ?></span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px; width: 100px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: <?php echo $popularity; ?>%"
                                                 title="<?php echo round($popularity, 1); ?>% of max">
                                                <?php echo round($popularity); ?>%
                                            </div>
                                        </div>
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