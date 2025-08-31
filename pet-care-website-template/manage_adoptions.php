<?php
include 'config.php';
include 'auth.php';
// Include PHPMailer classes
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

    header("Location: manage_adoptions.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Adoptions - TotoCare</title>
    <style>
        body {
    font-family: 'Roboto', sans-serif;
    background-color: #f3f4f6;
    margin: 0;
    padding: 0;
}

h2 {
    color: #333;
    text-align: center;
    margin: 20px 0;
}

table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

table th, table td {
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #4a5568;
    color: #fff;
    font-weight: bold;
}

table tr:hover {
    background-color: #e2e8f0;
}

button {
    padding: 10px 20px;
    margin: 5px;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-weight: bold;
}

button:hover {
    opacity: 0.9;
}

button.approve {
    background-color: #38a169; /* Green */
}

button.reject {
    background-color: #e53e3e; /* Red */
}

p {
    text-align: center;
    color: #555;
    font-size: 20px;
    margin: 20px;
}

form {
    display: inline-block;
}

    </style>
</head>
<body>
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
                                <button type="submit" name="action" value="approve" style="color: #38a169;">Approve</button>
                                <button type="submit" name="action" value="reject" style="color: #e53e3e;">Reject</button>
                            </form>
                        <?php else: ?>
                            <?php echo htmlspecialchars($request['status']); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No adoption requests found.</p>
    <?php endif; ?>
</body>
</html>
