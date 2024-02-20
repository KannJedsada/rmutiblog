<?php
require_once '../security/condb.php';
$sql = "SELECT u.*, r.*, i.*
FROM users AS u
JOIN roleid AS r ON u.role_id = r.role_id
JOIN isactive AS i ON u.isActive = i.isActiveid
WHERE u.role_id = 100
";
$result = $conn->query($sql);


session_start();
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
    header('location: ../frm/frm_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User details</title>
    <style>
        #popup {
            width: 50%;
            position: relative;
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }

        #popup-close {
            position: absolute;
            top: 0;
            right: 0;
            padding: 10px;
            cursor: pointer;
        }

        #myTable thead th {
            background-color: orange;
            color: white;
            padding: 10px;
            text-align: center;
        }

        #myTable tbody td {
            width: auto;
            text-align: center;
        }

        .adduser {
            display: none;
        }
    </style>
</head>

<body>
    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger" role="alert">
            <?php
            if (is_array($_SESSION['error'])) {
                foreach ($_SESSION['error'] as $error) {
                    echo $error . "<br>";
                }
            } else {
                echo $_SESSION['error'];
            }
            unset($_SESSION['error']);
            ?>
        </div>
    <?php }  ?>

    <div class=" container pt-5 w-80">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <h1>User details</h1>
            <div>
                <a class="text-success" href="#" style="text-decoration: none;" id="adduser" onclick="showPopup()">
                    <svg xmlns=" http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14 14.252V16.3414C13.3744 16.1203 12.7013 16 12 16C8.68629 16 6 18.6863 6 22H4C4 17.5817 7.58172 14 12 14C12.6906 14 13.3608 14.0875 14 14.252ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11ZM18 17V14H20V17H23V19H20V22H18V19H15V17H18Z"></path>
                    </svg>
                </a>
                <div id="popup">
                    <h1>Adduser</h1>
                    <span id="popup-close" onclick="hidePopup()">&times;</span>
                    <div>
                        <a href="#" id="adduser1" onclick="toggleForm('addUserForm')">Add 1 users</a>
                        <form class="adduser" id="addUserForm" action="adduser.php" method="post" style="display: none;">
                            <label for="" class="input-group">Username</label>
                            <input type="text" class="input-group" name="username" required>
                            <label for="" class="input-group">Email</label>
                            <input type="email" class="input-group" name="email" required>
                            <label for="" class="input-group">Password</label>
                            <input type="password" class="input-group" name="password" required>
                            <br>
                            <button type="submit" name="adduser" class="btn btn-success">Add</button>
                        </form>
                    </div>
                    <div>
                        <a href="#" onclick="toggleForm('fileUploadContainer')">Import from excel</a>
                        <form class="adduser" id="fileUploadContainer" action="adduser.php" method="post" enctype="multipart/form-data" style="display: none;">
                            <input type="file" name="file" id="file" class="file" accept=".csv,.xls,.xlsx">
                            <button type="submit" class="btn btn-success" name="import">Import</button>
                        </form>
                    </div>

                </div>

                <a style="color: orange;" href="./adminindex.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
                    </svg>
                </a>
            </div>
        </div>
        <table id="myTable" class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User id</th>
                    <th>Username</th>
                    <th>role id</th>
                    <th>is Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1;
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['role_status']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <div>
                                <a id="popupButton" class="text-warning" onclick="openModal('editModal_<?php echo $row['user_id']; ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M5 18.89H6.41421L15.7279 9.57627L14.3137 8.16206L5 17.4758V18.89ZM21 20.89H3V16.6473L16.435 3.21231C16.8256 2.82179 17.4587 2.82179 17.8492 3.21231L20.6777 6.04074C21.0682 6.43126 21.0682 7.06443 20.6777 7.45495L9.24264 18.89H21V20.89ZM15.7279 6.74785L17.1421 8.16206L18.5563 6.74785L17.1421 5.33363L15.7279 6.74785Z"></path>
                                    </svg></a>
                                <div id="editModal_<?php echo $row['user_id']; ?>" class="modaladd">
                                    <div class="modal-contentadd" style="width: 40%; align-items: center;">
                                        <span class="close" onclick="closeModal('editModal_<?php echo $row['user_id']; ?>')">&times;</span>
                                        <?php
                                        $editProfile = $row['user_id'];
                                        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :editProfile");
                                        $stmt->bindParam(":editProfile", $editProfile);
                                        $stmt->execute();
                                        $data = $stmt->fetch(PDO::FETCH_ASSOC);
                                        ?>
                                        <form action="edituser.php" method="POST">
                                            <input type="hidden" name="editprofileId" value="<?php echo $data['user_id']; ?>">
                                            <div class="form-group">
                                                <div>
                                                    <label for="profile_img" style="font-family: Montserrat, sans-serif">Image</label><br>
                                                    <?php if (!empty($data['profile_img'])) : ?>
                                                        <img id="preview" src="../img/profile/<?php echo $data['profile_img']; ?>" alt="Post Image" style="max-width: 15%; margin: auto; border-radius: 15%;">
                                                    <?php else : ?>
                                                        <img id="preview" src="../img/profile/profile-icon-png-910.png" alt="Post Image Preview" style="max-width: 15%; margin: auto;">
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <label for="username" style="font-family: Montserrat, sans-serif">Username</label>
                                                    <input type="text" name="username" class="input-group" value="<?php echo $data['username']; ?>" required>
                                                </div>
                                                <div>
                                                    <label for="email" style="font-family: Montserrat, sans-serif">Email</label>
                                                    <input type="text" name="email" class="input-group" value="<?php echo $data['email']; ?>" required>
                                                </div>
                                                <div>
                                                    <label for="role_id" style="font-family: Montserrat, sans-serif">Role id</label>
                                                    <?php
                                                    $role = $conn->prepare("SELECT * FROM roleid");
                                                    $role->execute();
                                                    ?>
                                                    <select name="roleid" class="form-select" required>
                                                        <?php
                                                        while ($datarole = $role->fetch(PDO::FETCH_ASSOC)) {
                                                            if ($datarole['role_id'] != 999) {
                                                                $selected = ($datarole['role_id'] == $data['role_id']) ? 'selected' : '';
                                                                echo '<option value="' . $datarole['role_id'] . '" ' . $selected . '>' . $datarole['role_status'] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="isActive" style="font-family: Montserrat, sans-serif">Is Active</label>
                                                    <?php
                                                    $isactive = $conn->prepare("SELECT * FROM isactive");
                                                    $isactive->execute();
                                                    ?>
                                                    <select name="isActive" class="form-select" required>
                                                        <?php
                                                        while ($dataactive = $isactive->fetch(PDO::FETCH_ASSOC)) {
                                                            $selected = ($dataactive['isActiveid'] == $data['isActive']) ? 'selected' : '';
                                                            echo '<option value="' . $dataactive['isActiveid'] . '" ' . $selected . '>' . $dataactive['status'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <button type="submit" name="editprofile" class="btn" style="background-color: orange; color: white; transition: background-color 0.3s, color 0.3s;" onmouseover="this.style.backgroundColor='darkorange'; this.style.color='white'" onmouseout="this.style.backgroundColor='orange'; this.style.color='white'">
                                                บันทึก
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <a href="#" class="text-danger" onclick="confirmDelete('<?php echo $row['user_id']; ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M17 6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6ZM18 8H6V20H18V8ZM9 11H11V17H9V11ZM13 11H15V17H13V11ZM9 4V6H15V4H9Z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php $count++;
                } ?>
            </tbody>
        </table>
    </div>
</body>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });

    function confirmDelete(userId) {
        var result = confirm("จะลบจริงอ๊ะป่าว?");
        if (result) {
            window.location.href = "deleteuser.php?id=" + userId;
        }
    }

    function showPopup() {
        var popup = document.getElementById("popup");
        popup.style.display = "block";
    }

    function hidePopup() {
        var popup = document.getElementById("popup");
        popup.style.display = "none";
    }

    function toggleForm(formId) {
        var form = document.getElementById(formId);
        if (form.classList.contains('adduser')) {
            form.classList.toggle('adduser');
        } else {
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    }

    function confirmDelete(userId) {
        var result = confirm("จะลบจริงอ๊ะป่าว?");
        if (result) {
            window.location.href = "deleteuser.php?id=" + userId;
        }
    }

    function openModal(modalId) {
        var modal = document.getElementById(modalId);
        modal.style.display = "block";

        // Add an event listener to close the modal if clicked outside its content
        window.addEventListener("click", function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    }

    function closeModal(modalId) {
        var modal = document.getElementById(modalId);
        modal.style.display = "none";
    }
</script>

</html>