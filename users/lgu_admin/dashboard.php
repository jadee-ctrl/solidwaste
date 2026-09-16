<?php
$pageTitle  = 'Dashboard';
$activePage = 'dashboard';
include 'includes/auth.php';

function countRows($conn, $table) {
    $r = $conn->query("SELECT COUNT(*) AS c FROM `$table`");
    return $r ? (int) $r->fetch_assoc()['c'] : 0;
}

$stats = [
    'barangayadmins' => countRows($conn, 'barangay_admin'),
    'collectors'      => countRows($conn, 'garbage_collector'),
    'trucks'          => countRows($conn, 'truck'),
    'routes'          => countRows($conn, 'route'),
    'waste'           => countRows($conn, 'waste_category'),
    'residents'       => countRows($conn, 'resident'),
    'announcements'   => countRows($conn, 'announcement'),
    'feedback'        => countRows($conn, 'feedback_report'),
    'reports'         => countRows($conn, 'reports'),
];

$today = date('l');
$todayStmt = $conn->prepare("SELECT COUNT(*) AS c FROM collection_schedule WHERE day_of_week = ?");
$todayStmt->bind_param('s', $today);
$todayStmt->execute();
$todayCollections = (int) $todayStmt->get_result()->fetch_assoc()['c'];

$recentFeedback = $conn->query("
    SELECT fr.report_type, r.first_name, r.last_name
    FROM feedback_report fr
    LEFT JOIN resident r ON fr.resident_id = r.resident_id
    ORDER BY fr.feedback_report_id DESC LIMIT 5
");

$recentAnnouncements = $conn->query("SELECT title, published_timestamp FROM announcement ORDER BY published_timestamp DESC LIMIT 5");

include 'includes/layout_top.php';
?>

<div class="content-header">
    <div>
        <h2>Welcome back<?= $adminName ? ', ' . htmlspecialchars($adminName) : '' ?></h2>
        <p>System-wide overview of the barangay waste collection network.</p>
    </div>
</div>


<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-green"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 3 2 12h3v8h6v-6h2v6h6v-8h3L12 3z"/></svg></div>
        <div><div class="stat-value"><?= number_format($stats['barangayadmins']) ?></div><div class="stat-label">Barangay Admins</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-gray"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M4 16a2 2 0 1 0 4 0 2 2 0 0 0-4 0zm12 0a2 2 0 1 0 4 0 2 2 0 0 0-4 0zM3 7h11v7H6.5A2.5 2.5 0 0 0 4 16.5V7zm11 0h2.5L20 11v5h-2"/></svg></div>
        <div><div class="stat-value"><?= number_format($stats['collectors']) ?></div><div class="stat-label">Garbage Collectors</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-amber"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M3 13h11V6H3v7zm12 0h2.5l2.5 3v-3h1V9.5L18.5 7H15v6zM6 18a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg></div>
        <div><div class="stat-value"><?= number_format($stats['trucks']) ?></div><div class="stat-label">Fleet Trucks</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-green"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M9 3 5 6.99h3V14h2V6.99h3L9 3zm7 14.01V10h-2v7.01h-3L15 21l4-3.99h-3z"/></svg></div>
        <div><div class="stat-value"><?= number_format($stats['routes']) ?></div><div class="stat-label">Total Routes</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-blue"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M6 7h12l-1 13H7L6 7zm3-4h6l1 2H8l1-2z"/></svg></div>
        <div><div class="stat-value"><?= number_format($stats['waste']) ?></div><div class="stat-label">Waste Categories</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-green"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg></div>
        <div><div class="stat-value"><?= number_format($stats['residents']) ?></div><div class="stat-label">Residents</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-amber"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/></svg></div>
        <div><div class="stat-value"><?= number_format($stats['feedback']) ?></div><div class="stat-label">Feedback Reports</div></div>
    </div>
    <div class="stat-card stat-card-highlight">
        <div class="stat-icon stat-icon-green"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M7 2v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7zm12 8H5v10h14V10z"/></svg></div>
        <div><div class="stat-value"><?= number_format($todayCollections) ?></div><div class="stat-label">Scheduled Today (<?= htmlspecialchars($today) ?>)</div></div>
    </div>
</div>

<div class="dashboard-panels">
    <div class="panel">
        <div class="panel-header"><h3>Recent Announcements</h3><a href="announcements.php" class="panel-link">View all &rarr;</a></div>
        <?php if ($recentAnnouncements->num_rows === 0): ?>
            <div class="empty-state-card empty-state-compact"><div class="empty-state-icon">📢</div><h3>No announcements yet</h3><p>Post your first announcement.</p></div>
        <?php else: ?>
            <ul class="simple-list">
                <?php while ($a = $recentAnnouncements->fetch_assoc()): ?>
                    <li><span class="simple-list-title"><?= htmlspecialchars($a['title']) ?></span><span class="simple-list-meta"><?= $a['published_timestamp'] ? date('M d, Y', strtotime($a['published_timestamp'])) : '' ?></span></li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div class="panel">
        <div class="panel-header"><h3>Recent Feedback Reports</h3><a href="feedback.php" class="panel-link">View all &rarr;</a></div>
        <?php if ($recentFeedback->num_rows === 0): ?>
            <div class="empty-state-card empty-state-compact"><div class="empty-state-icon">💬</div><h3>No feedback yet</h3><p>Resident reports will show up here.</p></div>
        <?php else: ?>
            <ul class="simple-list">
                <?php while ($f = $recentFeedback->fetch_assoc()): ?>
                    <li><span class="simple-list-title"><?= htmlspecialchars($f['report_type'] ?? 'Report') ?></span><span class="simple-list-meta"><?= htmlspecialchars(trim(($f['first_name'] ?? '') . ' ' . ($f['last_name'] ?? '')) ?: 'Unknown') ?></span></li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/layout_bottom.php'; ?>
