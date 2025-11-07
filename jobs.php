<?php
require_once 'config/database.php';
require_once 'includes/table_functions.php';

$table_name = 'jobs';
$primary_key = getPrimaryKey($pdo, $table_name);
$data = getTableData($pdo, $table_name);
$columns = getTableColumns($pdo, $table_name);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs - HR Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-briefcase me-2"></i>Jobs</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i>Add New Job
            </button>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search jobs...">
                    <button class="btn btn-outline-secondary" onclick="searchData()">Search</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <?php foreach($columns as $col): ?>
                                    <th><?php echo ucfirst(str_replace('_', ' ', $col)); ?></th>
                                <?php endforeach; ?>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach($data as $row): ?>
                                <tr>
                                    <?php foreach($columns as $col): ?>
                                        <td><?php echo htmlspecialchars($row[$col] ?? ''); ?></td>
                                    <?php endforeach; ?>
                                    <td>
                                        <button class="btn btn-sm btn-danger" onclick="deleteRecord('<?php echo $row[$primary_key]; ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Job</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addForm">
                    <div class="modal-body">
                        <input type="hidden" name="table" value="<?php echo $table_name; ?>">
                        <div class="mb-3">
                            <label class="form-label">Job ID</label>
                            <input type="text" class="form-control" name="data[job_id]" maxlength="10" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Job Title</label>
                            <input type="text" class="form-control" name="data[job_title]" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Min Salary</label>
                            <input type="number" class="form-control" name="data[min_salary]" step="0.01">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Max Salary</label>
                            <input type="number" class="form-control" name="data[max_salary]" step="0.01">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const tableName = '<?php echo $table_name; ?>';
        const primaryKey = '<?php echo $primary_key; ?>';

        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                const formData = new FormData();
                formData.append('table', tableName);
                formData.append('id_field', primaryKey);
                formData.append('id_value', id);

                fetch('api/delete.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.error);
                    }
                });
            }
        }

        function searchData() {
            const term = document.getElementById('searchInput').value;
            if (term.trim() === '') {
                location.reload();
                return;
            }
            fetch(`api/search.php?table=${tableName}&term=${encodeURIComponent(term)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateTable(data.data);
                    }
                });
        }

        function updateTable(data) {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = '';
            data.forEach(row => {
                const tr = document.createElement('tr');
                <?php foreach($columns as $col): ?>
                    tr.innerHTML += `<td>${row['<?php echo $col; ?>'] || ''}</td>`;
                <?php endforeach; ?>
                tr.innerHTML += `<td><button class="btn btn-sm btn-danger" onclick="deleteRecord('${row['<?php echo $primary_key; ?>']}')"><i class="fas fa-trash"></i></button></td>`;
                tbody.appendChild(tr);
            });
        }

        document.getElementById('addForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => {
                if (key.startsWith('data[')) {
                    const field = key.match(/data\[(.*?)\]/)[1];
                    data[field] = value || null;
                }
            });

            const postData = new FormData();
            postData.append('table', formData.get('table'));
            postData.append('data', JSON.stringify(data));

            fetch('api/add.php', {
                method: 'POST',
                body: postData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    location.reload();
                } else {
                    alert('Error: ' + result.error);
                }
            });
        });

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchData();
            }
        });
    </script>
</body>
</html>

