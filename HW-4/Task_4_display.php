<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['surname'] = $_POST['surname'];
    $_SESSION['age'] = $_POST['age'];
}
?>
<!DOCTYPE html>
<html>
<body>
    <?php if (isset($_SESSION['name']) && isset($_SESSION['surname']) && isset($_SESSION['age'])): ?>
        <h3>Данные:</h3>
        <p>Имя: <?= $_SESSION['name'] ?></p>
        <p>Фамилия: <?= $_SESSION['surname'] ?></p>
        <p>Возраст: <?= $_SESSION['age'] ?></p>
    <?php else: ?>
        <p>Данных нет. <a href="task4_form.php">Заполнить форму</a></p>
    <?php endif; ?>
</body>
</html>