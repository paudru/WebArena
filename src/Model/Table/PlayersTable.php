<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;


class PlayersTable extends Table
{

	public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('email', "Un nom d'utilisateur est nécessaire")
            ->notEmpty('password', 'Un mot de passe est nécessaire');
    }

    public function updatePwd($email, $password)
    {
    	$players = TableRegistry::get('Players');
    	$query = $players->query();
		$query->update()
		    ->set(['password' => $password])
		    ->where(['email' => $email])
		    ->execute();
    }

}