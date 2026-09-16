<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle  = 'Garbage Collectors';
$activePage = 'collectors';
$adminId    = $currentBarangayAdminId;

$collectors = [];
$errors = [];
try {
    $stmt = $conn->prepare('
        SELECT gc.garbage_collector_id, gc.first_name, gc.last_name, gc.email, gc.route_id, gc.truck_id,
               t.plate_number
        FROM garbage_collector gc
        LEFT JOIN truck t ON gc.truck_id = t.truck_id
        WHERE gc.route_id IN (SELECT route_id FROM route WHERE barangay_admin_id = ?)
        ORDER BY gc.last_name, gc.first_name
    ');
    $stmt->bind_param('i', $adminId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $collectors[] = $row;
    }
} catch (Exception $e) {
    $errors[] = 'Could not load garbage collectors.';
}

include __DIR__ . '/includes/admin_head.php';
?>

<div class="content-header">
    <div>
        <h2>Garbage Collectors</h2>
        <p>Collectors currently assigned to routes in your barangay. This list is read-only &mdash; collector accounts are managed by the LGU Admin.</p>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $error): ?><p><?= htmlspecialchars($error) ?></p><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Route</th>
                <th>Truck</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($collectors)): ?>
                <tr><td colspan="4">No collectors are currently assigned to your routes.</td></tr>
            <?php else: ?>
                <?php foreach ($collectors as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars(trim($c['first_name'] . ' ' . $c['last_name'])) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td>#<?= (int) $c['route_id'] ?></td>
                        <td><?= htmlspecialchars($c['plate_number'] ?? '—') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/includes/admin_foot.php'; ?>
