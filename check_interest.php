<?php
$db = new PDO('sqlite:database/database.sqlite');
$count = $db->query('SELECT COUNT(*) FROM interest_logs')->fetchColumn();
$sum = $db->query("SELECT IFNULL(SUM(interest_amount),0) FROM interest_logs WHERE status='processed'")->fetchColumn();
$latest = $db->query("SELECT calculation_date, created_at, interest_amount FROM interest_logs ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$jobs = $db->query("SELECT COUNT(*) FROM jobs")->fetchColumn();

echo "interest_logs: {$count}\n";
echo "sum_processed: {$sum}\n";
echo "jobs_pending: {$jobs}\n";
if ($latest) {
    echo "latest: {$latest['calculation_date']} | {$latest['created_at']} | {$latest['interest_amount']}\n";
}
