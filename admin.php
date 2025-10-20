<?php
session_start();
ob_start();
include("../connection.php");
if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
     header("Location: signin.php");
     exit();
}
// Ensure location and Admin ID are set
$loc = $_SESSION['location'] ?? null;
$id = $_SESSION['Aid'] ?? null;
// Approve new admin
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve'])) {
//     $approve_id = $_POST['approve_id'];
//     $query = "UPDATE admin SET status='approved' WHERE Aid=?";
//     $stmt = mysqli_prepare($connection, $query);
//     mysqli_stmt_bind_param($stmt, "i", $approve_id);
//     mysqli_stmt_execute($stmt);
//     header("Location: " . $_SERVER['PHP_SELF']);
//     exit();
// }
// Approve delivery person
$loc = $_SESSION['location'] ?? null;
$id = $_SESSION['Did'] ?? 0; // or some default value
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve_delivery'])) {
    $approve_delivery_id = $_POST['approve_delivery_id'];
    $query = "UPDATE delivery_persons SET status='approved' WHERE Did=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $approve_delivery_id);
    mysqli_stmt_execute($stmt);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve_user'])) {
    $approve_user_id = $_POST['approve_user_id'];
    $query = "UPDATE login SET is_approved = 1 WHERE id=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $approve_user_id);
    mysqli_stmt_execute($stmt);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve_fooddonation'])) {
    $approve_fooddonation_id = $_POST['approve_fooddonation_id'];
    $query = "UPDATE food_donations SET approval_status='approved' WHERE Fid=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $approve_fooddonation_id);
    mysqli_stmt_execute($stmt);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Admin Dashboard Panel</title>
