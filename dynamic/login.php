<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow-lg" style="max-width: 400px; width: 100%;" x-data="loginComponent()">
    <div class="card-body">
      <h3 class="card-title text-center mb-4">Welcome</h3>
      <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" x-model="username" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" x-model="password" required>
      </div>
      <button class="btn btn-primary btn-block" @click="login">Login</button>
      <p class="text-center mt-3">
        Don’t have an account? <a href="register.php">Sign up</a>
      </p>
      <p class="text-center mt-3">
        Go back to homepage by clicking <a href="../index.html">here</a>
      </p>
    </div>
  </div>
</div>

<script>
function loginComponent() {
  return {
    username: '',
    password: '',
    login() {
      fetch('backend/crud_index.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          action: 'login',
          username: this.username,
          password: this.password
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          Swal.fire({
            icon: 'success',
            title: 'Login successful!',
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