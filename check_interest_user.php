<?php
$db = new PDO('sqlite:database/database.sqlite');
$user = $db->query('SELECT id, name, username FROM users ORDER BY id DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo "no users\n";
    exit;
}

$investments = $db->query('SELECT id, status, amount, daily_interest, start_date, maturity_date FROM investments WHERE user_id=' . (int) $user['id'])->fetchAll(PDO::FETCH_ASSOC);
$active = array_values(array_filter($investments, fn($i) => $i['status'] === 'active'));

$logCount = $db->query('SELECT COUNT(*) FROM interest_logs WHERE user_id=' . (int) $user['id'])->fetchColumn();
$latestLog = $db->query('SELECT calculation_date, created_at, interest_amount FROM interest_logs WHERE user_id=' . (int) $user['id'] . ' ORDER BY id DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
$jobs = $db->query("SELECT COUNT(*) FROM jobs")->fetchColumn();

print_r($user);
print_r($active);
echo "interest_logs_for_user: {$logCount}\n";
if ($latestLog) {
    echo "latest_log: {$latestLog['calculation_date']} | {$latestLog['created_at']} | {$latestLog['interest_amount']}\n";
}
echo "jobs_pending: {$jobs}\n";
