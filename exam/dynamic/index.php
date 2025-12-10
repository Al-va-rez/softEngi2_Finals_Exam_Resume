<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2" defer></script>

    <!-- Bootstrap 4 JS + dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- HERO SECTION -->
    <section class="container-fluid py-5" id="hero" x-data="content.aboutMe ? content.aboutMe : {}">

        <div class="container">


            <!-- Main Content -->
            <div class="row">

                <!-- LEFT SIDE TEXT -->
                <div x-data="logoutComponent()">
                    <button class="btn btn-danger" @click="logout">Logout</button>
                </div>

                <div class="col-md-6 py-5">
                    <h1 class="font-weight-bold display-4 mb-2">I'm Viron Edson M. Alvarez</h1>
                    <h2 class="display-4 mb-4">Software Engineer</h2>
                </div>

                <!-- RIGHT SIDE PHOTO -->
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    <img :src="'../images/Formal Profile Pics.JPG'"
                        class="img-fluid rounded"
                        style="max-height:450px; object-fit:cover;">
                </div>
            </div>


            <!-- Bottom Contact Row -->
            <div class="text-center mt-5">

                <div class="d-flex justify-content-center align-items-center">

                    <div class="mx-3">
                        <i class="fab fa-instagram"> @instagram</i>
                    </div>

                    <div class="mx-3">
                        <i class="fas fa-phone"> +63123 456 7890</i>
                    </div>

                    <div class="mx-3">
                        <i class="fas fa-globe"> mywebsite.com</i>
                    </div>

                </div>

                <!-- Social Icons -->
                <div class="mt-4">
                    <i class="fab fa-dribbble mx-3" style="font-size:30px;"></i>
                    <i class="fab fa-linkedin mx-3" style="font-size:30px;"></i>
                    <i class="fab fa-facebook mx-3" style="font-size:30px;"></i>
                </div>

            </div>

        </div>
    </section>
    
    <?php include 'aboutMe.php'; ?>
    <?php include 'projects.php'; ?>
    <?php include 'education_certificates.php'; ?>
    <?php include 'techSkills.php'; ?>

    <script>
        function logoutComponent() {
            return {
                logout() {
                    fetch('backend/crud_index.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'logout' })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Logout successful!',
                            text: 'Redirecting...',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(() => window.location.href = 'index.php');
                        } else {
                        Swal.fire({
                            icon: data.status,
                            title: 'Oops...',
                            text: data.message,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        }
                    });
                }
            }
        }
    </script>
</body>
</html>