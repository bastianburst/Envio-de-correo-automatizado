<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>PHPMailer -  @Sebastián</title>
</head>
<body>
 
<?php

// Libreria PHPMailer
require '../PHPMailer/class.smtp.php';
require '../PHPMailer/PHPMailerAutoload.php';

// Creamos una nueva instancia
$mail = new PHPMailer(true);
 

// Activamos el servicio SMTP
$mail->isSMTP();
// Activamos / Desactivamos el "debug" de SMTP 
// 0 = Apagado 
// 1 = Mensaje de Cliente 
// 2 = Mensaje de Cliente y Servidor 

$mail->Mailer = "smtp";


$mail->Hostname= 'smtp.gmail.com';

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);


$mail->Helo = 'smtp.gmail.com';


$mail->SMTPDebug = 4; 
 

$mail->Debugoutput = 'html'; 


//Esto se comenta si en la base de datos ya esta en UTF-8
$mail->CharSet = 'UTF-8'; 

$mail->Encoding = 'quoted-printable';
 

$mail->Host = 'smtp.gmail.com'; 
 

$mail->Port = 587; 
 

$mail->SMTPSecure = 'tls'; 
 

$mail->SMTPAuth = true;


 $mail->isHtml(true);


$mail->Username = 'tucorreo@gmail.com'; 
 

$mail->Password = 'password'; 
 
//conectarse  a base de datos para enviar datos por correo
$db = new mysqli('127.0.0.1', 'root', '1234', 'mydb'); 
 
if ($db->connect_errno > 0) { 
    die('Imposible conectar [' . $db->connect_error . ']'); 
}

$date =  date("d/m/Y");
echo ' ' . $date;
$result = $db->query("SELECT * FROM ingresos WHERE DATE(fecha) = curdate();");

//se arma el cuerpo del mensaje, esto es una tabla
 $body="<div style='text-align:center;'>
 <div style='text-align:center;'>
 <h3><img src='../media/myAvatar.png' style='display: inline-block;'></h3>
 </div>
 <br></br>
 <h2 style='color:  #ddd; font-family:sans-serif;'>Reporte</h2>
 <p style='font-family: sans-serif; font-size: 17px;'>Este es el listado de clientes que ingresaron el ". $date .".</p>
<table style='padding: .2em; font-family:sans-serif; display:inline-block;'>
  <thead style='background-color: #495aa9; color:white;'>
    <th style='padding: .5em; border:1px solid #ddd;'>Nombres</th>
    <th style='padding: .5em; border:1px solid  #ddd;'>Documento</th>
    <th style='padding: .5em; border:1px solid  #ddd;'>Tel</th>
  </thead>
  <tbody>";

while ($rows=mysqli_fetch_array($result)){
   $body .="<tr style='color: gray;'>
        <td style='padding: .5em; border:1px solid #ddd;'>" . $rows['nombres'] . ' ' . $rows['apellidos'] . "</td> 
        <td style='padding: .5em; border:1px solid #ddd;'>" . $rows['idingreso'] . "</td> 
        <td style='padding: .5em; border:1px solid #ddd;'>" . $rows['telefonos'] . "</td>   
      </tr></tbody>";
}
$mail->Body = $body.="</table>
<div width='500' height='94' style='font-size:18px; font-weight: bold; font-family:sans-serif; padding-top:2em;'>Sebastian Gallego <br>
      Sitio: <a href='https://github.com/SEBASCAMPEON' target='_blanck'>https://github.com/SEBASCAMPEON</a></div>
       <p style='font-size:14px; font-weight:bold; color:gray; display:block; padding-top: 1em; font-family:sans-serif;'>Por favor no responder este mensaje</p>
       <p style='font-size:14px; font-weight:bold; color:#2c3c8c; display:block; padding-top: 1em; font-family:sans-serif;' title='por Sebastian Gallego, Analista y Desarrollador de software'>@Bastian</p></div>";
//esto es por si la consulta de la base de datos no retorna nada
$bodyalter="<h2 style='font-family:sans-serif;'>El día de hoy no hubo registros<h2> 
<div width='500' height='94' style='font-size:18px; font-weight: bold; font-family:sans-serif;'>Sebastian Gallego <br>
      Sitio: <a href='https://github.com/SEBASCAMPEON' target='_blanck'>https://github.com/SEBASCAMPEON</a></div>
<p style='font-size:14px; font-weight:bold; color:gray; display:block; padding-top:1em; font-family:sans-serif;'>Por favor no responder este mensaje</p>
<p style='font-size:14px; font-weight:bold; color:#2c3c8c; display:block; padding-top: 1em; font-family:sans-serif;' title='Sebastian Gallego, Analista y Desarrollador de software'>@Bastian</p>";
$bodyalter .="";

$after = $result->num_rows;
if($after == 0){

  $mail->msgHTML($bodyalter); 
}else{
 
   $mail->msgHTML($body); 
}


 
 //destinatarios, esto podria ser otra consulta y hacer un ciclo for para que se envie a una lista de la base de datos
    $mail->setFrom('tucorreo@gmail.com', 'Hola'); 
    $mail->addAddress('correodestinatario@hotmail.com', "Sebastian Gallego"); 


                           
     




 
 
    $mail->Subject = 'Listado de clientes que ingresaron el dia de hoy'; 
 
    // La mejor forma de enviar un correo, es creando un HTML e insertandolo de la siguiente forma, PHPMailer permite insertar, imagenes, css, etc. (No se recomienda el uso de Javascript) 
 
  
   // $mail->msgHTML(file_get_contents('../Week/week.php'), dirname(__FILE__));

    $mail->send(); 


 
    // Borramos el destinatario, de esta forma nuestros clientes no ven los correos de las otras personas y parece que fuera un único correo para ellos. 
    $mail->ClearAddresses();


    if($mail->Send())
    {     
      return $body; 
    }else
    {
      return false;
    }

?>
</body>
</html>