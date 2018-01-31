<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\Configure;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
        $this->loadComponent('Auth', [
             'authenticate' => [
                'Form' => [
                    'userModel'=>'Players',
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ] ],
            ], 
            'loginAction' => [
                'controller' => 'Players',
                'action' => 'login'
            ],
            'loginRedirect' => [
                'controller' => 'Arenas', 
                'action' => 'fighter'],
            'logoutRedirect' => [
                'controller' => 'Arenas',
                'action' => 'index'
            ],
            // If the user arrives on an unauthorized page,
            // redirects to the previous page.
            'unauthorizedRedirect' => $this->referer()
        ]);
    }
    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        // Note: These defaults are just to get started quickly with development
        // and should not be used in production. You should instead set "_serialize"
        // in each action as required.
        $this->loadComponent('Auth');

        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
        $this->set('email', $this->Auth->user('id'));
    }
    public function beforeFilter(Event $event)
    {
        //Option C : gestion d'une limite temporelle
        $this->set('PT_ACTION_MAX', 3); //Nombre de points d'action maximum
        $this->set('TPS_RECUP', 10); //Temps de récupération d'un point d'action
    }
}