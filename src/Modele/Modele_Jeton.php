<?php

namespace App\Modele;

use App\Utilitaire\Singleton_ConnexionPDO;
use PDO;

class Modele_Jeton
{
    /**
     * @param $valeur
     * @param $idUtilisateur
     * @param $codeAction (1 pour modifier MDP)
     * @return L'id du jeton créer ou false
     */
    static function  Jeton_Creation($valeur, $idUtilisateur, $codeAction)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare(' 
        INSERT INTO `token` ( valeur, codeAction, idUtilisateur, dateFin) 
        VALUE ( :valeur, :codeAction, :idUtilisateur, :dateFin)');
        $requetePreparee->bindParam('valeur', $valeur);
        $requetePreparee->bindParam('codeAction', $codeAction);
        $requetePreparee->bindParam('idUtilisateur', $idUtilisateur);
        $dateTime = new \DateTime('+ 15 minutes');
        $forma = $dateTime->format('Y-m-d h-m-s');
        $requetePreparee->bindParam('dateFin', $forma);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        $requetePreparee = $connexionPDO->prepare('
        SELECT MAX(id) AS IdMax
        FROM `token`');
        $reponse = $requetePreparee->execute();
        $tab = $requetePreparee->fetch(PDO::FETCH_ASSOC);
        return $tab['IdMax'];
    }

    static function Jeton_Rechercher_Par_Valeur($valeur)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare('
        select * 
        from `token`
        where valeur = :valeur');
        $requetePreparee->bindParam('valeur', $valeur);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête

        return $requetePreparee->fetch(PDO::FETCH_ASSOC);
    }

    static function Jeton_Delete_Par_Id($idJeton)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare('
        delete
        from `token`
        where id = :id');
        $requetePreparee->bindParam('id', $idJeton);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
    }
}