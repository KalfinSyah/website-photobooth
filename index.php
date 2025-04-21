<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require_once 'private/upload_photos.php';
require_once 'private/get_photos.php';
require_once 'private/delete_photo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $result = uploadPhotos(
        $_SESSION['username'],
        $_POST['photo'],
    );

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteBtn'])) {
    $result = deletePhoto($_POST['photo_id']);
}
$userPhotos = getPhotos($_SESSION['username']);
$othersPhotos = getPhotos();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Booth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
            color: #212529; /* Dark text */
        }
        .card {
            border-color: #007bff; /* Primary blue border */
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .card-body {
            background-color: #fff;
        }
        .card-footer {
            background-color: #007bff;
            color: white;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-info:hover {
            background-color: #117a8b;
            border-color: #117a8b;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #1e7e34;
            border-color: #1e7e34;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            color: #212529;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .delete-button {
            position: absolute;
            top: 5px;
            left: 5px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 0.8rem;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .ratio:hover .delete-button {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container min-vh-100 d-flex align-items-center">
        <div class="row w-100 justify-content-center">
            <div class="col-lg-8">
                <div class="card border-primary shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h2 class="card-title text-center mb-0"><i class="bi bi-camera"></i> Photo Booth</h2>
                    </div>
                    
                    <div class="card-body bg-white">
                        <div class="ratio ratio-16x9 border border-3 border-primary rounded-3 overflow-hidden">
                            <video id="cameraPreview" class="object-fit-cover" autoplay></video>
                        </div>

                        <div class="d-grid gap-2 mt-4" id="photooo">
                            <button onclick="capturePhoto()" class="btn btn-primary btn-lg rounded-pill">
                                <i class="bi bi-camera-fill me-2"></i>Capture Photo
                            </button>
                        </div>

                        <div id="capturedPhotoContainer" class="mt-4 d-none">
                            <div class="border border-3 border-success rounded-3 overflow-hidden">
                                <img id="capturedPhoto" class="img-fluid w-100">
                            </div>
                            
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="mt-3">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                        <input id="photoData" type="hidden" name="photo">
                                        <button class="btn btn-info btn-lg px-5 rounded-pill" type="submit" name="submit">
                                            <i class="bi bi-upload me-2"></i>Upload
                                        </button>
                                        <button onclick="savePhoto()" class="btn btn-success btn-lg px-5 rounded-pill">
                                            <i class="bi bi-save me-2"></i>Download
                                        </button>
                                        <button onclick="retakePhoto()" type="button" class="btn btn-warning btn-lg px-5 rounded-pill">
                                            <i class="bi bi-arrow-repeat me-2"></i>Retake
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-primary d-flex justify-content-between align-items-center">
                        <a href="profile.php" class="text-white" style="text-decoration: none;"><img src="https://picsum.photos/32"> <?php echo $_SESSION['username']; ?></a>
                        <a class="btn btn-danger btn-sm rounded-pill" href="logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container min-vh-100 d-flex align-items-center">
        <div class="row w-100 justify-content-center g-4">
            <div class="col-lg-6">
                <div class="card border-info shadow-lg h-100">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title text-center mb-0"><i class="bi bi-images"></i> My Photos</h3>
                    </div>
                    
                    <div class="card-body bg-white p-2" style="height: 70vh; overflow-y: auto;">
                        <div class="row g-2">
                            <?php if (!empty($userPhotos)): ?>
                                <?php foreach ($userPhotos as $photo): ?>
                                    <div class="col-6 col-md-4 col-lg-6">
                                        <div class="ratio ratio-1x1 bg-light">
                                            <img src="<?= $photo['image_data'] ?>" 
                                                alt="My photo" 
                                                class="object-fit-cover"
                                                loading="lazy">
                                            <form method="post">
                                                <input type="hidden" name="photo_id" value="<?= $photo['photo_id'] ?>">
                                                <button type="submit" class="delete-button" name="deleteBtn">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12 text-center text-secondary py-4">
                                    <i class="bi bi-image-fill display-5"></i>
                                    <p class="mt-2 mb-0">No photos found</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-footer bg-info text-center small">
                        <span class="text-white">
                            <?= count($userPhotos) ?> photo<?= count($userPhotos) !== 1 ? 's' : '' ?> stored
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-warning shadow-lg h-100">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="card-title text-center mb-0"><i class="bi bi-people-fill"></i> Others' Photos</h3>
                    </div>
                    
                    <div class="card-body bg-white p-2" style="height: 70vh; overflow-y: auto;">
                        <div class="row g-2">
                            <?php if (!empty($othersPhotos)): ?>
                                <?php foreach ($othersPhotos as $photo): ?>
                                    <div class="col-6 col-md-4 col-lg-6">
                                        <div class="ratio ratio-1x1 bg-light position-relative">
                                            <img src="<?= $photo['image_data'] ?>" 
                                                alt="Others' photo" 
                                                class="object-fit-cover"
                                                loading="lazy">
                                            <div class="position-absolute bottom-0 start-0 end-0 p-2 text-truncate" 
                                                style="background: rgba(0, 0, 0, 0.6);">
                                                <small class="text-white">
                                                    <i class="bi bi-person-fill"></i>
                                                    <?= htmlspecialchars($photo['username']) ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12 text-center text-secondary py-4">
                                    <i class="bi bi-image-fill display-5"></i>
                                    <p class="mt-2 mb-0">No public photos found</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-footer bg-warning text-center small">
                        <span class="text-dark">
                            <?= count($othersPhotos) ?> public photo<?= count($othersPhotos) !== 1 ? 's' : '' ?> available
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const video = document.getElementById('cameraPreview');
        const photooo = document.getElementById('photooo');
        const capturedPhoto = document.getElementById('capturedPhoto');
        const photoContainer = document.getElementById('capturedPhotoContainer');

        // Access camera
        navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error('Error accessing camera:', err);
                alert('Error accessing camera. Please check permissions.');
            });

        function capturePhoto() {
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
        
            const dataURL = canvas.toDataURL('image/jpeg');
            document.getElementById('photoData').value = dataURL;
            capturedPhoto.src = dataURL;
            photooo.classList.add('d-none')
            photoContainer.classList.remove('d-none');
            video.parentElement.classList.add('d-none');
        }

        function retakePhoto() {
            photooo.classList.remove('d-none')
            photoContainer.classList.add('d-none');
            video.parentElement.classList.remove('d-none');
        }

        function savePhoto() {
            const link = document.createElement('a');
            link.download = `photo_${Date.now()}.png`;
            link.href = capturedPhoto.src;
            link.click();
            alert('Photo downloaded!');
            retakePhoto();
        }

        window.addEventListener('beforeunload', () => {
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>