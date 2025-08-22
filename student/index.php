<?php
$uploadDir = __DIR__ . '/../projects/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$msg = '';
if (!empty($_FILES['file'])) {
    $name = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    
    // Extensions autorisées pour le lab
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    $allowed = ['gz','zip','php3','php4','php5','php7','phtml','pht','gz'];
    
    if(in_array($ext, $allowed)){
        move_uploaded_file($tmp, $uploadDir.$name);
        $msg = "<span class='success'>✅ File uploaded: $name</span>";
    } else {
        $msg = "<span class='error'>❌ Extension not allowed!</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Upload</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #0f172a;
      color: #e2e8f0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .container {
      background: #1e293b;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.4);
      width: 420px;
      text-align: center;
    }
    h2 {
      margin-bottom: 20px;
      color: #38bdf8;
    }
    input[type="file"] {
      margin: 10px 0;
    }
    input[type="submit"] {
      background: #38bdf8;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }
    input[type="submit"]:hover {
      background: #0ea5e9;
    }
    p {
      margin-top: 15px;
    }
    .success { color: #22c55e; }
    .error { color: #ef4444; }
    .note {
      font-size: 0.9em;
      color: #94a3b8;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>📂 Upload your project</h2>
    <form method="POST" enctype="multipart/form-data">
      <input type="file" name="file"/><br>
      <input type="submit" value="Upload"/>
    </form>
    <p><?php echo $msg; ?></p>
    <p class="note">⚠️ Veuillez mettre vos projets sous format <b>.zip</b> ou <b>.gz</b>.</p>
  </div>
</body>
</html>
