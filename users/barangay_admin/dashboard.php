<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle  = 'Dashboard';
$activePage = 'dashboard';

$adminId = $currentBarangayAdminId;

/** Safely run a scalar COUNT query with bound params. Returns 0 on any failure. */
function safeScalarCount(mysqli $conn, string $sql, string $types = '', array $params = []): int {
    try {
        $stmt = $conn->prepare($sql);
        if ($types !== '') {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (int) ($row['c'] ?? 0);
    } catch (Exception $e) {
        return 0;
    }
}

$myRoutesCount     = safeScalarCount($conn, 'SELECT COUNT(*) AS c FROM route WHERE barangay_admin_id = ?', 'i', [$adminId]);
$mySchedulesCount  = safeScalarCount($conn, 'SELECT COUNT(*) AS c FROM collection_schedule WHERE barangay_admin_id = ?', 'i', [$adminId]);
$myCollectorsCount = safeScalarCount($conn, 'SELECT COUNT(*) AS c FROM garbage_collector WHERE route_id IN (SELECT route_id FROM route WHERE barangay_admin_id = ?)', 'i', [$adminId]);
$wasteCategoryCount = safeScalarCount($conn, 'SELECT COUNT(*) AS c FROM waste_category', '', []);

$today = date('l');
$todayCollections = safeScalarCount(
    $conn,
    'SELECT COUNT(*) AS c FROM collection_schedule WHERE barangay_admin_id = ? AND day_of_week = ?',
    'is',
    [$adminId, $today]
);

// Recent schedules for this barangay admin (latest 5)
$recentSchedules = [];
try {
    $stmt = $conn->prepare('
        SELECT cs.collection_schedule_id, cs.day_of_week, cs.schedule_status, wc.category_name
        FROM collection_schedule cs
        LEFT JOIN waste_category wc ON cs.waste_category_id = wc.waste_category_id
        WHERE cs.barangay_admin_id = ?
        ORDER BY cs.collection_schedule_id DESC
        LIMIT 5
    ');
    $stmt->bind_param('i', $adminId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $recentSchedules[] = $row;
    }
} catch (Exception $e) { /* table may be empty */ }

// Garbage collectors on this admin's routes
$myCollectors = [];
try {
    $stmt = $conn->prepare('
        SELECT gc.garbage_collector_id, gc.first_name, gc.last_name, gc.route_id
        FROM garbage_collector gc
        WHERE gc.route_id IN (SELECT route_id FROM route WHERE barangay_admin_id = ?)
        LIMIT 5
    ');
    $stmt->bind_param('i', $adminId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $myCollectors[] = $row;
    }
} catch (Exception $e) { /* table may be empty */ }

include __DIR__ . '/includes/admin_head.php';
?>

<div class="content-header">
    <div>
        <h2>Welcome back<?= $currentBarangayAdminName ? ', ' . htmlspecialchars($currentBarangayAdminName) : '' ?> </h2>
        <p>Here's an overview of your barangay's routes and collection schedules.</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-green">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M9 3 5 6.99h3V14h2V6.99h3L9 3zm7 14.01V10h-2v7.01h-3L15 21l4-3.99h-3z"/></svg>
        </div>
        <div>
            <div class="stat-value"><?= number_format($myRoutesCount) ?></div>
            <div class="stat-label">My Routes</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-blue">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M7 2v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7zm12 8H5v10h14V10z"/></svg>
        </div>
        <div>
            <div class="stat-value"><?= number_format($mySchedulesCount) ?></div>
            <div class="stat-label">Collection Schedules</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-gray">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M4 16a2 2 0 1 0 4 0 2 2 0 0 0-4 0zm12 0a2 2 0 1 0 4 0 2 2 0 0 0-4 0zM3 7h11v7H6.5A2.5 2.5 0 0 0 4 16.5V7zm11 0h2.5L20 11v5h-2"/></svg>
        </div>
        <div>
            <div class="stat-value"><?= number_format($myCollectorsCount) ?></div>
            <div class="stat-label">Garbage Collectors on My Routes</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-amber">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M6 7h12l-1 13H7L6 7zm3-4h6l1 2H8l1-2z"/></svg>
        </div>
        <div>
            <div class="stat-value"><?= number_format($wasteCategoryCount) ?></div>
            <div class="stat-label">Waste Categories (Reference)</div>
        </div>
    </div>

    <div class="stat-card stat-card-highlight">
        <div class="stat-icon stat-icon-green">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/></svg>
        </div>
        <div>
            <div class="stat-value"><?= number_format($todayCollections) ?></div>
            <div class="stat-label">Scheduled Today (<?= htmlspecialchars($today) ?>)</div>
        </div>
    </div>
</div>

<div class="dashboard-panels">
    <div class="panel">
        <div class="panel-header">
            <h3>Recent Collection Schedules</h3>
            <a href="schedules.php" class="panel-link">View all &rarr;</a>
        </div>
        <?php if (empty($recentSchedules)): ?>
            <div class="empty-state-card empty-state-compact">
                <div class="empty-state-icon"></div>
                <h3>No schedules yet</h3>
                <p>Add your first collection schedule to see it here.</p>
            </div>
        <?php else: ?>
            <ul class="simple-list">
                <?php foreach ($recentSchedules as $s): ?>
                    <li>
                        <span class="simple-list-title"><?= htmlspecialchars($s['category_name'] ?? 'Waste') ?> &mdash; <?= htmlspecialchars($s['day_of_week']) ?></span>
                        <span class="simple-list-meta"><?= htmlspecialchars($s['schedule_status'] ?? '') ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h3>Garbage Collectors on My Routes</h3>
            <a href="collectors.php" class="panel-link">View all &rarr;</a>
        </div>
        <?php if (empty($myCollectors)): ?>
            <div class="empty-state-card empty-state-compact">
                <div class="empty-state-icon"></div>
                <h3>No collectors assigned yet</h3>
                <p>Collectors assigned to your routes will show up here.</p>
            </div>
        <?php else: ?>
            <ul class="simple-list">
                <?php foreach ($myCollectors as $c): ?>
                    <li>
                        <span class="simple-list-title"><?= htmlspecialchars(trim($c['first_name'] . ' ' . $c['last_name'])) ?></span>
                        <span class="simple-list-meta">Route #<?= (int) $c['route_id'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/admin_foot.php'; ?>
