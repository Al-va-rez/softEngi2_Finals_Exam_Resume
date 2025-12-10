<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow-lg" style="max-width: 500px; width: 100%;" x-data="registerComponent()">
    <div class="card-body">
      <h3 class="card-title text-center mb-4">Create Account</h3>
      <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" x-model="username" required>
      </div>
      <div class="form-group">
        <label>First Name</label>
        <input type="text" class="form-control" x-model="firstname" required>
      </div>
      <div class="form-group">
        <label>Last Name</label>
        <input type="text" class="form-control" x-model="lastname" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" x-model="password" required>
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" class="form-control" x-model="confirm_password" required>
      </div>
      <button class="btn btn-success btn-block" @click="register">Register</button>
      <p class="text-center mt-3">
        Already have an account? <a href="login.php">Login here</a>
      </p>
    </div>
  </div>
</div>

<script>
function registerComponent() {
  return {
    username: '',
    firstname: '',
    lastname: '',
    password: '',
    confirm_password: '',
    register() {
      fetch('backend/crud_index.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          action: 'register',
          username: this.username,
          firstname: this.firstname,
          lastname: this.lastname,
          password: this.password,
          confirm_password: this.confirm_password
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          Swal.fire({
            icon: 'success',
            title: 'Registration successful!',
            text: 'Redirecting...',
            timer: 1500,
            timerProgressBar: true,
            showConfirmButton: false
          }).then(() => window.location.href = 'login.php');
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