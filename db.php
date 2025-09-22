<?php
$servername = "localhost"; // مضيف قاعدة البيانات
$username = "root"; // اسم مستخدم MySQL
$password = ""; // كلمة المرور
$dbname = "my_notes"; // اسم قاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname); // يتصل بقاعدة البيانات
if ($conn->connect_error) { // يتحقق إذا فيه خطأ في الاتصال
    die("فشل الاتصال: " . $conn->connect_error); // يوقف ويعرض الخطأ
}
mysqli_set_charset($conn, "utf8"); // يدعم العربية
?>