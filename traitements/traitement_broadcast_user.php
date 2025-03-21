<?php
include_once __DIR__ . "/../loadenv.php";
?>
<?php
if(!isset($_SESSION)){
  session_start();
}

$message = $_POST['message'];
if (isset($_SESSION["Id_Uti"]) && isset($message)) {
  
  $message = "'".$message."'";
  $db->query('CALL broadcast_Utilisateur(?, ?);', 'is', [$_SESSION["Id_Uti"], $message]);
  header("Location: ../messagerie.php");

} else {

  echo "error";
  echo $message;
  var_dump(isset($_SESSION["Id_Uti"]));
  var_dump(isset($message));

}

?>