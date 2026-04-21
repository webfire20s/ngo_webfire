<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

/* FETCH DESIGNATIONS */
$designations = $pdo->query("SELECT * FROM designations")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!verifyToken($_POST['csrf_token'])) {
        die("Invalid CSRF Token");
    }

    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $designation_id = $_POST['designation_id'];
    $referral = sanitize($_POST['referral'] ?? '');

    /* CHECK DUPLICATE */
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetch()) {
        echo "<p style='color:red;'>Email already registered</p>";
    } else {
        /* PHOTO UPLOAD */
        $photo_name = null;

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {

            $file = $_FILES['photo'];

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed)) {
                die("Only JPG, JPEG, PNG allowed");
            }

            if ($file['size'] > 2 * 1024 * 1024) {
                die("Max file size is 2MB");
            }

            /* CREATE FOLDER IF NOT EXISTS */
            if (!is_dir('uploads/profile')) {
                mkdir('uploads/profile', 0777, true);
            }

            /* TEMP NAME (will rename after user_id) */
            $temp_name = uniqid() . '.' . $ext;
            $temp_path = "uploads/profile/" . $temp_name;

            move_uploaded_file($file['tmp_name'], $temp_path);

            $photo_name = $temp_name;
        }

        /* CREATE USER */
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, phone, password, role, profile_photo)
            VALUES (?, ?, ?, ?, 'member', ?)
        ");
        $stmt->execute([
            $name,
            $email,
            $phone,
            $password,
            $photo_name
        ]);

        $user_id = $pdo->lastInsertId();
        /* RENAME PHOTO WITH USER ID */
        if ($photo_name) {
            $ext = pathinfo($photo_name, PATHINFO_EXTENSION);

            $new_name = "user_" . $user_id . "." . $ext;
            rename("uploads/profile/" . $photo_name, "uploads/profile/" . $new_name);

            /* UPDATE DB */
            $stmt = $pdo->prepare("UPDATE users SET profile_photo=? WHERE id=?");
            $stmt->execute([$new_name, $user_id]);
        }

        /* HANDLE REFERRAL */
        $referred_by = null;
        if (!empty($referral)) {
            $refCheck = $pdo->prepare("SELECT user_id FROM memberships WHERE referral_code = ?");
            $refCheck->execute([$referral]);
            $refUser = $refCheck->fetch();
            if ($refUser) {
                $referred_by = $refUser['user_id'];
            }
        }

        /* GENERATE REFERRAL CODE */
        $referral_code = strtoupper(substr(md5(uniqid()), 0, 8));

        /* CREATE MEMBERSHIP */
        $stmt = $pdo->prepare("
            INSERT INTO memberships 
            (user_id, designation_id, join_date, expiry_date, status, referral_code, referred_by)
            VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 'expired', ?, ?)
        ");
        $stmt->execute([
            $user_id,
            $designation_id,
            $referral_code,
            $referred_by
        ]);

        echo "<p style='color:green;'>Registration successful. Please login.</p>";
    }
}
?>

<h2>Register</h2>

<form method="POST"  enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo generateToken(); ?>">

    Name:<br>
    <input type="text" name="name" required><br><br>

    Email:<br>
    <input type="email" name="email" required><br><br>

    Phone:<br>
    <input type="text" name="phone" required><br><br>

    Password:<br>
    <input type="password" name="password" required><br><br>

    Designation:<br>
    <select name="designation_id" required>
        <?php foreach ($designations as $d): ?>
            <option value="<?php echo $d['id']; ?>">
                <?php echo $d['title']; ?> (₹<?php echo $d['fee']; ?>)
            </option>
        <?php endforeach; ?>
    </select><br><br>
    
    <input type="file" name="photo" accept="image/*" required><br><br>
    <!-- <button type="submit">Upload Photo</button> -->


    Referral Code (optional):<br>
    <input type="text" name="referral"><br><br>

    <button type="submit">Register</button>
</form>