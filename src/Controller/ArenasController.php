<?php
namespace App\Controller;
use App\Controller\AppController;
use App\Form\AddFighterForm;
use Cake\Event\Event; 
use Cake\Core\Configure;
use Cake\I18n\Time;

/**
 * Personal Controller
 * User personal interface
 *
 */
class ArenasController extends AppController {

    public function index() {
        $this->loadModel('Surroundings');
        $this->Surroundings->initObstacles();
        configure::write('displayOption', 0);       
    }  
       
    /**
     * Autorisation d'accès à l'accueil sans connexion
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['index']);
        
    }
    public function fighter(){

        $session = $this->request->session();

        $id=$this->Auth->user('id');
        $this->set('player_id',$id);

        $this->loadModel('Fighters');

        if($this->request->is('ajax')) {
            if($selectedfighter=$this->request->getData('currentfighter'))
            {
                // Force le controller à rendre une réponse JSON.
                $this->RequestHandler->renderAs($this, 'json');
                // Définit le type de réponse de la requete AJAX
                $this->response->type('application/json');
                $session->write('selectedfighter', $selectedfighter);
                // Chargement du layout AJAX
                $this->viewBuilder()->layout('ajax');
                // Créer un contexte sites à renvoyer 
                $this->set('return',$session->read('selectedfighter'));
                // Généreration des vues de données
                $this->set('_serialize', ['return']);
            }
        }

        if ($this->request->is('post')) {
            if (!empty($this->request->getData()['submittedimagenewfighter']['name'])) {
                $name = $this->request->getData('name');
                $id_fighter=$this->Fighters->addNewFighter($name, $id);
                $folder = "../webroot/img/";
                $pic = $this->request->getData()['submittedimagenewfighter'];
                $path = $folder . $pic['name'];
                
                move_uploaded_file($pic['tmp_name'], $path);
                rename($path, $folder.$id . '_' .$id_fighter. '.jpg');
            }
            if(!empty($this->request->getData()['submittedimageupdatefighter']['name'])){
                $id_fighter = $this->request->getData('name');
                $folder = "../webroot/img/";
                $pic = $this->request->getData()['submittedimageupdatefighter'];
                $path = $folder . $pic['name'];
                
                unlink($folder.$id . '_' .$id_fighter. '.jpg'); //On supprime l'avatar précédent
                move_uploaded_file($pic['tmp_name'], $path);
                rename($path, $folder.$id . '_' .$id_fighter. '.jpg');
            }
            //Si on augmente une capacité
            if(null!==$this->request->getData('skill'))
            {
                //Type de capacité augmentée
                $skill = $this->request->getData('skill');
                //id du combattant concerné
                $idFighter =$this->request->getData('idFighter');
                //Get fighter courant
                $myfighter = $this->Fighters->infoFighterByName($id, $idFighter);
                //Augmenter la skill du perso du fighter
                $this->Fighters->improveSkill($skill,$myfighter);
            }
            
        }

        if($selectedfighter=$session->read('selectedfighter')){
           $this->set('selectedfighter', $selectedfighter);
        }

        //$this->set('new', $this->Fighters->addNewFighter());
        $this->set('num', $this->Fighters->countFighters($id));
        $this->set('tous', $this->Fighters->getAllFighters($id));
        $this->set('skills', $this->Fighters->displaySkills($id));
        $this->set('best', $this->Fighters->getBestFighter($id));
        //$newfighter = $this->Fighters->newEntity();
        //$this->request->getData($newfighter);
        //$contact = new AddFighterForm();
        
        
    }
    public function sight(){
        //Get player_id courant
        $id=$this->Auth->user('id');
        $this->set('player_id',$id);

        $this->loadModel('Surroundings');
        $this->loadModel('Fighters');
        $this->loadModel('Events');


        //points d'action
        $ptmax=$this->viewVars['PT_ACTION_MAX'];
        $tpsrecup=$this->viewVars['TPS_RECUP'];
        $this->set('ptmax',$ptmax);
        $this->set('tpsrecup',$tpsrecup);

        $folder = "../webroot/img/";

        $session = $this->request->session();
        $selectedfighter = $session->read('selectedfighter');

        //Get fighter courant
        $myfighter = $this->Fighters->infoFighter($id,$selectedfighter);

        if(isset($myfighter))
        {

            $time=$this->Fighters->timeManager($myfighter, $ptmax, $tpsrecup);
            $this->set('time',$time);
            // Si c'est une requête AJAX
            if($this->request->is('ajax')) {
                // Force le controller à rendre une réponse JSON.
                $this->RequestHandler->renderAs($this, 'json');
                // Définit le type de réponse de la requete AJAX
                $this->response->type('application/json');

                if ($data = $this->request->is('post')) {

                    $time=$this->Fighters->timeManager($myfighter, $ptmax, $tpsrecup);
                    $this->set('time',$time);

                    //Déplacement
                    if($dir = $this->request->getData('dir'))
                    {
                        if($this->Fighters->hasEnoughPoints($myfighter, $tpsrecup)){
                            $myfighter = $this->Fighters->move($myfighter, $dir, $tpsrecup);
                            $array = $this->Surroundings->obstacleDisplay($myfighter);
                            $alert = $this->Surroundings->TWaround($myfighter, $array);
                            //$fightersByPos = $this->Fighters->getFighterByPos();
                            $fightersByPos = $this->Fighters->getFighterByPosAndId($myfighter);
                            $isdead = $this->Fighters->isDead($myfighter);

                            if($isdead){
                                unlink($folder.$id . '_' .$myfighter['id']. '.jpg');
                            }
                            $this->set('fbypos', $fightersByPos);
                            // Chargement du layout AJAX
                            $this->viewBuilder()->layout('ajax');
                            // Créer un contexte sites à renvoyer 
                            $this->set('sites',$array);
                            $this->set('alert',$alert);
                            $this->set('isdead',$isdead);
                            // Généreration des vues de données
                            $this->set('_serialize', ['sites','alert','fbypos','tpsrecup','ptmax','time','isdead']);
                        }else{
                            // Chargement du layout AJAX
                            $this->viewBuilder()->layout('ajax');
                            // Créer un contexte sites à renvoyer 
                            $this->set('sites',"ptaction");
                            // Généreration des vues de données
                            $this->set('_serialize', ['sites']);
                        }
                        
                    }//Attaque
                    else if($atta = $this->request->getData('atta'))
                    {
                        if($this->Fighters->hasEnoughPoints($myfighter, $tpsrecup)){
                            
                            $this->Fighters->attaque($myfighter, $atta, $tpsrecup);
                            
                            $array = $this->Surroundings->obstacleDisplay($myfighter);
                            $alert = $this->Surroundings->TWaround($myfighter, $array);
                            
                            //$fightersByPos = $this->Fighters->getFighterByPos();
                            $fightersByPos = $this->Fighters->getFighterByPosAndId($myfighter);

                            $isdead = $this->Fighters->isDead($myfighter);
                            if($isdead){
                                unlink($folder.$id . '_' .$myfighter['id']. '.jpg');
                            }
                            $this->set('fbypos', $fightersByPos);
                            // Chargement du layout AJAX
                            $this->viewBuilder()->layout('ajax');
                            // Créer un contexte sites à renvoyer 
                            $this->set('sites',$array);
                            $this->set('alert',$alert);
                            $this->set('isdead',$isdead);
                            // Généreration des vues de données
                            $this->set('_serialize', ['sites','alert','fbypos','tpsrecup','ptmax','time','isdead']);
                        }else{
                            // Chargement du layout AJAX
                            $this->viewBuilder()->layout('ajax');
                            // Créer un contexte sites à renvoyer 
                            $this->set('sites',"ptaction");
                            // Généreration des vues de données
                            $this->set('_serialize', ['sites']);
                        }
                    }//Regénérer les obstacles dans l'arène
                    else if($this->request->getData('obs'))
                    {
                        $this->Surroundings->initObstacles();
                        $array = $this->Surroundings->obstacleDisplay($myfighter);
                        $alert = $this->Surroundings->TWaround($myfighter, $array);
                        
                        //$fightersByPos = $this->Fighters->getFighterByPos();
                        $fightersByPos = $this->Fighters->getFighterByPosAndId($myfighter);

                        $isdead = $this->Fighters->isDead($myfighter);
                        if($isdead){
                            unlink($folder.$id . '_' .$myfighter['id']. '.jpg');
                        }
                        $this->set('fbypos', $fightersByPos);
                        // Chargement du layout AJAX
                        $this->viewBuilder()->layout('ajax');
                        // Créer un contexte sites à renvoyer 
                        $this->set('sites',$array);
                        $this->set('alert',$alert);
                        $this->set('isdead',$isdead);
                        // Généreration des vues de données
                        $this->set('_serialize', ['sites','alert','fbypos','tpsrecup','ptmax','time','isdead']);
                    }
                }
                
            }

            //Array de l'arène
            $array = $this->Surroundings->obstacleDisplay($myfighter);
            $this->set('array', $array);

            $alert = $this->Surroundings->TWaround($myfighter, $array);
            if($alert !="")
                $this->Flash->error(__($alert));


            //Fighter courant + tous les fighters (pos)
            $this->set('myfighter', $myfighter);
            //Get all fighters (player_id, name + coord) ==> display dans l'arène
            
            //$fightersByPos = $this->Fighters->getFighterByPos();
            $fightersByPos = $this->Fighters->getFighterByPosAndId($myfighter);
            $this->set('fbypos', $fightersByPos);

        }
        $this->set('i',0);  
    }    
        
    
    public function diary(){
        $this->loadModel('Fighters');
        $id = $this->Auth->user('id');

        $session = $this->request->session();
        $selectedfighter = $session->read('selectedfighter');
        //Get fighter courant
        $myfighter = $this->Fighters->infoFighter($id,$selectedfighter);
        $this->loadModel('Events');
        
        if(isset($myfighter))
        {
            if($array = $this->Events->displayEvent($myfighter)){
            $this->set('vide', 0);
             $this->set('array', $array);
            }else  $this->set('vide', 2);
        }else $this->set('vide', 2);
        
        
    }
   

    public function newfighter() {

        $this->autoRender = false;

        $data = [];

        $emp=$this->Fighters->newEntity();
       
        $this->set(compact('data'));
        $this->set('_serialize', 'data');
    }

    
}