</head>
<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image"></div>
            <span class="logo_name">ADMIN</span>
        </div>
        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="#"><i class="uil uil-estate"></i> <span class="link-name">Dashboard</span></a></li>
                <li><a href="analytics.php"><i class="uil uil-chart"></i> <span class="link-name">Analytics</span></a></li>
                <li><a href="donate.php"><i class="uil uil-heart"></i> <span class="link-name">Donates</span></a></li>
                <li><a href="feedback.php"><i class="uil uil-comments"></i> <span class="link-name">Feedbacks</span></a></li>
                <li><a href="adminprofile.php"><i class="uil uil-user"></i> <span class="link-name">Profile</span></a></li>
            </ul>        
            <ul class="logout-mode">
                <li><a href="../logout.php"><i class="uil uil-signout"></i> <span class="link-name">Logout</span></a></li>
                <li class="mode">
                    <a href="#"><i class="uil uil-moon"></i> <span class="link-name">Dark Mode</span></a>
                    <div class="mode-toggle"><span class="switch"></span></div>
                </li>
            </ul>
        </div>
    </nav>
    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <p class="logo">Food <b style="color: #06C167;" >Donate</b></p>
        </div>
        <div class="dash-content">
            <div class="overview">
                <div class="title"><i class="uil uil-tachometer-fast-alt"></i> <span class="text">Dashboard</span></div>
                <div class="boxes">
                    <div class="box box1">
                        <i class="uil uil-user"></i>
                        <span class="text">Total users</span>
                        <?php
                        $query = "SELECT COUNT(*) as count FROM login";
                        $stmt = mysqli_prepare($connection, $query);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $row = mysqli_fetch_assoc($result);
                        echo "<span class='number'>{$row['count']}</span>";
                        ?>O
                    </div>
                    <div class="box box2">
                        <i class="uil uil-comments"></i>
                        <span class="text">Feedbacks</span>
                        <?php
                           $query="SELECT count(*) as count FROM  user_feedback";
                           $result=mysqli_query($connection, $query);
                           $row=mysqli_fetch_assoc($result);
                         echo "<span class=\"number\">".$row['count']."</span>";
                        ?>
                    </div>
                    <div class="box box3">
                        <i class="uil uil-heart"></i>
                        <span class="text">Total Donations</span>
                        <?php
                        $query = "SELECT COUNT(*) as count FROM food_donations";
                        $stmt = mysqli_prepare($connection, $query);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $row = mysqli_fetch_assoc($result);
                        echo "<span class='number'>{$row['count']}</span>";
                        ?>
                    </div>
                </div>
            </div>
            <!-- <div class="activity">
                <div class="title"><i class="uil uil-user-plus"></i> <span class="text">Pending Admin Approvals</span></div>
                <div class="table-container">
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // $query = "SELECT * FROM admin WHERE status = 'pending'";
                                // $result = mysqli_query($connection, $query);
                                // while ($row = mysqli_fetch_assoc($result)) {
                                //     echo "<tr>
                                //             <td>{$row['name']}</td>
                                //             <td>{$row['email']}</td>
                                //             <td>{$row['location']}</td>
                                //             <td>
                                //                 <form method='post'>
                                //                     <input type='hidden' name='approve_id' value='{$row['Aid']}'>
                                //                     <button type='submit' name='approve'>Approve</button>
                                //                 </form>
                                //             </td>
                                //         </tr>";
                                // }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> -->
            <!-- Delivery Person Approvals -->
            <div class="activity">
                <div class="title"><i class="uil uil-truck"></i> <span class="text">Pending Delivery Approvals</span></div>
                <div class="table-container">
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>City</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM delivery_persons WHERE status = 'pending'";
                                $result = mysqli_query($connection, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                            <td>{$row['name']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['city']}</td>
                                            <td>
                                                <form method='post'>
                                                    <input type='hidden' name='approve_delivery_id' value='{$row['Did']}'>
                                                    <button type='submit' name='approve_delivery'>Approve</button>
                                                </form>
                                            </td>
                                        </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="activity">
                <div class="title"><i class="uil uil-user-plus"></i> <span class="text">Pending User Approvals</span></div>
                <div class="table-container">
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Gender</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $query = "SELECT * FROM login WHERE is_approved = 0";
                                $result = mysqli_query($connection, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                            <td>" . htmlspecialchars($row['name']) . "</td>
                                            <td>" . htmlspecialchars($row['email']) . "</td>
                                            <td>" . htmlspecialchars($row['gender']) . "</td>
                                            <td>
                                                <form method='post'>
                                                    <input type='hidden' name='approve_user_id' value='{$row['id']}'>
                                                    <button type='submit' name='approve_user'>Approve</button>
                                                </form>
                                            </td>
                                        </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>            
            <div class="activity">
                <div class="title"><i class="uil uil-clock-three"></i> <span class="text">Recent Donations</span></div>
                <div class="table-container">
                    <div class="table-wrapper">
                    <form method='post'>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th>Food</th>
                                    <th>Meal</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Phone</th>
                                    <th>Location</th>
                                    <th>Address</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $query = "SELECT * FROM food_donations WHERE approval_status = 'pending'";
                                $result = mysqli_query($connection, $query);
                                while ($row = mysqli_fetch_assoc($result)) { 
                                    echo"<tr>
                                            <td>{$row['name']}</td>
                                            <td>{$row['foodname']}</td>
                                            <td>{$row['meal']}</td>                                                                                        
                                            <td>{$row['category']}</td>
                                            <td>{$row['quantity']}</td>
                                            <td>{$row['phoneno']}</td>
                                            <td>{$row['location']}</td>
                                            <td>{$row['address']}</td>

                                            <td>                                            
                                            <form method='post'>
                                                    <input type='hidden' name='approve_fooddonation_id' value='{$row['Fid']}'>
                                                    <button type='submit' name='approve_fooddonation'>Approve</button>
                                                </form>
                                            </td>
                                        </tr>";
                                 } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <?php
                // Assign order
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['foodname'])) {
                    $order_id = $_POST['order_id'];
                    $delivery_person_id = $_POST['delivery_person_id'];
                    // Check if order is already assigned
                    $query = "SELECT * FROM food_donations WHERE Fid = ? AND assigned_to IS NOT NULL";
                    $stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($stmt, "i", $order_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) == 0) {
                        // Assign order
                        $query = "UPDATE food_donations SET assigned_to = ? WHERE Fid = ?";
                        $stmt = mysqli_prepare($connection, $query);
                        mysqli_stmt_bind_param($stmt, "ii", $delivery_person_id, $order_id);
                        mysqli_stmt_execute($stmt);
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }
                }
                ?>
            </div>
        </div>
    </section>
    <script src="admin.js"></script>
</body>
</html>
