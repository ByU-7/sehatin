<?php
// process_quiz.php
require_once 'config/database.php';
require_once 'includes/auth_check.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $answers = $_POST['answers']; // Array [question_id => user_value]
    $user_id = $_SESSION['user_id'];
    
    $total_score = 0;
    $max_per_q = 5;
    $total_questions = count($answers);

    try {
        $pdo->beginTransaction();

        // TAHAP 1: Hitung Skor Sambil Menyiapkan Data Detail
        $details_to_save = [];
        foreach ($answers as $q_id => $val) {
            $stmt = $pdo->prepare("SELECT question_type FROM questions WHERE id = ?");
            $stmt->execute([$q_id]);
            $q_data = $stmt->fetch();

            $raw_val = (int)$val;
            $final_score = ($q_data['question_type'] === 'negatif') ? (6 - $raw_val) : $raw_val;
            
            $total_score += $final_score;
            $details_to_save[] = ['q_id' => $q_id, 'score' => $final_score];
        }

        // TAHAP 2: Hitung Persentase & Klasifikasi
        $max_total = $total_questions * $max_per_q;
        $percentage = ($total_score / $max_total) * 100;

        if ($percentage >= 80) $class = 'Sangat Baik';
        elseif ($percentage >= 60) $class = 'Baik';
        elseif ($percentage >= 40) $class = 'Cukup';
        elseif ($percentage >= 20) $class = 'Kurang';
        else $class = 'Sangat Kurang';

        // TAHAP 3: Simpan Hasil Utama
        $stmt_res = $pdo->prepare("INSERT INTO results (user_id, category_id, total_score, result_classification, percentage) VALUES (?, ?, ?, ?, ?)");
        $stmt_res->execute([$user_id, $category_id, $total_score, $class, $percentage]);
        $res_id = $pdo->lastInsertId();

        // TAHAP 4: Simpan Detail Jawaban
        foreach ($details_to_save as $det) {
            $stmt_det = $pdo->prepare("INSERT INTO result_details (result_id, question_id, option_id, score) VALUES (?, ?, NULL, ?)");
            $stmt_det->execute([$res_id, $det['q_id'], $det['score']]);
        }

        $pdo->commit();
        header("Location: result_view.php?id=" . $res_id);
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack();
        die("System Error: " . $e->getMessage());
    }
}