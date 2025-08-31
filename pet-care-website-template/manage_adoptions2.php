<?php
include 'config.php';
include 'auth.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$user_id = $_SESSION['user_id'];

// Fetch adoption requests for pets listed by the logged-in user
$sql = "SELECT ar.id, ar.status, ar.created_at, p.pet_name, u.username AS adopter_name, u.email AS adopter_email
        FROM adoption_requests ar
        JOIN pets p ON ar.pet_id = p.id
        JOIN users u ON ar.user_id = u.id
        WHERE p.owner_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle approval or rejection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['request_id'])) {
    $action = $_POST['action'];
    $request_id = $_POST['request_id'];
    $status = ($action === 'approve') ? 'Approved' : 'Rejected';

    // Update adoption request status
    $sql = "UPDATE adoption_requests SET status = :status WHERE id = :request_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':status' => $status, ':request_id' => $request_id]);

    // Send email notification to adopter
    $adopter_email = $_POST['adopter_email'];
    $adopter_name = $_POST['adopter_name'];
    $pet_name = $_POST['pet_name'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'totocare.contact@gmail.com';
        $mail->Password = 'osdkllcqdffaumwb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('totocare.contact@gmail.com', 'TotoCare');
        $mail->addAddress($adopter_email);
        $mail->isHTML(true);
        $mail->Subject = "Adoption Request $status";
        $mail->Body = "Dear $adopter_name,<br>Your adoption request for pet <b>$pet_name</b> has been <b>$status</b>.<br>Thank you for showing interest in adoption!";
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    header("Location: manage_adoptions2.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Adoptions - TotoCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/userstyle.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
        <h2 style="color: #ED6436;">Toto<span style="color: #fff;">Care</span></h2>
        </div>
        <a href="user_dashboard.php"><i class="fa fa-home"></i> Home</a>
        <a href="manage_adoptions2.php"><i class="fa fa-paw"></i> Manage Adoptions</a>
        <a href="scheduled_bookings.php"><i class="fa fa-calendar"></i> Scheduled Bookings</a>
        <a href="user_history.php"><i class="fa fa-history"></i> History</a>
        <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Manage Adoptions</h2>
        <?php if (!empty($requests)): ?>
            <table border="1">
                <tr>
                    <th>Request ID</th>
                    <th>Pet Name</th>
                    <th>Adopter Name</th>
                    <th>Adopter Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['id']); ?></td>
                        <td><?php echo htmlspecialchars($request['pet_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['adopter_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['adopter_email']); ?></td>
                        <td><?php echo htmlspecialchars($request['status']); ?></td>
                        <td>
                            <?php if ($request['status'] === 'Pending'): ?>
                                <form method="post">
                                    <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['id']); ?>">
                                    <input type="hidden" name="adopter_email" value="<?php echo htmlspecialchars($request['adopter_email']); ?>">
                                    <input type="hidden" name="adopter_name" value="<?php echo htmlspecialchars($request['adopter_name']); ?>">
                                    <input type="hidden" name="pet_name" value="<?php echo htmlspecialchars($request['pet_name']); ?>">
                                    <button type="submit" name="action" value="approve" class="approve">Approve</button>
                                    <button type="submit" name="action" value="reject" class="reject">Reject</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No adoption requests found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
