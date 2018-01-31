<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class FightersTable extends Table
{


//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------


    public function getAllFighters($id){
        $fighters = TableRegistry::get('Fighters');
        $query = $fighters
                ->find()
                ->select(['name'])
                ->where(['player_id' => $id]);    
        $allfighters = $query->toArray();
        return($allfighters);
        
    }
    public function getBestFighter($id){
       
        $fighters = TableRegistry::get('Fighters');
        $query = $fighters
                ->find()
                ->select(['name','level'])
                ->where(['player_id' => $id])
                ->order(['level' => 'DESC'])
                ->limit([1]);
            
        $bestfighter = $query->toArray();
        return($bestfighter);
        
    }
    public function countFighters($id){
        $fighters = TableRegistry::get('Fighters');
        $query = $fighters
                ->find('all')
                ->where(['player_id' => $id]);
        $nfighters = $query -> count();
        return ($nfighters);
    }

    public function infoFighter($id, $fighter_id){
         $fighters = TableRegistry::get('Fighters');
         global $displayOption;
         $displayOption = 0;
        $query = $fighters->find()->where(['player_id' => $id, 'id'=> $fighter_id])->first(); //possiblement à changer pour récupérer le combatant correspondant ou joueur
        return ($query);
    }

    public function infoFighterByName($id, $idFighter){
         $fighters = TableRegistry::get('Fighters');
         global $displayOption;
         $displayOption = 0;
        $query = $fighters->find()->where(['player_id' => $id, 'id' => $idFighter])->first(); //possiblement à changer pour récupérer le combatant correspondant ou joueur
        return ($query);
    }


    public function getFighterByPos(){
        $fighters = TableRegistry::get('Fighters');
        $query = $fighters
                ->find()
                ->select(['name','player_id','id', 'coordinate_x', 'coordinate_y']);
        $fightersPos = $query->toArray();
        return($fightersPos);
    }


    public function getFighterByPosAndId($myfighter){

        $fighters = TableRegistry::get('Fighters');
        $query = $fighters
                ->find()
                ->select(['name','player_id','id', 'coordinate_x', 'coordinate_y'])
                ->where(['id'=>$myfighter->id])
                ->orWhere(['player_id !=' => $myfighter->player_id]);
        $fightersPos = $query->toArray();
        return($fightersPos);

        /*

        $fightersPos = TableRegistry::get('Fighters');
        $query = $fightersPos
                ->find()
                ->select(['name','player_id','id', 'coordinate_x', 'coordinate_y']);
                ->where(['player_id !=' => $myfighter->player_id]);
                ->orWhere(['id' => $myfighter->id]);
        $fightersPosAndId = $query->toArray();
        return($fightersPosAndId);*/
    }


    public function addNewFighter($name, $id){
        $fighters = TableRegistry::get('Fighters');
        $new = $fighters->newEntity();
        $new->name = $name;
        $new->player_id = $id;
        $new->coordinate_x = rand(0,14);
        $new->coordinate_y = rand(0,9);
        $new->level = 1;
        $new->xp = 4;
        $new->skill_sight = 2;
        $new->skill_strength = 1;
        $new->skill_health = 5;
        $new->current_health = 5;
        $new->next_action_time = TIME::now();
        $fighters->save($new);
        return $new->id;
    }


//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------

    public function displaySkills($id){
        $fighters = TableRegistry::get('Fighters');
        $query = $fighters
                ->find()
                ->select(['id','name','level','xp', 'skill_sight','skill_strength','skill_health','current_health'])
                ->where(['player_id' => $id]);

            
        $fighterskills = $query->toArray();
        return($fighterskills);
    }

    public function improveSkill($skill, $fighter)
    {
        $fighters = TableRegistry::get('Fighters');
        switch ($skill) {
            case "sight" :
                $fighter->skill_sight = $fighter->skill_sight + 1;
                break;
            case "strength" :
                $fighter->skill_strength= $fighter->skill_strength + 1;
                break;
            case "health" :
                $fighter->skill_health = $fighter->skill_health + 3;
                $fighter->current_health = $fighter->skill_health;
                
                break;
           
        }
        $fighter->level = $fighter->level + 1;
        $fighters->save($fighter);
    }

//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------

    public function timeManager($fighter, $PT_ACTION_MAX, $TPS_RECUP){
        $fighters = TableRegistry::get('Fighters');
        Time::setDefaultLocale('fr-FR');
        Time::setToStringFormat('yyyy-MM-dd HH:mm:ss');
        $date=new Time($fighter->next_action_time);
        $diff_time = Time::now()->diffInSeconds($date);
        if($diff_time>($PT_ACTION_MAX*$TPS_RECUP)){
            $newtime=Time::now()->subSeconds($PT_ACTION_MAX*$TPS_RECUP);
            $fighter->next_action_time=$newtime;
            $fighters->save($fighter);
            $diff_time=$PT_ACTION_MAX*$TPS_RECUP;
        }
        return $diff_time;
    }

    public function actionPointUsed($fighter, $TPS_RECUP){
        $fighters = TableRegistry::get('Fighters');
        Time::setDefaultLocale('fr-FR');
        Time::setToStringFormat('yyyy-MM-dd HH:mm:ss');
        $date=new Time($fighter->next_action_time);
        $date->addSecond($TPS_RECUP);
        $fighter->next_action_time=$date;
        $fighters->save($fighter);
    }

    public function hasEnoughPoints($fighter, $TPS_RECUP){
        $fighters = TableRegistry::get('Fighters');
        Time::setDefaultLocale('fr-FR');
        Time::setToStringFormat('yyyy-MM-dd HH:mm:ss');
        $date=new Time($fighter->next_action_time);
        $diff_time = Time::now()->diffInSeconds($date);
        if($diff_time>=$TPS_RECUP)
            return true;
        else
            return false;
    }

    public function checkFighters($player, $PT_ACTION_MAX, $TPS_RECUP)
    {
        $fighters = TableRegistry::get('Fighters');
        Time::setDefaultLocale('fr-FR');
        Time::setToStringFormat('yyyy-MM-dd HH:mm:ss');
        $query = $fighters->query();
        $query->update()
            ->set(['next_action_time' => Time::now()->subSeconds($PT_ACTION_MAX*$TPS_RECUP)])
            ->where(['player_id' => $player])
            ->execute();
    }


//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------

    public function move($fighter, $dir, $TPS_RECUP){
        $obs = TableRegistry::get('Surroundings');
        $col = $obs->find('all')->where(['type' => 'P'])->toArray();
        $fighters = TableRegistry::get('Fighters');
        $allfighter = $fighters->find('all')->where(['player_id !=' => $fighter->player_id])->toArray();
        $coltrouve = false;
        $fighttrouve = false;
        
        switch ($dir) {
            //HAUT
            case '1': 
                //Blindage sortie d'arène
                if($fighter->coordinate_y == 0)
                    break;
                else{
                    //S'il y a une colonne
                    foreach ($col as $key){
                        if($key->coordinate_y == $fighter->coordinate_y - 1 and $key->coordinate_x == $fighter->coordinate_x)
                            $coltrouve = true;
                    }
                    //S'il y a un autre combattant
                    foreach($allfighter as $af){
                         if($af->coordinate_y == $fighter->coordinate_y - 1 and $af->coordinate_x == $fighter->coordinate_x)
                            $fighttrouve = true;
                    }
                    //Si pas de colonne et pas de combattant => Autoriser déplacement
                    if($coltrouve == false and $fighttrouve == false){
                        $fighters->actionPointUsed($fighter, $TPS_RECUP); 
                        $fighter->coordinate_y= $fighter->coordinate_y - 1;
                        $fighters->isDead($fighter);   
                        $fighters->save($fighter);
                    }
                }
                
                return $fighter;
                break;
                
            case '2': // en bas
                if($fighter->coordinate_y == 9)
                    break;
                else{
                    //S'il y a une colonne
                    foreach ($col as $key) {
                        if($key->coordinate_y == $fighter->coordinate_y + 1 and $key->coordinate_x == $fighter->coordinate_x)
                            $coltrouve = true;
                    }
                    //S'il y a un autre combattant
                    foreach($allfighter as $af){
                         if($af->coordinate_y == $fighter->coordinate_y + 1 and $af->coordinate_x == $fighter->coordinate_x)
                            $fighttrouve = true;
                    }
                    //Si pas de colonne et pas de combattant => Autoriser déplacement
                    if($coltrouve == false and $fighttrouve == false){
                        $fighters->actionPointUsed($fighter, $TPS_RECUP); 
                        $fighter->coordinate_y= $fighter->coordinate_y + 1;
                        $fighters->isDead($fighter);
                        $fighters->save($fighter);
                    }
                }
                
                return $fighter;
                break;

            case '3': // a gauche
            
                if($fighter->coordinate_x == 0){
                    break;
                }else{
                    //S'il y a une colonne
                    foreach ($col as $key) {
                        if($key->coordinate_x == $fighter->coordinate_x - 1 and $key->coordinate_y == $fighter->coordinate_y)
                            $coltrouve = true;
                    }
                    //S'il y a un combattant
                    foreach($allfighter as $af){
                         if($af->coordinate_x == $fighter->coordinate_x - 1 and $af->coordinate_y == $fighter->coordinate_y)
                            $fighttrouve = true;
                    }
                    //Si pas de colonne et pas de combattant => Autoriser déplacement
                    if($coltrouve == false and $fighttrouve == false){
                        $fighters->actionPointUsed($fighter, $TPS_RECUP); 
                        $fighter->coordinate_x= $fighter->coordinate_x - 1;
                        $fighters->isDead($fighter);
                        $fighters->save($fighter);
                    }
                }
                
                return $fighter;
                break;
            case '4': // a droite
                if($fighter->coordinate_x == 14){
                    break;
                }else{
                    //S'il y a une colonne
                    foreach ($col as $key) {
                        if($key->coordinate_x == $fighter->coordinate_x + 1 and $key->coordinate_y == $fighter->coordinate_y)
                            $coltrouve = true;
                    }
                    //S'il y a un combattant
                    foreach($allfighter as $af){
                         if($af->coordinate_x == $fighter->coordinate_x + 1 and $af->coordinate_y == $fighter->coordinate_y)
                            $fighttrouve = true;
                    }
                    //Si pas de colonne et pas de combattant => Autoriser déplacement
                    if($coltrouve == false and $fighttrouve == false){
                        $fighters->actionPointUsed($fighter, $TPS_RECUP); 
                        $fighter->coordinate_x= $fighter->coordinate_x + 1;
                        $fighters->isDead($fighter);
                        $fighters->save($fighter);
                    }
                }
                break;
        
       } return $fighter;
   }
    





    //Passage Piège (T) ou Monstre (W) => Mort

    public function isDead($fighter){
            
            $fighters = TableRegistry::get('Fighters');
            $events = TableRegistry::get('Events');
            
            $obs = TableRegistry::get('Surroundings');
            $query = $obs->find('all')->toArray();
            
            $X = $fighter->coordinate_x;
            $Y = $fighter->coordinate_y;

            //Pour chaque élément de décors : T ou W
            foreach($query as $q)
            {
                //T
                if($q->coordinate_x == $X and $q->coordinate_y == $Y and $q->type == 'T')
                {
                    //Déclenche event 6 + Mort
                    $events->createNewEvent2($fighter, '6');
                    $fighters->delete($fighter); 
                    return true;
                }
                //W
                else if($q->coordinate_x == $X and $q->coordinate_y == $Y and $q->type == 'W')
                {
                    //Déclenche event 5 + Mort
                    $events->createNewEvent2($fighter, '5');
                    $fighters->delete($fighter); 
                    return true;
               }   

            }

            return false;
    }


//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------

    public function testAttaque1($fighter, $dir){
        //récupère la position du joueur, la direction de l'attaque
        $fighters = TableRegistry::get('Fighters');
        $query = $fighters->find('all')->toArray();
        $X = $fighter->coordinate_x;
        $Y = $fighter->coordinate_y;
        //verifie si un fighter est présent sur la case attaquée
        switch($dir){
            case '1':
                foreach ($query as $key) {
                    if($key->coordinate_y == $Y - 1 and $key->coordinate_x == $X){
                        return $key;
                    }
                }break;
            case '2':
                 foreach ($query as $key) {
                    if($key->coordinate_y == $Y + 1 and $key->coordinate_x == $X){
                         return $key;
                    }
                }break;
            case '3':
                 foreach ($query as $key) {
                    if($key->coordinate_y == $Y and $key->coordinate_x == $X - 1){
                        return $key;
                    }
                }break;
            case '4':
                foreach ($query as $key) {
                    if($key->coordinate_y == $Y and $key->coordinate_x == $X + 1){
                         return $key;
                    }
                }break;
        }
        
        
    }
    public function testAttaque2($fighter, $dir){
        //récupère la position du joueur, la direction de l'attaque
        $obs = TableRegistry::get('Surroundings');
        $query = $obs->find('all')->toArray();
        $X = $fighter->coordinate_x;
        $Y = $fighter->coordinate_y;
        //verifie si un fighter est présent sur la case attaquée
        switch($dir){
            case '1':
                foreach ($query as $key) {
                    if($key->coordinate_y == $Y - 1 and $key->coordinate_x == $X and $key->type == 'W'){
                        return $key;
                    }
                }break;
            case '2':
                 foreach ($query as $key) {
                    if($key->coordinate_y == $Y + 1 and $key->coordinate_x == $X and $key->type == 'W'){
                         return $key;
                    }
                }break;
            case '3':
                 foreach ($query as $key) {
                    if($key->coordinate_y == $Y and $key->coordinate_x == $X - 1 and $key->type == 'W'){
                        return $key;
                    }
                }break;
            case '4':
                foreach ($query as $key) {
                    if($key->coordinate_y == $Y and $key->coordinate_x == $X + 1 and $key->type == 'W'){
                         return $key;
                    }
                }break;
        }
        
    }


    public function attaque($fighter,$dir, $TPS_RECUP){

        $fighters = TableRegistry::get('Fighters');
        $obs = TableRegistry::get('Surroundings');
        $events = TableRegistry::get('Events');
        $ennemis = $fighters->find('all')->toArray();

        if($ennemi = $fighters->testAttaque1($fighter, $dir)){
            //chargement aléatoire
            //recupérer le niveau de l'attaquant
            $mylevel = $fighter->level;
            $yourlevel = $ennemi->level;
            $conditionreussi = 10 + $yourlevel - $mylevel;
            $testreussi = rand(1, 20);
            if($testreussi > $conditionreussi){
                $ennemi->current_health = $ennemi->current_health - $fighter->skill_strength;
                if($ennemi->current_health <= 0){
                    //mort de l'attaqué
                    $fighters->delete($ennemi);
                    $fighter->xp = $fighter->xp + $ennemi->level + 1;
                   $fighters->save($fighter);
                   $events->createNewEvent1($fighter, $ennemi, '2');
                   $events->createNewEvent1($fighter, $ennemi, '3');
                }else {
                    $fighter->xp = $fighter->xp + 1;
                    $fighters->save($ennemi);
                    $events->createNewEvent1($fighter, $ennemi, '2');
                }
            }else {
                //echo "attaque ratée... ->".$testreussi;
                $events->createNewEvent1($fighter, $ennemi, '1');
            }
            //$fighters->actionPointUsed($fighter, $TPS_RECUP); 
        }else
        if($monstre = $fighters->testAttaque2($fighter, $dir) ){
            $obs->delete($monstre);
            $events->createNewEvent2($fighter, '4');
            
        } 
         $fighters->actionPointUsed($fighter, $TPS_RECUP);
        //else echo "pas d'attaque possible";
        return 0;
    }
    
    //si oui,
            //chargement aléatoire de la réussite
            //si attaque réussie
                //xp du combattant +1
                //pv du combatant attaqué = - skill force de l'attaquant
                //si mort
                    //combattant attaqué mort et attaquant gagne 1 + niveau du mort
            //si attaque ratée
                //"aataque ratée"
}