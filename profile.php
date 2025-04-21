<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require_once 'private/update_username.php';
require_once 'private/get_gender.php';
require_once 'private/get_country.php';
$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $result = updateUsername(
        $_SESSION['username'],
        $_POST['username']
    );
    if ($result['status']) {
        $_SESSION['username'] = $_POST['username'];
    } else {
        $errorMessage = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Photo Booth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px 0;
        }
        .profile-card {
            max-width: 450px;
            margin: 30px auto;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
            background-color: #fff;
            padding: 30px;
        }
        .profile-img-container {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 25px;
            border: 5px solid #f0f0f0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .username-input {
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            border: 1px solid #e0e0e0;
            width: 100%;
            margin-bottom: 20px;
            padding: 8px;
            border-radius: 5px;
        }
        .username-input:focus {
            outline: none;
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        .profile-info {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 25px;
            background-color: #f8f9fa;
        }
        .profile-info .list-group-item {
            padding: 12px 20px;
            border-left: none;
            border-right: none;
            background-color: transparent;
        }
        .profile-info .list-group-item:first-child {
            border-top: none;
        }
        .profile-info .list-group-item:last-child {
            border-bottom: none;
        }
        .info-value {
            font-weight: 500;
        }
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #3a5ccc;
            border-color: #3a5ccc;
        }
        .btn-secondary {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
        }
        .action-buttons {
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <form method="post" action="">
                <div class="profile-img-container">
                    <img src="https://picsum.photos/200" alt="Profile Image" class="profile-img">
                </div>

                <input type="text" class="username-input" name="username" value="<?= htmlspecialchars($_SESSION['username']) ?>" aria-label="Username">

                <div class="profile-info">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div><i class="bi bi-person-fill me-2"></i>Gender:</div>
                            <div class="info-value"><?= getGender($_SESSION['username'])['gender']; ?></div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div><i class="bi bi-globe me-2"></i>Country Code:</div>
                            <div class="info-value"><?= getCountry($_SESSION['username'])['country'][0]['country_id']; ?></div>
                        </li>
                    </ul>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary" name="submit"><i class="bi bi-check-circle me-2"></i>Save Changes</button>
                </div>
            </form>
        </div>
    </div>


    <div class="action-buttons">
                <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle-fill me-2"></i>Back to Home</a>
            </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>