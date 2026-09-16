<?php
$pageTitle  = 'Routes';
$activePage = 'routes';
include 'includes/auth.php';

$errors  = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';

    if ($formAction === 'delete') {
        $id = (int) ($_POST['route_id'] ?? 0);
        $stmt = $conn->prepare('DELETE FROM route WHERE route_id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $success = 'Route deleted.';
    } else {
        $path    = trim($_POST['path_coordinates'] ?? '');
        $bAdmin  = (int) ($_POST['barangay_admin_id'] ?? 0);
        $id      = (int) ($_POST['route_id'] ?? 0);

        if ($path === '' || $bAdmin <= 0) {
            $errors[] = 'Path coordinates and barangay admin are required.';
        } else {
            if ($formAction === 'update' && $id > 0) {
                $stmt = $conn->prepare('UPDATE route SET path_coordinates=?, barangay_admin_id=? WHERE route_id=?');
                $stmt->bind_param('sii', $path, $bAdmin, $id);
                $stmt->execute();
                $success = 'Route updated.';
            } else {
                $stmt = $conn->prepare('INSERT INTO route (path_coordinates, barangay_admin_id) VALUES (?, ?)');
                $stmt->bind_param('si', $path, $bAdmin);
                $stmt->execute();
                $success = 'Route added.';
            }
        }
    }
}

$barangayAdmins = $conn->query('SELECT barangay_admin_id, firstname, lastname FROM barangay_admin ORDER BY firstname');

$list = $conn->query('
    SELECT r.*, ba.firstname, ba.lastname
    FROM route r
    LEFT JOIN barangay_admin ba ON r.barangay_admin_id = ba.barangay_admin_id
    ORDER BY r.route_id DESC
');

$editing = null;
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare('SELECT * FROM route WHERE route_id = ?');
    $stmt->bind_param('i', $_GET['edit']);
    $stmt->execute();
    $editing = $stmt->get_result()->fetch_assoc();
}

include 'includes/layout_top.php';
?>

<div class="content-header">
    <div>
        <h2>Routes</h2>
        <p>All collection routes across every barangay, each assigned to a barangay admin.</p>
    </div>
</div>

<?php if (!empty($errors)): ?><div class="alert alert-error"><?php foreach ($errors as $e): ?><p><?= htmlspecialchars($e) ?></p><?php endforeach; ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><p><?= htmlspecialchars($success) ?></p></div><?php endif; ?>

<div class="panel" style="margin-bottom:1.5rem;">
    <div class="panel-header">
        <h3><?= $editing ? 'Edit Route #' . (int) $editing['route_id'] : 'Add a New Route' ?></h3>
        <?php if ($editing): ?><a href="routes.php" class="panel-link">Cancel edit</a><?php endif; ?>
    </div>
    <form method="POST" action="routes.php">
        <input type="hidden" name="form_action" value="<?= $editing ? 'update' : 'add' ?>">
        <?php if ($editing): ?><input type="hidden" name="route_id" value="<?= (int) $editing['route_id'] ?>"><?php endif; ?>
        <div class="form-group">
            <label>Barangay Admin</label>
            <select name="barangay_admin_id" class="form-control" required>
                <option value="">Select a barangay admin&hellip;</option>
                <?php $barangayAdmins->data_seek(0); while ($ba = $barangayAdmins->fetch_assoc()): ?>
                    <option value="<?= (int) $ba['barangay_admin_id'] ?>" <?= (isset($editing) && (int) $editing['barangay_admin_id'] === (int) $ba['barangay_admin_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ba['firstname'] . ' ' . $ba['lastname']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Path Coordinates</label>
            <textarea name="path_coordinates" class="form-control" rows="3" placeholder="e.g. 8.2280,124.2452; 8.2295,124.2470" required><?= htmlspecialchars($editing['path_coordinates'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><?= $editing ? 'Update Route' : 'Add Route' ?></button>
    </form>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead><tr><th>Route ID</th><th>Barangay Admin</th><th>Path Coordinates</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if ($list->num_rows === 0): ?>
                <tr><td colspan="4">No routes yet. Add one above.</td></tr>
            <?php else: while ($row = $list->fetch_assoc()): ?>
                <tr>
                    <td>#<?= (int) $row['route_id'] ?></td>
                    <td><?= htmlspecialchars(trim(($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? '')) ?: '—') ?></td>
                    <td><?= htmlspecialchars(mb_strimwidth($row['path_coordinates'], 0, 60, '…')) ?></td>
                    <td>
                        <a href="routes.php?edit=<?= (int) $row['route_id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                        <form method="POST" action="routes.php" style="display:inline;" onsubmit="return confirm('Delete this route?');">
                            <input type="hidden" name="form_action" value="delete">
                            <input type="hidden" name="route_id" value="<?= (int) $row['route_id'] ?>">
                            <button type="submit" class="btn btn-outline btn-sm" style="color:#c62828;border-color:#f5c6cb;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; endif; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/layout_bottom.php'; ?>
