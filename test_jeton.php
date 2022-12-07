<?php
include "vendor/autoload.php";

use App\Modele\Modele_Jeton as Modele_Jeton;
use App\Modele\Modele_Salarie as Modele_Salarie;

$octetsAleatoires = openssl_random_pseudo_bytes (256) ;
$valeur = sodium_bin2base64($octetsAleatoires, SODIUM_BASE64_VARIANT_ORIGINAL);

$utilisateur = Modele_Salarie::Salarie_Select_byMail('userZoomBox@userZoomBox.com');
$idJeton = Modele_Jeton::Jeton_Creation($valeur, $utilisateur['idSalarie'], 1);

$jetonRechercher = Modele_Jeton::Jeton_Rechercher_Par_Valeur($valeur);


if($idJeton == $jetonRechercher["id"])
{
    // On a retrouvé le jeton par rapport à sa valeur :)
    // On check si l'utilisateur est le même :)
    if($jetonRechercher['idUtilisateur'] == $utilisateur['idSalarie']) {
        // L'utilisateur est le bon
    }
    else {
        echo "Problème...1";
    }

    if($jetonRechercher['codeAction'] == 1) {
        // Le code action est bon
        Modele_Jeton::Jeton_Delete_Par_Id($idJeton);
        $verifSupression = Modele_Jeton::Jeton_Rechercher_Par_Valeur($valeur);
        if(!$verifSupression) {
            // Le jeton à été supprimé
        }
        else {
            echo "Problème...2";
        }
    }
    else {
        echo "Problème...3";
    }
}
else {
    echo "Problème...4";
}

