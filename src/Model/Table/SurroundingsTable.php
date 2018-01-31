<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Collection\Collection;

class SurroundingsTable extends Table
{
	
		//blindage : on passe deux attributs, x et y on regarde si quelque chose a ses coordonnées dans la base de données, 
		//si il y a quelque chose, on recommence un rand, sinon on l'ajoute a la table
		private function checkObs($x, $y){
			$obs = TableRegistry::get('Surroundings'); //table
			$query = $obs->find('all')->toArray(); //récupérer toute la table
			
			
			foreach($query as $one){

				$one_x = $one->coordinate_x;
				$one_y = $one->coordinate_y;

				
				if($one_x == $x and $one_y == $y){
				
					return (false);
				}
			}
			return (true);
		}



//----------------------------------------------------------------------
//----------------------------------------------------------------------

		//CREATION COLONNES 
		public function createColonne(){
			$obs = TableRegistry::get('Surroundings');

			//Nouvelle colonne
			$new = $obs->newEntity();
			$x = rand(0,14);
			$y = rand(0,9);
			$new->type = 'P';
			
			//S'il y a déjà un élément à ces coordonnées : choisir d'autres coordonnées
			while($obs->checkObs($x, $y) == false){
				
				$x = rand(0,14);
				$y = rand(0,9);
			}
			$new->coordinate_x = $x;
			$new->coordinate_y = $y;
			
			$obs->save($new);
			return($new);
		}

		//CREATION PIEGE
		public function createPiege(){
			$obs = TableRegistry::get('Surroundings');

			//Nouveau piège
			$new = $obs->newEntity();
			$x = rand(0,14);
			$y = rand(0,9);
			$new->type = 'T';

			//S'il y a déjà un élément à ces coordonnées : choisir d'autres coordonnées
			while($obs->checkObs($x, $y) == false){
				
				$x = rand(0,14);
				$y = rand(0,9);
			}
			$new->coordinate_x = $x;
			$new->coordinate_y = $y;
			
			$obs->save($new);
			return($new);
		}

		//CREATION MONSTRE
		public function createMonstre(){
			$obs = TableRegistry::get('Surroundings');

			//Nouveau monstre
			$new = $obs->newEntity();
			$x = rand(0,14);
			$y = rand(0,9);
			$new->type = 'W';

			//S'il y a déjà un élément à ces coordonnées : choisir d'autres coordonnées
			while($obs->checkObs($x, $y) == false){
				
				$x = rand(0,14);
				$y = rand(0,9);
			}
			$new->coordinate_x = $x;
			$new->coordinate_y = $y;
			
			$obs->save($new);
			return($new);
		}

		//CREATION OBSTACLES SUR L'ARENE
		public function initObstacles(){
			$obs = TableRegistry::get('Surroundings');
			$query = $obs->query();
			$query->delete()->execute();

			//Créer des pièges et des colonnes pour 1/10 cases
			$nbcases = 150;
			$nb = ($nbcases)/10;
			for( $j=0; $j<$nb; $j++){
				$obs->createColonne();
				$obs->createPiege();	
			}
			//Création d'un monstre invisible
			$obs->createMonstre();
			return (0);
		}

//----------------------------------------------------------------------
//----------------------------------------------------------------------

		//affiche un message (à personnaliser) quand le fighter s'approche d'un monstre ou d'un piège
		public function alertObstacle($row){
			$obs = TableRegistry::get('Surroundings');
			switch($row->type){
				case 'T':
					echo "Attention, il y a un piège en ".$row->coordinate_x."-".$row->coordinate_y;
					break;
				
				case 'W':
					echo "Attention, il y a un monstre en ".$row->coordinate_x."-".$row->coordinate_y;
					break;
			}
			return (0);
			
		}

