<?php
require_once __DIR__ . '/../config/config.php';

function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $path = ''): string { return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/'); }
function csrf_token(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(32)); }
function csrf_field(): string { return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">'; }
function verify_csrf(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf_token'] ?? '')) {
        http_response_code(403); exit('Invalid form token. Please go back and try again.');
    }
}
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): array
{
    $user = current_user();
    if (!$user) { header('Location: ' . url('public/login.php')); exit; }
    return $user;
}
function require_role(string ...$roles): array
{
    $user = require_login();
    if (!in_array($user['role'], $roles, true)) { http_response_code(403); exit('You are not authorized to view this page.'); }
    return $user;
}
function flash(string $message): void { $_SESSION['flash'] = $message; }
function redirect(string $path): never { header('Location: ' . url($path)); exit; }
function record_history(PDO $pdo, int $grievanceId, ?string $old, string $new, int $userId, string $remarks, bool $public = true): void
{
    $q = $pdo->prepare('INSERT INTO grievance_history (grievance_id, previous_status, new_status, updated_by, remarks, is_public) VALUES (?, ?, ?, ?, ?, ?)');
    $q->execute([$grievanceId, $old, $new, $userId, $remarks, (int)$public]);
}
