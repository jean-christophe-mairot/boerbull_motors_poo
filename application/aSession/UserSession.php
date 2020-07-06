<?php

// forcer les erreurs si on a pas bien typé nos class
declare(strict_types=1);

//appel du model
include_once 'model/panier/NewPanierModel.php';

class UserSession{

    public function __construct(){

        // Démarrage du module PHP de gestion des sessions.
        /*si le statut de la session courante et que les sessions sont activées, mais qu'aucune n'existe. 
        on demarre une session*/
        if (session_status() === PHP_SESSION_NONE) {
            session_start();//demarre
        }

        if(!$this->isAuthenticatedUser()){
            $ClassPanier = new PanierModel(); 
            $ClassPanier->deleteAll();
        }
    }    

    /**
     * Construction de la session utilisateur.
     *
     * @param int $userId
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @return void
     */
    public function create(int $userId, string $firstName, string $lastName, string $email) : void
    {
        $_SESSION['user']['id']         = $userId;
        $_SESSION['user']['first_name'] = $firstName;
        $_SESSION['user']['last_name']  = $lastName;
        $_SESSION['user']['mail']       = $email;
    }

    /**
     * Destruction de l'ensemble de la session.
     * @return void
     */
    public function userDestroy() : void
    {
        unset($_SESSION['user']);
    }

    /**
     * Afficher le mail
     * @return void
     */
    public function getEmail() : ?array
    {
        if (!$this->isAuthenticatedUser()) {
            return null;
        }
        //s'il est connecté on peut affiché la session , $_SESSION['user']['mail']
        return $_SESSION['user']['mail'];
    }

    /**
     * Afficher le prénom
     * @return void
     */
    public function getFirstName() : ?array
    {
        //if (!$this->isAuthenticated()) = si le user n'est pas connècté, alors ne rien renvoyé  
        if (!$this->isAuthenticatedUser()) {
            return null;
        }
        //s'il est connecté on peut affiché la session , $_SESSION['user']['first_name']
        return $_SESSION['user']['first_name'];
    }


    //afficher le nom
    public function getLastName(){

        if (!$this->isAuthenticatedUser()) {
            return null;
        }
        //s'il est connecté on peut affiché la session , $_SESSION['user']['last_name']
        return $_SESSION['user']['last_name'];
    }



    //afficher le id
    public function getUserId(){
        if (!$this->isAuthenticatedUser()) {
            return null;
        }
        //s'il est connecté on peut affiché la session , $_SESSION['user']['id']
        return $_SESSION['user']['id'];
    }




    //on verifie si la session user existe et qu'il y a du contenu dedans
    public function isAuthenticatedUser(){
        if(array_key_exists('user', $_SESSION)) {
            if (!empty($_SESSION['user']) && isset($_SESSION['user'])) {
                return true;
            }
        }
        return false;
    }
}