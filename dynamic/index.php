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
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <!-- HERO SECTION -->
    <section class="py-5"
            id="hero"
            style="
                background: linear-gradient(135deg, #ffffff 0%, #dceaff 100%);
                border-radius: 20px;
                border: 1px solid #bcd4ff;
                box-shadow: 0 8px 20px rgba(0,0,0,0.07);
            ">

        <div class="container">

            <div class="row align-items-center">

                <!-- LEFT SIDE TEXT -->
                <div class="col-md-6 py-5">

                    <div x-data="logoutComponent()">
                        <button class="btn btn-danger" @click="logout">Logout</button>
                    </div>

                    <h1 class="font-weight-bold mb-3" style="font-size: 3rem; color: #000;">
                        I'm Viron Edson M. Alvarez
                    </h1>

                    <h2 class="mb-4" style="font-size: 2.2rem; color: #000;">
                        Software Engineer
                    </h2>

                    <p class="mt-3" style="font-size: 1.1rem; color: #000;">
                        Developing for maintainability and functionality
                    </p>
                </div>

                <!-- RIGHT SIDE PHOTO -->
                <div class="col-md-6 d-flex justify-content-center">
                    <div style="
                        width: 380px;
                        height: 380px;
                        border-radius: 20px;
                        background: #ffffff;
                        padding: 15px;
                        border: 1px solid #7fb2ff;
                        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
                    ">
                        <img src="../images/profile.jpg"
                            class="img-fluid rounded"
                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                    </div>
                </div>

            </div>


            <!-- CONTACT ROW -->
            <div class="text-center mt-5">

                <div class="d-flex justify-content-center align-items-center flex-wrap">

                    <div class="mx-3 my-1" style="color: #000; font-weight: bold;">
                        <i class="fab fa-instagram mr-1" style="color:#007bff;"></i> @instagram
                    </div>

                    <div class="mx-3 my-1" style="color: #000; font-weight: bold;">
                        <i class="fas fa-phone mr-1" style="color:#007bff;"></i> +63 123 456 7890
                    </div>

                    <div class="mx-3 my-1" style="color: #000; font-weight: bold;">
                        <i class="fas fa-globe mr-1" style="color:#007bff;"></i> mywebsite.com
                    </div>

                </div>

                <!-- Social Icons -->
                <div class="mt-4">
                    <i class="fab fa-dribbble mx-3" style="font-size:30px; color:#007bff;"></i>
                    <i class="fab fa-linkedin mx-3" style="font-size:30px; color:#007bff;"></i>
                    <i class="fab fa-facebook mx-3" style="font-size:30px; color:#007bff;"></i>
                </div>

            </div>

        </div>
    </section>
    
    <section class="py-5"
        style="background: linear-gradient(135deg, #f0f7ff 0%, #cfe4ff 100%);
                border-radius: 20px;
                border: 1px solid #a5c9ff;
                box-shadow: 0 8px 20px rgba(0,0,0,0.07);">
        <?php include 'aboutMe.php'; ?>
    </section>
    <section class="py-5"
        style="background: linear-gradient(135deg, #f0f7ff 0%, #cfe4ff 100%);
                border-radius: 20px;
                border: 1px solid #a5c9ff;
                box-shadow: 0 8px 20px rgba(0,0,0,0.07);">
        <?php include 'techSkills.php'; ?>
    </section>
    <section class="py-5"
        style="background: linear-gradient(135deg, #f0f7ff 0%, #cfe4ff 100%);
                border-radius: 20px;
                border: 1px solid #a5c9ff;
                box-shadow: 0 8px 20px rgba(0,0,0,0.07);">
        <?php include 'projects.php'; ?>
    </section>
    <section class="py-5"
        style="background: linear-gradient(135deg, #f0f7ff 0%, #cfe4ff 100%);
                border-radius: 20px;
                border: 1px solid #a5c9ff;
                box-shadow: 0 8px 20px rgba(0,0,0,0.07);">
        <?php include 'education_certificates.php'; ?>
    </section>

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