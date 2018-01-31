<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use App\Form\AddFighterForm;
use Cake\Event\Event; 
use Cake\Auth\DefaultPasswordHasher;

/**
 * Controller utilisateur
 * Toutes les fonctions relatives aux joueurs 
 */
class PlayersController extends AppController {

	/**
	 * Autorisation d'accès aux pages d'inscription et de déconnexion sans connexion
	 */
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'logout','pwdrecovery']);
    }

    /**
     * Ajout d'un utilisateur
     */
	public function add()
    {
        $user = $this->Players->newEntity();
        if ($this->request->is('post')) 
        {
            $user = $this->Players->patchEntity($user, $this->request->getData());

            //Vérifier si l'adresse mail est déjà utilisée (= présente dans la table Players)
            $playersEmail = TableRegistry::get('Players');
            $query = $playersEmail
                    ->find()
                    ->select(['email'])
                    ->where(['email' => $user->email]);
            if($query-> count() != 0)
            {
                $this->Flash->error(__("Cette adresse mail est déjà utilisée"));
            }
            else{
                //Ajouter le compte
                if ($this->Players->save($user))
                {
                    $this->Flash->success(__("L'utilisateur a été sauvegardé. Il est maintenant temps de créer votre premier combattant."));
                    return $this->redirect(['controller' => 'Arenas', 
                    'action' => 'fighter']);
                }
                else{
                $this->Flash->error(__("Impossible d'ajouter l'utilisateur."));
                }
            }
        }

        $this->set('user', $user);
    }

    /**
     * Connexion
     */
    public function login()
    {
        $this->loadModel('Fighters');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                //Vérification des champs 'next_action_time' du joueur pour le bon fonctionnement des points d'action
                $ptmax=$this->viewVars['PT_ACTION_MAX'];
                $tpsrecup=$this->viewVars['TPS_RECUP'];
                $this->Fighters->checkFighters($this->Auth->user('id'), $ptmax, $tpsrecup);

                return $this->redirect($this->Auth->redirectUrl());
            }else{
                $this->Flash->error(__('Votre adresse mail ou mot de passe est incorrect, veuillez réessayer'));
            }
        }

    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    public function pwdrecovery()
    {
        $this->loadModel('Players');
        if($this->request->is('post')){
            $email=$this->request->getData('email');
            $password=$this->request->getData('password');
            $hasher = new DefaultPasswordHasher();
            $this->Players->updatePwd($email, $hasher->hash($password));
            return $this->redirect(['controller' => 'Players', 
                    'action' => 'login']);
        }
    }

}