		public function TWaround($fighter, $array){

			$obs = TableRegistry::get('Surroundings');
			$query = $obs->find('all')->toArray();

			$X = $fighter->coordinate_x;
			$Y = $fighter->coordinate_y;

			$alertMessage = "";

			foreach($query as $row)
			{
				if( ($row->coordinate_x == $X && $row->coordinate_y == $Y + 1) ||
					($row->coordinate_x == $X && $row->coordinate_y == $Y - 1) || 
					($row->coordinate_x == $X + 1 && $row->coordinate_y == $Y) ||
					($row->coordinate_x == $X -1 && $row->coordinate_y == $Y ))
				{
					switch($row->type){
						case 'T':
							$alertMessage = "Brise suspecte";
							break;
						case 'W':
							$alertMessage ="Puanteur ";
							break;
					}
				}
			}
			return $alertMessage;
		}

//----------------------------------------------------------------------
//----------------------------------------------------------------------

		//TROUVER LES COLONNES DANS LE CHAMP DE VISION DU FIGHTER
		public function findSurroundings($fighter,$arrayTotal){
			$obs = TableRegistry::get('Surroundings');
			$query = $obs->find('all')->toArray();

			$X = $fighter->coordinate_x;
			$Y = $fighter->coordinate_y;
			$V = $fighter->skill_sight;
			
			foreach ($query as $row)
			{
				//Si à portée de vue et de type P, le mettre dans l'arrayTotal
				if(	((abs($X - $row->coordinate_x) + abs($Y - $row->coordinate_y)) <= $V)
					&& $row->type == 'P')
				{
					$arrayTotal[$row->coordinate_x][$row->coordinate_y]='P';
				}
			}
			return $arrayTotal;
		}

		//TROUVER TOUS LES FIGHTERS DANS LE CHAMP DE VISION DU FIGHTER
		public function findFighters($fighter,$arrayTotal){
			$fighters = TableRegistry::get('Fighters');
			$query = $fighters->find('all')->where(['player_id !=' => $fighter->player_id])->toArray();

			$X = $fighter->coordinate_x;
			$Y = $fighter->coordinate_y;
			$V = $fighter->skill_sight;
				
			foreach ($query as $row)
			{
				//Si à portée de vue, mettre F dans l'arrayTotal
				if((abs($X - $row->coordinate_x) + abs($Y - $row->coordinate_y)) <= $V)
				{
					$arrayTotal[$row->coordinate_x][$row->coordinate_y]='F';
				}
			}
			return $arrayTotal;
		}


		//REMPLISSAGE DE L'ARRAY ARENE
		public function obstacleDisplay($fighter){
			$obs = TableRegistry::get('Surroundings');
			$query = $obs->find('all')->toArray();
			$fight = TableRegistry::get('Fighters');
			$combat = $fight->find('all')->toArray();

			$X = $fighter->coordinate_x;
			$Y = $fighter->coordinate_y;
			$V = $fighter->skill_sight;
			
			$j=0;
			//remplir le tableau de case vides / de brume
			//$i = ligne = y / $j = colonne = x
			while($j<15)
			{
				$i =0;
				while($i<10)
				{
					//Si à portée de vue
					if((abs($X - $j) + abs($Y - $i)) <= $V)
						$arrayTotal[$j][$i] = 'A';	
					else
						$arrayTotal[$j][$i] = 'B';
					
					$i++;
				}
				$j++;	
			}

			//ajouter les colonnes visibles par le fighter
			$arrayTotal = $obs->findSurroundings($fighter,$arrayTotal);
			$arrayTotal = $obs->findFighters($fighter,$arrayTotal);
				
			//pour prévenir si il y a un piège ou un monstre à une case du fighter
			//echo "coor:".$X."-".$Y." ";
			/*foreach($query as $row)
			{
				if( ($row->coordinate_x == $X && $row->coordinate_y == $Y + 1) ||
					($row->coordinate_x == $X && $row->coordinate_y == $Y - 1) || 
					($row->coordinate_x == $X + 1 && $row->coordinate_y == $Y) ||
					($row->coordinate_x == $X -1 && $row->coordinate_y == $Y ))
				{
					$obs->alertObstacle($row);
				}
			}*/

			//ajouter les autres fighters présents sur la carte
			// foreach ($combat as $comb) {
			// 	$arrayTotal[$comb->coordinate_x][$comb->coordinate_y] = 'F';
			// }

			//Ajout du fighter courant (mais ça devrait être assuré par findFighters déjà ?)
			$arrayTotal[$X][$Y]='F';


			return $arrayTotal;
		}

}