<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$product_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// التحقق من ملكية المنتج قبل الحذف
$stmt = $conn->prepare("DELETE FROM produits WHERE id = ? AND vendeur_id = ?");
$stmt->bind_param("ii", $product_id, $user_id);
$stmt->execute();

header("Location: account.php?success=deleted");
exit();
?>