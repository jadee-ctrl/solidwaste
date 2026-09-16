<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle  = 'Collection Schedule';
$activePage = 'schedules';
$adminId    = $currentBarangayAdminId;

$daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$statuses   = ['Active', 'Inactive'];

$errors  = [];
$success = '';

// ---- Load waste categories for the dropdown ----
$wasteCategories = [];
try {
    $res = $conn->query('SELECT waste_category_id, category_name FROM waste_category ORDER BY category_name');
    while ($res && $row = $res->fetch_assoc()) {
        $wasteCategories[] = $row;
    }
} catch (Exception $e) {
    $errors[] = 'Could not load waste categories.';
}

// ---- Handle form submissions (add / update / delete) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';

    if ($formAction === 'delete') {
        $scheduleId = (int) ($_POST['collection_schedule_id'] ?? 0);
        try {
            $stmt = $conn->prepare('DELETE FROM collection_schedule WHERE collection_schedule_id = ? AND barangay_admin_id = ?');
            $stmt->bind_param('ii', $scheduleId, $adminId);
            $stmt->execute();
            $success = 'Schedule deleted.';
        } catch (Exception $e) {
            $errors[] = 'Could not delete that schedule.';
        }
    } else {
        $wasteCategoryId = (int) ($_POST['waste_category_id'] ?? 0);
        $dayOfWeek       = $_POST['day_of_week'] ?? '';
        $scheduleStatus  = $_POST['schedule_status'] ?? 'Active';
        $scheduleId      = (int) ($_POST['collection_schedule_id'] ?? 0);

        if ($wasteCategoryId <= 0 || !in_array($dayOfWeek, $daysOfWeek, true)) {
            $errors[] = 'Please choose a waste category and a valid day of the week.';
        } else {
            try {
                if ($formAction === 'update' && $scheduleId > 0) {
                    $stmt = $conn->prepare('UPDATE collection_schedule SET waste_category_id = ?, day_of_week = ?, schedule_status = ? WHERE collection_schedule_id = ? AND barangay_admin_id = ?');
                    $stmt->bind_param('issii', $wasteCategoryId, $dayOfWeek, $scheduleStatus, $scheduleId, $adminId);
                    $stmt->execute();
                    $success = 'Schedule updated.';
                } else {
                    $stmt = $conn->prepare('INSERT INTO collection_schedule (waste_category_id, day_of_week, schedule_status, barangay_admin_id) VALUES (?, ?, ?, ?)');
                    $stmt->bind_param('issi', $wasteCategoryId, $dayOfWeek, $scheduleStatus, $adminId);
                    $stmt->execute();
                    $success = 'Schedule added.';
                }
            } catch (Exception $e) {
                $errors[] = 'Could not save the schedule. Please try again.';
            }
        }
    }
}

// ---- Load this admin's schedules ----
$schedules = [];
try {
    $stmt = $conn->prepare('
        SELECT cs.collection_schedule_id, cs.waste_category_id, cs.day_of_week, cs.schedule_status, wc.category_name
        FROM collection_schedule cs
        LEFT JOIN waste_category wc ON cs.waste_category_id = wc.waste_category_id
        WHERE cs.barangay_admin_id = ?
        ORDER BY FIELD(cs.day_of_week, "Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")
    ');
    $stmt->bind_param('i', $adminId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $schedules[] = $row;
    }
} catch (Exception $e) {
    $errors[] = 'Could not load your schedules.';
}

// ---- If editing, load that schedule's current data ----
$editingSchedule = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    foreach ($schedules as $s) {
        if ((int) $s['collection_schedule_id'] === $editId) {
            $editingSchedule = $s;
            break;
        }
    }
}

include __DIR__ . '/includes/admin_head.php';
?>

<div class="content-header">
    <div>
        <h2>Collection Schedule</h2>
        <p>Set which day each waste category is collected in your barangay.</p>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $error): ?><p><?= htmlspecialchars($error) ?></p><?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><p><?= htmlspecialchars($success) ?></p></div>
<?php endif; ?>

<div class="panel" style="margin-bottom:1.5rem;">
    <div class="panel-header">
        <?php if ($editingSchedule): ?><a href="schedules.php" class="panel-link">Cancel edit</a><?php endif; ?>
    </div>
            
    <?php if (empty($wasteCategories)): ?>
        <div class="empty-state-card empty-state-compact">
            <div class="empty-state-icon">♻️</div>
            <h3>No waste categories found</h3>
            <p>Ask the LGU Admin to add waste categories before creating a schedule.</p>
        </div>
    <?php else: ?>
        <form method="POST" action="schedules.php">
            <input type="hidden" name="form_action" value="<?= $editingSchedule ? 'update' : 'add' ?>">
            <?php if ($editingSchedule): ?>
                <input type="hidden" name="collection_schedule_id" value="<?= (int) $editingSchedule['collection_schedule_id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="waste_category_id">Waste Category</label>
                <select id="waste_category_id" name="waste_category_id" class="form-control" required>
                    <option value="">Select a category&hellip;</option>
                    <?php foreach ($wasteCategories as $wc): ?>
                        <option value="<?= (int) $wc['waste_category_id'] ?>" <?= (isset($editingSchedule) && (int) $editingSchedule['waste_category_id'] === (int) $wc['waste_category_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($wc['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="day_of_week">Day of the Week</label>
                <select id="day_of_week" name="day_of_week" class="form-control" required>
                    <option value="">Select a day&hellip;</option>
                    <?php foreach ($daysOfWeek as $day): ?>
                        <option value="<?= $day ?>" <?= (isset($editingSchedule) && $editingSchedule['day_of_week'] === $day) ? 'selected' : '' ?>><?= $day ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="schedule_status">Status</label>
                <select id="schedule_status" name="schedule_status" class="form-control" required>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status ?>" <?= (isset($editingSchedule) && $editingSchedule['schedule_status'] === $status) ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><?= $editingSchedule ? 'Update Schedule' : 'Add Schedule' ?></button>
        </form>
    <?php endif; ?>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Waste Category</th>
                <th>Day</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($schedules)): ?>
                <tr><td colspan="4">No schedules yet. Add your first one above.</td></tr>
            <?php else: ?>
                <?php foreach ($schedules as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['category_name'] ?? 'Unknown') ?></td>
                        <td><?= htmlspecialchars($s['day_of_week']) ?></td>
                        <td>
                            <span class="status-indicator <?= strtolower($s['schedule_status']) === 'active' ? 'active' : 'inactive' ?>">
                                <?= htmlspecialchars($s['schedule_status']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="schedules.php?edit=<?= (int) $s['collection_schedule_id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                            <form method="POST" action="schedules.php" style="display:inline;" onsubmit="return confirm('Delete this schedule? This cannot be undone.');">
                                <input type="hidden" name="form_action" value="delete">
                                <input type="hidden" name="collection_schedule_id" value="<?= (int) $s['collection_schedule_id'] ?>">
                                <button type="submit" class="btn btn-outline btn-sm" style="color:#c62828;border-color:#f5c6cb;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/includes/admin_foot.php'; ?>
