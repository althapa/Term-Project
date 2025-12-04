<?php
require __DIR__ . '/_auth.php';

// Fetch user list
$users = $conn->query("
    SELECT id, name, email, is_admin, created_at
    FROM users
    ORDER BY id DESC
");

$page_title = "Admin - Users";
include __DIR__ . '/../includes/header.php';
?>

<style>
    body {
        background: #f5f5f7;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .user-container {
        max-width: 1100px;
        margin: auto;
    }

    .user-title {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #1d1d1f;
    }

    .apple-table {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.08);
        border: 1px solid #e0e0e0;
    }

    .apple-table th {
        background: #f0f0f2;
        color: #1d1d1f;
        font-weight: 600;
        padding: 12px;
    }

    .apple-table td {
        padding: 12px;
        vertical-align: middle;
    }

    .admin-badge {
        background: #0071e3;
        color: white;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 0.8rem;
    }

    .user-badge {
        background: #34c759;
        color: white;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 0.8rem;
    }

    .btn-apple {
        background: #0071e3;
        color: white;
        padding: 8px 18px;
        border-radius: 10px;
        font-weight: 500;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-apple:hover {
        background: #005bb5;
    }

    .btn-danger-apple {
        background: #ff3b30;
        color: white;
        padding: 8px 18px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
    }

    .btn-danger-apple:hover {
        background: #d9342a;
    }
</style>

<div class="user-container mt-5">

    <h1 class="user-title">User Accounts</h1>

    <div class="apple-table">

        <table class="table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($u = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>

                    <td>
                        <?php if ($u['is_admin'] == 1): ?>
                            <span class="admin-badge">Admin</span>
                        <?php else: ?>
                            <span class="user-badge">Customer</span>
                        <?php endif; ?>
                    </td>

                    <td><?= $u['created_at'] ?></td>

                    <td>
                        <?php if ($u['is_admin'] == 1): ?>
                            <a href="user_role.php?id=<?= $u['id'] ?>&set=0"
                               class="btn-danger-apple">
                               Remove Admin
                            </a>
                        <?php else: ?>
                            <a href="user_role.php?id=<?= $u['id'] ?>&set=1"
                               class="btn-apple">
                               Make Admin
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>

        </table>
    </div>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
