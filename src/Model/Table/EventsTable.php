<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class EventsTable extends Table
{

	public function createNewEvent1($fighter1, $fighter2, $type){
		$events = TableRegistry::get('Events');
		$fighters = TableRegistry::get('Fighters');
		$new = $events->newEntity();
		switch ($type) {
			case '1':		//attaque ratée
				$new->name = $fighter1->name.' attaque '.$fighter2->name.' mais le rate.';
				$new->date = Time::now();
				$new->coordinate_x = $fighter1->coordinate_x;
				$new->coordinate_y = $fighter1->coordinate_y;
				$events->save($new);
				break;

			case '2':		//attaque réussie
				$new->name = $fighter1->name.' attaque '.$fighter2->name.' et le touche.';
				$new->date = Time::now();
				$new->coordinate_x = $fighter1->coordinate_x;
				$new->coordinate_y = $fighter1->coordinate_y;
				$events->save($new);
				break;

			case '3':		//mort d'un autre combattant
				$new->name = $fighter1->name.' a tué '.$fighter2->name;
				$new->date = Time::now();
				$new->coordinate_x = $fighter1->coordinate_x;
				$new->coordinate_y = $fighter1->coordinate_y;
				$events->save($new);
				break;

			}

	}

	public function createNewEvent2($fighter1, $type){
		$events = TableRegistry::get('Events');
		$fighters = TableRegistry::get('Fighters');
		$new = $events->newEntity();
		switch ($type) {
			case '4':		//monstre tué
				$new->name = $fighter1->name.' a tué le monstre!';
				$new->date = Time::now();
				$new->coordinate_x = $fighter1->coordinate_x;
				$new->coordinate_y = $fighter1->coordinate_y;
				$events->save($new);
				break;

			case '5':		//fighter tué par un monstre
				$new->name = $fighter1->name.' a été tué par le monstre';
				$new->date = Time::now();
				$new->coordinate_x = $fighter1->coordinate_x;
				$new->coordinate_y = $fighter1->coordinate_y;
				$events->save($new);
				break;

			case '6':		//fighter tué par un piège
				$new->name = $fighter1->name.' a été tué par un piège';
				$new->date = Time::now();
				$new->coordinate_x = $fighter1->coordinate_x;
				$new->coordinate_y = $fighter1->coordinate_y;
				$events->save($new);
				break;

			}
	}

	public function displayEvent($fighter){
		$events = TableRegistry::get('Events');
		$query = $events->find('all')->toArray();
		// $date = Time::now();
		$temp = array();
		foreach ($query as $key) {
			if($key->date->wasWithinLast(1)){
				$temp[] = $key;
			}
		}
		$retour = $events->findEventCoord($fighter, $temp);
		return $retour;

	}

	public function findEventCoord($fighter, $temp){
		$events = TableRegistry::get('Events');
		$X = $fighter->coordinate_x;
		$Y = $fighter->coordinate_y;
		$V = $fighter->skill_sight;
		$W = 0;
		$vide = true;
		
		foreach ($temp as $row){
			$j = $V;
			$W = 0;
			while($j >= 0){
				
				$i = $W;
				while($i >= 0){
					
					if($i == 0 and $j == 0){
						if($row->coordinate_x == $X and $row->coordinate_y == $Y){
								$retour[] = $row;
								$vide = false;
							}
						$i--;
					}else
					if($i == 0 and $j != 0){
						if($row->coordinate_x == $X and $row->coordinate_y == $Y + $j){
								$retour[] = $row;
								$vide = false;
							
							
						}
						if($row->coordinate_x == $X and $row->coordinate_y == $Y - $j){
							$retour[] = $row;
							$vide = false;
							
						}
						$i--;
					}else
					if($i != 0 and $j == 0){
						if($row->coordinate_x == $X + $i and $row->coordinate_y == $Y){
							$retour[] = $row;
							$vide = false;
							
						}
						if($row->coordinate_x == $X - $i and $row->coordinate_y == $Y){
							$retour[] = $row;
							$vide = false;
							
						}
						$i--;
					}else{
						if($row->coordinate_x == $X + $i and $row->coordinate_y == $Y + $j){
							$retour[] = $row;
							$vide = false;
							
						}
						if($row->coordinate_x == $X - $i and $row->coordinate_y == $Y + $j){
							$retour[] = $row;
							$vide = false;
							
						}
						if($row->coordinate_x == $X + $i and $row->coordinate_y == $Y - $j){
							$retour[] = $row;
							$vide = false;
							
						}
						if($row->coordinate_x == $X - $i and $row->coordinate_y == $Y - $j){
						$retour[] = $row;
						$vide = false;
							
						}
						$i--;
					}

				}$W++;
				$j--;	
			}
		}
		if($vide == false){
			return $retour;
		}else return 0;
	}
}