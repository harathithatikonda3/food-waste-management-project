<?php
include '../connection.php';
if(isset($_GET['approve'])){
    $id = $_GET['approve'];
    mysqli_query($connection, "UPDATE admin SET status='approved' WHERE id='$id'");
}
$result = mysqli_query($connection, "SELECT * FROM admin WHERE status='pending'");
echo "<h2>Pending Admin Approvals</h2><ul>";
while($row = mysqli_fetch_assoc($result)){
    echo "<li>".$row['name']." (".$row['email'].") - <a href='admin_approval.php?approve=".$row['Aid']."'>Approve</a></li>";
}
echo "</ul>";

if(isset($_GET['approve'])){
    $id = $_GET['approve'];
    mysqli_query($connection, "UPDATE delivery_persons SET status='approved' WHERE id='$id'");
}
$result = mysqli_query($connection, "SELECT * FROM delivery_persons WHERE status='pending'");
echo "<h2>Pending delivery Approvals</h2><ul>";
while($row = mysqli_fetch_assoc($result)){
    echo "<li>".$row['name']." (".$row['email'].") - <a href='admin_approval.php?approve=".$row['id']."'>Approve</a></li>";
}
echo "</ul>";
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "UPDATE delivery_persons SET status='approved' WHERE Did='$id'";
  mysqli_query($connection, $sql);
  header("Location: admin.php");

}

?>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve'])) {
    $id = $_POST['user_id'];
    $stmt = $connection->prepare("UPDATE login SET is_approved = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$result = $connection->query("SELECT * FROM login WHERE is_approved = 0");
?>

<h2>Pending User Approvals</h2>
<?php while ($row = $result->fetch_assoc()) { ?>
    <form method="post">
        <p><?php echo $row['name'] . " (" . $row['email'] . ")"; ?></p>
        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
        <button type="submit" name="approve">Approve</button>
    </form>
<?php } 
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    mysqli_query($connection, "UPDATE food_donations SET status='approved' WHERE id=$id");
}

if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    mysqli_query($connection, "UPDATE food_donations SET status='rejected' WHERE id=$id");
}

$result = mysqli_query($connection, "SELECT * FROM food_donations WHERE approval_status='pending'");?>
<h2 style="text-align:center;">Pending Food Donations</h2>

<table>
    <tr>
        <th>Donor</th>
        <th>Food</th>
        <th>Meal</th>
        <th>Category</th>
        <th>Quantity</th>
        <th>Phone</th>
        <th>Location</th>
        <th>Address</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['foodname']) ?></td>
            <td><?= $row['meal'] ?></td>
            <td><?= $row['category'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['phoneno'] ?></td>
            <td><?= $row['location'] ?></td>
            <td><?= $row['address'] ?></td>
            <td>
                                                <form method='post'>
                                                    <input type='hidden' name='approve_user_id' value='{$row['id']}'>
                                                    <button type='submit' name='approve_user'>Approve</button>
                                                </form>
                                            </td>
        </tr>
    <?php } ?>

