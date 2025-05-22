<?php
require 'config.php';
checkLogin();
requireAdmin();





// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 // Prevent admins from modifying their own status/role
if(isset($_POST['update'])) {
    if($_POST['id'] == $_SESSION['id'] && ($_POST['role'] != 'admin' || $_POST['status'] == 'disabled')) {
        die("Cannot modify your own admin status/role");
    }
}






// Delete user if delete button is clicked
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ".$_SERVER['PHP_SELF']."?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Toggle user status if enable/disable button is clicked
if (isset($_GET['toggle_status'])) {
    $id = $_GET['toggle_status'];
    
    // First get current status
    $status_query = "SELECT status FROM user WHERE id = ?";
    $stmt = $conn->prepare($status_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Set new status to opposite of current status
    $new_status = ($row['status'] == 'active') ? 'disabled' : 'active';
    
    // Update the status
    $sql = "UPDATE user SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $id);
    
    if ($stmt->execute()) {
        $action = ($new_status == 'active') ? 'enabled' : 'disabled';
        header("Location: ".$_SERVER['PHP_SELF']."?status=$action");
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }
}

// Update user if update form is submitted
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    
    // Check if password was changed
    if (!empty($password)) {
        // Hash the password if it was changed
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE user SET user_id=?, password=?, full_name=?, email=?, contact_number=?, role=?, status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $user_id, $hashed_password, $full_name, $email, $contact_number, $role, $status, $id);
    } else {
        // Don't update password if field was left blank
        $sql = "UPDATE user SET user_id=?, full_name=?, email=?, contact_number=?, role=?, status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $user_id, $full_name, $email, $contact_number, $role, $status, $id);
    }
    
    if ($stmt->execute()) {
        header("Location: ".$_SERVER['PHP_SELF']."?status=updated");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Insert new user if add form is submitted
if (isset($_POST['add'])) {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO user (user_id, password, full_name, email, contact_number, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $user_id, $hashed_password, $full_name, $email, $contact_number, $role, $status);
    
    if ($stmt->execute()) {
        header("Location: ".$_SERVER['PHP_SELF']."?status=added");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch user data
$sql = "SELECT * FROM user";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            padding: 20px;
        }
        .action-buttons a, .action-buttons button {
            margin-right: 5px;
        }
   

    

/* For Bootstrap Icons (if using) */
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css");

/* Modal nav tabs styling */
.modal-nav-tabs {
    background-color: #f8f9fa;
    border-radius: 6px 6px 0 0;
}

.modal-nav-tabs .nav-link {
    color: #495057 !important;
    font-weight: 500;
    border: 1px solid transparent;
    border-bottom: none;
    margin: 0;
    border-radius: 0;
}

.modal-nav-tabs .nav-link:hover {
    background-color: #e9ecef;
    color: #212529 !important;
}

.modal-nav-tabs .nav-link.active {
    background-color: white;
    color: #4361ee !important;
    border-color: #dee2e6;
    border-bottom-color: white !important;
    font-weight: 500;
}

.modal-nav-tabs .nav-item:first-child .nav-link {
    border-radius: 6px 0 0 0;
}

.modal-nav-tabs .nav-item:last-child .nav-link {
    border-radius: 0 6px 0 0;
}


@media (min-width: 992px) {
    .stats-cards {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .stat-card p {
        font-size: 1.8rem;
    }
}






    </style>
</head>
<body>
 
    <div class="container">
        <h1 class="mb-4">Admin Panel</h1>
        
        <?php
        // Display status messages
        if (isset($_GET['status'])) {
            $status = $_GET['status'];
            $message = "";
            $alert_class = "alert-success";
            
            switch ($status) {
                case "added":
                    $message = "User added successfully!";
                    break;
                case "updated":
                    $message = "User updated successfully!";
                    break;
                case "deleted":
                    $message = "User deleted successfully!";
                    break;
                case "enabled":
                    $message = "User account enabled successfully!";
                    break;
                case "disabled":
                    $message = "User account disabled successfully!";
                    break;
                default:
                    $message = "";
            }
            
            if (!empty($message)) {
                echo '<div class="alert ' . $alert_class . ' status-message">' . $message . '</div>';
            }
        }
        ?>
        
        <!-- Add User Button -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Add New User
        </button>
          <li>
                    <a href="home.php" >
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
        
        <!-- User Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>".$row["id"]."</td>";
                            echo "<td>".$row["user_id"]."</td>";
                            echo "<td>".$row["full_name"]."</td>";
                            echo "<td>".$row["email"]."</td>";
                            echo "<td>".$row["contact_number"]."</td>";
                            echo "<td>".$row["role"]."</td>";
                            
                            // Status column with appropriate styling
                            $status_badge = ($row["status"] == 'active') ? 
                                           '<span class="badge bg-success">Active</span>' : 
                                           '<span class="badge bg-danger">Disabled</span>';
                            echo "<td>".$status_badge."</td>";
                            
                            echo "<td>".$row["created_at"]."</td>";
                            echo "<td>".$row["updated_at"]."</td>";
                            echo "<td class='action-buttons'>
                                    <button class='btn btn-sm btn-warning edit-btn' 
                                            data-id='".$row["id"]."'
                                            data-user_id='".$row["user_id"]."'
                                            data-full_name='".$row["full_name"]."'
                                            data-email='".$row["email"]."'
                                            data-contact_number='".$row["contact_number"]."'
                                            data-role='".$row["role"]."'
                                            data-status='".$row["status"]."'
                                            data-bs-toggle='modal' 
                                            data-bs-target='#editUserModal'>
                                        Edit
                                    </button>";
                            
                            // Toggle status button
                            if ($row["status"] == 'active') {
                                echo "<a href='?toggle_status=".$row["id"]."' class='btn btn-sm btn-secondary' 
                                          onclick='return confirm(\"Are you sure you want to disable this account?\")'>Disable</a>";
                            } else {
                                echo "<a href='?toggle_status=".$row["id"]."' class='btn btn-sm btn-success' 
                                          onclick='return confirm(\"Are you sure you want to enable this account?\")'>Enable</a>";
                            }
                            
                            echo "<a href='?delete=".$row["id"]."' class='btn btn-sm btn-danger' 
                                    onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">User ID</label>
                                <input type="text" class="form-control" id="user_id" name="user_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="admin">Admin</option>
                                    <option value="inspector" selected>Inspector</option>
                                    <option value="legal">Legal</option>
                                    <option value="head">Head</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" selected>Active</option>
                                    <option value="disabled">Disabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add" class="btn btn-primary">Add User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <input type="hidden" id="edit_id" name="id">
                            <div class="mb-3">
                                <label for="edit_user_id" class="form-label">User ID</label>
                                <input type="text" class="form-control" id="edit_user_id" name="user_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_password" class="form-label">Password (leave blank to keep current password)</label>
                                <input type="password" class="form-control" id="edit_password" name="password">
                            </div>
                            <div class="mb-3">
                                <label for="edit_full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="edit_contact_number" name="contact_number" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_role" class="form-label">Role</label>
                                <select class="form-select" id="edit_role" name="role">
                                    <option value="admin">Admin</option>
                                    <option value="inspector">Inspector</option>
                                    <option value="legal">Legal</option>
                                    <option value="head">Head</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" name="status">
                                    <option value="active">Active</option>
                                    <option value="disabled">Disabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="update" class="btn btn-primary">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Fill edit modal with user data
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const user_id = this.getAttribute('data-user_id');
                    const full_name = this.getAttribute('data-full_name');
                    const email = this.getAttribute('data-email');
                    const contact_number = this.getAttribute('data-contact_number');
                    const role = this.getAttribute('data-role');
                    const status = this.getAttribute('data-status');
                    
                    document.getElementById('edit_id').value = id;
                    document.getElementById('edit_user_id').value = user_id;
                    document.getElementById('edit_full_name').value = full_name;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_contact_number').value = contact_number;
                    document.getElementById('edit_role').value = role;
                    document.getElementById('edit_status').value = status;
                });
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>