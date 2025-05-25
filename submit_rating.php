<?php 
declare(strict_types=1); 
header('Content-Type: application/json');
require_once 'config.php'; // اتصال mysqli هنا

// تفعيل عرض الأخطاء للديباغ
error_reporting(E_ALL);
ini_set('display_errors', 1);

function sendResponse(bool $success, string $message, array $additionalData = []) {
    $response = ['success' => $success, 'message' => $message] + $additionalData;
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Méthode non autorisée');
}

// تأكد من الاتصال
if (!isset($conn) || !($conn instanceof mysqli)) {
    sendResponse(false, 'Erreur de connexion à la base de données');
}

$json = file_get_contents('php://input');
if ($json === false) {
    sendResponse(false, 'Erreur de lecture des données');
}

$data = json_decode($json, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    sendResponse(false, 'Données JSON invalides: ' . json_last_error_msg());
}

$requiredFields = ['produit_id', 'utilisateur_id', 'note'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field])) {
        sendResponse(false, "Champ manquant: $field");
    }
}

$produit_id = filter_var($data['produit_id'], FILTER_VALIDATE_INT);
$utilisateur_id = filter_var($data['utilisateur_id'], FILTER_VALIDATE_INT);
$note = filter_var($data['note'], FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1, 'max_range' => 5]
]);
$commentaire = isset($data['commentaire']) ? $conn->real_escape_string($data['commentaire']) : '';
$date_avis = isset($data['date_avis']) ? $conn->real_escape_string($data['date_avis']) : date('Y-m-d H:i:s');

if ($produit_id === false || $produit_id <= 0) {
    sendResponse(false, 'ID produit invalide');
}
if ($utilisateur_id === false || $utilisateur_id <= 0) {
    sendResponse(false, 'ID utilisateur invalide');
}
if ($note === false) {
    sendResponse(false, 'Note invalide (doit être entre 1 et 5)');
}

$conn->begin_transaction();

try {
    // تحقق إذا المستخدم سبق وقيّم المنتج
    $stmt = $conn->prepare("SELECT id FROM avis WHERE produit_id = ? AND utilisateur_id = ?");
    if (!$stmt) throw new Exception('Erreur préparation requête SELECT: ' . $conn->error);
    $stmt->bind_param("ii", $produit_id, $utilisateur_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // تحديث التقييم
        $stmt->close();
        $updateStmt = $conn->prepare("UPDATE avis SET note = ?, commentaire = ?, date_avis = ? WHERE produit_id = ? AND utilisateur_id = ?");
        if (!$updateStmt) throw new Exception('Erreur préparation requête UPDATE: ' . $conn->error);
        $updateStmt->bind_param("issii", $note, $commentaire, $date_avis, $produit_id, $utilisateur_id);
        if (!$updateStmt->execute()) throw new Exception('Échec de la mise à jour de l\'évaluation: ' . $updateStmt->error);
        $updateStmt->close();
        $action = 'updated';
    } else {
        // إدخال تقييم جديد
        $stmt->close();
        $insertStmt = $conn->prepare("INSERT INTO avis (produit_id, utilisateur_id, note, commentaire, date_avis) VALUES (?, ?, ?, ?, ?)");
        if (!$insertStmt) throw new Exception('Erreur préparation requête INSERT: ' . $conn->error);
        $insertStmt->bind_param("iiiss", $produit_id, $utilisateur_id, $note, $commentaire, $date_avis);
        if (!$insertStmt->execute()) throw new Exception('Échec de l\'insertion de l\'évaluation: ' . $insertStmt->error);
        $insertStmt->close();
        $action = 'created';
    }

    $conn->commit();

    sendResponse(true, 'Évaluation enregistrée avec succès', [
        'action' => $action,
        'produit_id' => $produit_id,
        'note' => $note
    ]);

}catch (Exception $e) {
    $conn->rollback();
    sendResponse(false, 'Erreur: ' . $e->getMessage());
}
