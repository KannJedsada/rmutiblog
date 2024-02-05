<?php
require_once '../security/condb.php';
$sql = "SELECT * FROM users where  DATE(dateCreate) = CURDATE()";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="../style.css">
    <style>
        #myTable thead th {
            background-color: orange;
            color: white;
            padding: 10px;
            text-align: center;
        }

        #myTable tbody tr {
            background-color: #FFEB3B;
        }

        #myTable tbody td {
            width: 50px;
            text-align: center;
        }

        .dataTables_filter {
            margin-bottom: 10px;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
    </style>

    <title>User</title>
</head>

<body>
    <div class="container pt-5 w-80">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <h1>User details</h1>
            <a style="color: orange;" href="./adminindex.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
                </svg>
            </a>

        </div>
        <table id="myTable" class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User id</th>
                    <th>Username</th>
                    <th>role id</th>
                    <th>is Active</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1;
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['role_id']; ?></td>
                        <td><?php echo $row['isActive']; ?></td>
                    </tr>
                <?php $count++;
                } ?>
            </tbody>
        </table>
    </div>
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
    </script>
    <script src="../script.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>