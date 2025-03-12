<?php
include_once __DIR__ . "/../loadenv.php";
error_reporting(E_ALL);
ini_set("display_errors", 1);

function dbConnect()
{
    $utilisateur = $_ENV['DB_USER'];
    $serveur = $_ENV['DB_HOST'];
    $motdepasse = $_ENV['DB_PASS'];
    $basededonnees = $_ENV['DB_NAME'];

    return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees . ';charset=utf8', $utilisateur, $motdepasse);
}

function afficheContacts($id_user)
{
    $bdd = dbConnect();
    $query = $bdd->query(('CALL listeContact(' . $id_user . ');'));
    $contacts = $query->fetchAll(PDO::FETCH_ASSOC);
    if (count($contacts) == 0) {
        echo ($htmlPasDeConversation);
    } else {
        foreach ($contacts as $contact) {
            afficherContact($contact);
        }
    }
}

function afficherContact($contact)
{
    $str = $contact['Prenom_Uti'] . ' ' . $contact['Nom_Uti'];
?>
    <form method="get">
        <input type="submit" value="<?php echo ($str); ?>">
        <input type="hidden" name="Id_Interlocuteur" value="<?php echo ($contact['Id_Uti']) ?>">
    </form>
<?php
}

if (isset($_SESSION['Id_Uti'])) {
    afficheContacts($_SESSION['Id_Uti']);
}



?>