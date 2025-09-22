<?php
session_start();
require_once 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "يرجى ملء جميع الحقول";
    } else {
        $sql = "SELECT id, password FROM user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                // لا نستخدم header() مباشرة، بل نعتمد على JavaScript للتوجيه بعد SweetAlert2
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم تسجيل الدخول',
                            text: 'مرحبًا! سيتم توجيهك إلى لوحة التحكم...',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'dashboard.php';
                        });
                    });
                </script>";
            } else {
                $error = "كلمة المرور غير صحيحة";
            }
        } else {
            $error = "البريد الإلكتروني غير مسجل";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, pink, #0000FF, pink, purple);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .custom-card {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            max-width: 600px;
            width: 100%;
            min-height: 500px;
        }
        img {
            max-width: 200px;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="card p-5 shadow-lg custom-card mx-auto rounded-3">
        <div class="text-center">
            <h2 class="fs-4">StickyMicky</h2>
            <img src="assets/images/17576035.png" class="w-50 mx-auto" alt="StickyMicky Logo">
        </div>
        <h2 class="text-center mb-4 fs-3">تسجيل الدخول</h2>

        <?php if (!empty($error)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: '<?php echo $error; ?>'
                });
            });
        </script>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label for="email" class="form-label fs-5">البريد الإلكتروني</label>
                <input type="email" class="form-control" name="email" id="email" required placeholder="أدخل بريدك الإلكتروني">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label fs-5">كلمة المرور</label>
                <input type="password" class="form-control" name="password" id="password" required placeholder="أدخل كلمة المرور">
            </div>
            <button type="submit" class="btn btn-outline-light w-100">تسجيل الدخول</button>
        </form>
        <p class="text-center mt-4 fs-6">ليس لديك حساب؟ <a href="#" class="text-primary">إنشاء حساب</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>