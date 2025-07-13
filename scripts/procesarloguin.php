<?php
require 'coneccion.php';
    $nus=$_POST['username'];
    $pas=$_POST['password'];
    $sql = "SELECT dni, pas FROM usuarios WHERE dni = :dni";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dni', $nus);
    $stmt->execute();
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($pas, $usuario['pas'])) {
        
        header("Location: dashboard.php");
        exit;
    } else {
        //redirect to login page with error
        header("Location: ../index.php?error=1");
    }

    
?>