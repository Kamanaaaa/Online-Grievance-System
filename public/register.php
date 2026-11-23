<?php
require '../includes/functions.php'; verify_csrf(); $errors=[];
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $name=trim($_POST['full_name']??''); $email=strtolower(trim($_POST['email']??'')); $password=$_POST['password']??''; $phone=trim($_POST['phone']??'');
 if (mb_strlen($name)<2 || mb_strlen($name)>140) $errors[]='Enter your name (2 to 140 characters).';
 if (!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Enter a valid email address.';
 if (strlen($password)<10) $errors[]='Password must be at least 10 characters.';
 if (!$errors) { try { $q=db()->prepare("INSERT INTO users(role_id,full_name,email,phone,password_hash) SELECT id,?,?,?,? FROM roles WHERE name='citizen'"); $q->execute([$name,$email,$phone,password_hash($password,PASSWORD_DEFAULT)]); flash('Account created. You can now sign in.'); redirect('public/login.php'); } catch(PDOException $ex) { $errors[]=$ex->getCode()==='23000'?'An account already uses that email address.':'Registration could not be completed.'; } }
}
$pageTitle='Register'; require '../includes/header.php'; ?>
<section class="panel form-narrow"><h1>Create a citizen account</h1><?php foreach($errors as $error): ?><p class="error"><?= e($error) ?></p><?php endforeach; ?><form method="post"><?= csrf_field() ?><label for="full_name">Full name</label><input id="full_name" name="full_name" required maxlength="140" value="<?= e($_POST['full_name']??'') ?>"><label for="email">Email</label><input id="email" type="email" name="email" required maxlength="190" value="<?= e($_POST['email']??'') ?>"><label for="phone">Phone (optional)</label><input id="phone" name="phone" maxlength="30"><label for="password">Password (at least 10 characters)</label><input id="password" type="password" name="password" minlength="10" required><button>Create account</button></form></section><?php require '../includes/footer.php'; ?>
