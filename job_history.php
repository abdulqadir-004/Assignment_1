<?php
require_once 'config/database.php';
require_once 'includes/table_functions.php';

$table_name = 'job_history';
$primary_key = 'employee_id'; // Composite key, but we'll use employee_id for deletion
$data = getTableData($pdo, $table_name);
$columns = getTableColumns($pdo, $table_name);
$employees = getTableData($pdo, 'employees');
$jobs = getTableData($pdo, 'jobs');
$departments = getTableData($pdo, 'departments');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job History - HR Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-history me-2"></i>Job History</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i>Add New Job History
            </button>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search job history...">
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
                                        <button class="btn btn-sm btn-danger" onclick="deleteRecord(<?php echo $row['employee_id']; ?>, '<?php echo $row['start_date']; ?>')">
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
                    <h5 class="modal-title">Add New Job History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addForm">
                    <div class="modal-body">
                        <input type="hidden" name="table" value="<?php echo $table_name; ?>">
                        <div class="mb-3">
                            <label class="form-label">Employee</label>
                            <select class="form-select" name="data[employee_id]" required>
                                <option value="">Select Employee</option>
                                <?php foreach($employees as $emp): ?>
                                    <option value="<?php echo $emp['employee_id']; ?>"><?php echo htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="data[start_date]" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="data[end_date]" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Job</label>
                            <select class="form-select" name="data[job_id]" required>
                                <option value="">Select Job</option>
                                <?php foreach($jobs as $job): ?>
                                    <option value="<?php echo $job['job_id']; ?>"><?php echo htmlspecialchars($job['job_title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="data[department_id]" required>
                                <option value="">Select Department</option>
                                <?php foreach($departments as $dept): ?>
                                    <option value="<?php echo $dept['department_id']; ?>"><?php echo htmlspecialchars($dept['department_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Job History</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const tableName = '<?php echo $table_name; ?>';

        function deleteRecord(employeeId, startDate) {
            if (confirm('Are you sure you want to delete this record?')) {
                // For job_history, we need to delete by employee_id AND start_date
                const formData = new FormData();
                formData.append('table', tableName);
                formData.append('id_field', 'employee_id');
                formData.append('id_value', employeeId);
                formData.append('start_date', startDate);

                // Custom delete for composite key
                fetch('api/delete_job_history.php', {
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
                tr.innerHTML += `<td><button class="btn btn-sm btn-danger" onclick="deleteRecord(${row['employee_id']}, '${row['start_date']}')"><i class="fas fa-trash"></i></button></td>`;
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
                    data[field] = value;
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

