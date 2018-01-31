<?php $this->assign('title', 'Vision');?>


<?php 

if(!isset($myfighter)){	
//SI LE JOUEUR N'A PAS DE COMBATTANT ?>
<div style = "background-image : url('https://i.ytimg.com/vi/3rpGX6Rqj4o/maxresdefault.jpg'); background-size : cover; padding-bottom : 40%; background-position : center" >
 	<div class="alert callout text-center" style = "color : red;">
 		<p>Vous ne pouvez pas accéder à l'arène sans combattant !</p>
 		<?php echo $this->Html->link('Sélectionner un combattant', array('controller' => 'Arenas', 'action' => 'fighter'), ['class' => 'button secondary']); ?>
 	</div>
</div>
 	
<?php }else{ ?>
	


<!--Grille Boutons / Arène-->
<div class="grid-x grid-padding-x">
	<!--Boutons-->
	<div class="large-2 cell titlePage">
		 <?php
		// if($displayOption == 0){ // ?
		?>	

		<?php

		$width_time = $time/($ptmax*$tpsrecup)*100;
		$timebar = "width : ". $width_time . "%";

		?>

	
	<div class="primary progress" role="progressbar" tabindex="0" aria-valuenow="<?= $time ?>" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
		<span class="progress-meter" id = "progress" style =" <?= $timebar?>" >
			<p class="progress-meter-text">
			</p>
		</span>
	</div>
	<p id="actionpt"></p>


		<!--DEPLACEMENT-->
		<div>
			<h2 class="h3">Déplacez-vous</h2>
			<div class="grid-x">
				<div class="small-4 small-offset-4">
			<?= $this->Form->PostButton('<i class="fa fa-arrow-up fa-2x"></i>', ['controller' => 'Arenas','action' => 'sight'], ['data-dir' => '1', 'class' => 'button primary sendmov', 'onclick' => 'return false;']); ?>
				</div>
			</div>
			<div class= "grid-x">
				<div class="small-4">
			<?= $this->Form->PostButton('<i class="fa fa-arrow-left fa-2x"></i>', ['controller' => 'Arenas','action' => 'sight'], ['data-dir' => '3', 'class' => 'button primary sendmov rptaction', 'onclick' => 'return false;']); ?>
				</diV>
				<div class="small-4">
			<?= $this->Form->PostButton('<i class="fa fa-arrow-down fa-2x"></i>', ['controller' => 'Arenas','action' => 'sight'], ['data-dir' => '2', 'class' => 'button primary sendmov rptaction', 'onclick' => 'return false;']); ?>
				</div>
				<div class="small-4">
			<?= $this->Form->PostButton('<i class="fa fa-arrow-right fa-2x"></i>', ['controller' => 'Arenas','action' => 'sight'], ['data-dir' => '4', 'class' => 'button primary sendmov rptaction', 'onclick' => 'return false;']); ?>
				</div>
			</div>
		</div>
		<!--ATTAQUE-->
		<div>
			<h2 class="h3">Attaquez</h2>
			<div class="grid-x">
				<div class="small-4 small-offset-4">
			<?=  $this->Form->PostButton('<i class="fa fa-arrow-up fa-2x"></i>', ['controller' => 'Arenas','action' => 'sight'], ['data-atta' => '1', 'class' => 'button alert sendatta', 'onclick' => 'return false;']);?>
				</div>
			</div>
			<div class= "grid-x">
				<div class="small-4">
			<?= $this->Form->PostButton('<i class="fa fa-arrow-left fa-2x"></i>', ['controller' => 'Arenas','action' => 'sight'], ['data-atta' => '3', 'class' => 'button alert sendatta', 'onclick' => 'return false;']); ?>
				</diV>
				<div class="small-4">
			<?= $this->Form->PostButton('<i class="fa fa-arrow-down fa-2x"></i>', ['controller' => 'Arenas','action' => 'sight'], ['data-atta' => '2', 'class' => 'button alert sendatta', 'onclick' => 'return false;']); ?>
				</div>
				<div class="small-4">
			<?= $this->Form->PostButton('<i class="fa fa-arrow-right fa-2x"></i>', ['controller' => 'Arenas','action' => 'sight'], ['data-atta' => '4', 'class' => 'button alert sendatta', 'onclick' => 'return false;']); ?>
				</div>
			</div>
		</div>

		<!--RESET OBSTACLES-->
		<?= $this->Form->PostButton('générer obstacles', ['controller' => 'Arenas','action' => 'sight'], ['data-obs' => '5', 'class' => 'button primary large reinitobs', 'onclick' => 'return false;', 'style' => 'margin-top : 60px']); ?>

 
 	</div>


 	<!--Arène-->
	<div class= "large-10 cell backblack" id="arene">
		
		
		<div id="arene-errors">
			<?= $this->Flash->render(); ?>	
		</div>
		<!--Table sans rayures, margin = 5% car 15*col(6%)+2*5% = 100%-->
		<div class="table-scroll">
			<table class="unstriped" style = "margin : 5%;">
				<?php $j=0;
				while($j<15){ ?>
					<tr style = "background-image : url('https://upload.wikimedia.org/wikipedia/commons/6/6f/Essos.jpg'); background-size : cover;">
						<?php foreach ($array as $key) { 
						$i = 0 ?>
							<td class="col15">

								<?php foreach ($key as $col) {

									//Rand [1;3] pour les différentes images de brouillard
									$fog = rand(1,3);
									//chaine pour le style des images. A modifier plus tard => css + class (ou class foundation?)
									$size = " 'width : 100%' ";
									// /!\ marche si toutes les images sont carrées, crée des décalages sinon => à régler.
									//Brume
									if($col==="B"){ echo $this->Html->image('Fog'.$fog.'.png', ['alt' => 'B', 'style' => $size, 'id'=> $j.'_'.$i ]);}
									//Nada
									else if($col==="A"){ echo $this->Html->image('vide.jpg', ['alt' => 'A', 'style' => $size, 'id'=> $j.'_'.$i]);}
									//Colonne
							        else if($col==="P") { echo $this->Html->image('https://vignette.wikia.nocookie.net/farmville/images/6/6b/Castle_Ruins-icon.png/revision/latest?cb=20110515061930', ['alt' => 'P', 'style' => $size, 'id'=> $j.'_'.$i]);} 
								    //Piège - de toute façon on affiche ni les pièges ni les monstres, donc osef presque
									else if($col==="T") { echo $this->Html->image('Frey.png', ['alt' => 'T', 'style' => $size, 'id'=> $j.'_'.$i]);}
							        //Monster
							        else if($col==="W") { echo $this->Html->image('https://vignette.wikia.nocookie.net/game-of-thrones-le-trone-de-fer/images/9/90/Viserion_et_le_Roi_de_la_Nuit.png/revision/latest/scale-to-width-down/350?cb=20170828195843&path-prefix=fr', ['alt' => 'W', 'style' => $size, 'id'=> $j.'_'.$i]);} 
							        //Fighter
							        else if($col==="F") 
							     	{
							        	foreach ($fbypos as $currentFighter)	///----------------Pb parce que fbypos est pas recalculé à chaque fois
							        	{ 
							        		//Trouver quel est le fighter à cette position F et display son image
							        		if($currentFighter->coordinate_x == $j && $currentFighter->coordinate_y == $i)
							        			echo $this->Html->image($currentFighter->player_id."_".$currentFighter->id.".jpg" , ['alt' => $currentFighter->name, 'style' => $size, 'id'=> $j.'_'.$i]);
							        	}	
							        }
					       			$i++;
								} 
								$j++;
							echo "</td>";
						}
					echo "</tr>";
				}
			echo "</table>";?>
		</div>
		<!--JOUEUR COURANT-->
		<div class="grid-x float-center">
			<h4 style = "color : white;"> <?= "Vous jouez avec " . $myfighter->name ."</h4>"?>
			<button class="clear button" type="button" data-toggle="changeFighter">
				Changer de combattant
			</button>
		</div>
		<!--Form (toggle) pour changer le fighter-->
		<div class="dropdown-pane callout" id="changeFighter" data-dropdown data-close-on-click="true" style="width : 32%;">
			Allez sur la page combattant et cochez le combattant de votre choix.
		</div>
	</div>
</div>

<?php } ?>


<?php 


$this->Html->scriptStart(['block' => true]);
echo 'var sec = '.$time.';
    function pad ( val ) { 
    	return val > 9 ? val : "0" + val; 
    }
    var inter = setInterval( function(){
    	var seconds = pad(++sec);
    	console.log(seconds);
    	var actionpt = 0;
    	if(seconds<='.($ptmax*$tpsrecup).'){
	    	var width_time = seconds/'.($ptmax*$tpsrecup).'*100;
			var timebar = "width : " + width_time + "%";
	    	$("#progress").attr("style",timebar);
	    	document.getElementById("actionpt").innerHTML = "Vous avez " + Math.floor(seconds/'.$tpsrecup.') + " point(s) d\'action";
	    }else{
	    	document.getElementById("actionpt").innerHTML = "Vous avez '.$ptmax.' point(s) d\'action";
	    }

    }, 1000);';

$this->Html->scriptEnd();
echo $this->Html->script('vendor/jquery'); 
echo $this->Html->script(['navigation']);


?>

