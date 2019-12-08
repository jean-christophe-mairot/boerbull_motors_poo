<?php
//controlleur mettre en relation le model et la vue 

//appel du model
//require_once 'model/user/booking/UserBookingFormModel.php';
require_once 'model/user/UserModel.php';
require_once 'model/panier/NewPanierModel.php';
require_once 'model/oneCar/OneCarModel.php';


//appel de la session
require_once 'aSession/UserSession.php';

//appel d'un fichier dans la librairie
require_once 'library/Tools.php';


class UserBookingFormController{

    private $userSession;
    private $panierModel;
    private $UserModel;
    private $oneCarModel;

    public function __construct()
    {
        // instance de session et de model
        $this->userSession = new UserSession();
        $this->panierModel = new PanierModel();
        $this->oneCarModel = new OnecarModel();
        //$this->UserBookingFormModel = new UserBookingFormModel();
        $this->UserModel = new UserModel();
    }


    // en $_GET
    //A partir du routeur , bookingFormView() appelera les différente function 
    public function bookingFormView(){
        // On ne peut pas réserver sans être connecté !
        // sinon l'utilisateur sera renvoyer vers la page de connexion
        if(!$this->userSession->isAuthenticatedUser()){
            redirect('index.php?action=user&action2=loginForm');
        }

        $session = array_keys($_SESSION['panier']);//recupère le ou les clé qui son dans la session panier

        //$panierModel = new PanierModel();//instance de panier model
        $panier = $this->panierModel->PanierView($session); //appel de la fonction panierVie(), puis on le met dans la variable $panier
        $prix_total_TTC = $this->panierModel->prixTTC($session);

    
    
        if(!empty($session)){
            //appel de la fontion du model
            $oneCar = $this->oneCarModel->OneCarBooking($session);
        }else {
            $oneCar = '';
        }

        //appel de la vue
        require_once 'www/templates/user/booking/BookingFormView.phtml';
    }




    //en POST
    //A partir du routeur , il appelera notre function userBookingForm()
    public function userBookingForm(){

        // On ne peut pas réserver sans être connecté !
        // sinon l'utilisateur sera renvoyer vers la page de connexion
        if(!$this->userSession->isAuthenticatedUser()){
            redirect('index.php?action=user&action2=loginForm');
        }

        if(!empty($_POST)){
            if(array_key_exists('id',$_POST) && isset($_POST['id']) && ctype_digit($_POST['id'])){ 
                if(array_key_exists('user_i',$_POST) && isset($_POST['user_i']) && ctype_digit($_POST['user_i'])){ 
                    if(array_key_exists('numberOfSeats',$_POST) && isset($_POST['numberOfSeats']) && ctype_digit($_POST['numberOfSeats'])){ 
                        if(array_key_exists('datetimepicker',$_POST) && isset($_POST['datetimepicker'])){  
                            if(array_key_exists('datetimepicker2',$_POST) && isset($_POST['datetimepicker2'])){ 

                                //récupération du compte client de l'utilisateur connecté
                                //$userId = getUserId();
                                $userId = $_POST['user_i'];

                                //récupération id de ou des voitures selectionner
                                $car_id = $_POST['id'];


                                //Récupéré la date de debut jusqu'a 10 caractères, EX : 2019-06-21
                                $dateDebut = new DateTime(date(substr($_POST['datetimepicker'], 0, 10)));

                                //récupéré les heures qui s'ajoutera après la date et s'affichera au 11èmes caractères 
                                $hourDebut = substr($_POST['datetimepicker'],11);

                            


                                //Récupéré la date de fin jusqu'a 10 caractères, EX : 2019-06-21
                                $dateFin = new DateTime(date(substr($_POST['datetimepicker2'], 0, 10)));

                                //récupéré les heures qui s'ajoutera après la date et s'affichera au 11èmes caractères 
                                $hourFin = substr($_POST['datetimepicker2'],11);



                                //création de la reservation
                                $this->UserModel->addBooking($userId, $dateDebut->format('Y-m-d'), $hourDebut, $dateFin->format('Y-m-d'), $hourFin, $_POST['numberOfSeats'], $car_id);

                            

                                //$panierModel = new PanierModel(); 
                                $this->panierModel->deleteAll();


                                //redirection vers la page d'accueil
                                redirect('index.php');
                            }
                        }
                    }
                }
            }
            redirect('index.php?action=user&action2=bookingForm&id='.  $_POST['id'] );
        }


        //redirection vers la page d'accueil
        redirect('index.php');
    }
}