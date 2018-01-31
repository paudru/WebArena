<?php $this->assign('title', 'Combattant'); ?>


<?php $x = rand(1,8); 
$x = "background-image : url('../webroot/img/wallpaper".$x.".jpg');"?>

<!--grille titre / Fiches combattant-->
<div style = "overflow : hidden">
<div class="grid-x grid-margin-x mainContent" style = "<?php echo $x ?>">	

	<!--titre-->
	<div class="large-3 cell titlePage">
		<h2 class="float-center" style="text-align : center">Mes combattants</h2>
		
      	<?= $this->Html->image('kingshand.png', ['alt' => '']); ?>
	</div>

	<!-- // -->


	<!-- Fiches combattant-->
	<div class="large-9 cell" style="overflow : hidden">


		<!--S'il y a des combattants : les afficher-->
		<?php if($num != 0) { ?>

		<!-- XY Grid cartes combattants-->
		<div class="grid-x grid-margin-x">	
		<?php
		//Pour tous les fighters (pour l'instant, peut importe le joueur)
		foreach($skills as $s){ ?> 

			<?php 
				//Si le joueur doit prendre des augmentations
				if($s['xp']/4 - $s['level'] >= 1) $lvlup = 1;
				else $lvlup=0; 
			?>

		<!--Carte Combattant-->
		<div class="small-10 small-offset-1 medium-offset-0 medium-4 large-5 cell" >
			<div class="card" id="<?= $s['name']; ?>">
				<!--Header de la carte-->
				<div class="card-divider" style="position : relative;">
					<!--Nom du Combattant-->
<!--CSS/!\-->
			    	<h4 style= "width : 75%; margin-bottom : 0px;">
			    		<p style="display : inline-block"> 

			    			<?php 
			    			if(isset($selectedfighter)){
			    				if($selectedfighter==$s['id'])
			    					echo $this->Form->radio('currentFighter', [['value' => $s['id'] , 'text' => '', 'checked'=>"checked"]]); 
			    				else
				    				echo $this->Form->radio('currentFighter', [['value' => $s['id'] , 'text' => '']]); 
			    			}
			    			else{
			    				echo $this->Form->radio('currentFighter', [['value' => $s['id'] , 'text' => '']]); 
			    			}
			    			?>
			    		</p>
			    		<p style="display : inline-block"><?= $s['name']." ";?></p>

			    	

			    		<?php /*<div class="switch tiny">
							<input class="switch-input" id="<?= "currentFighter".$s['id'] ?>" type="radio" checked name="currentFighter">
								<label class="switch-paddle" title="Se jeter dans l'arène" for="<?= "currentFighter".$s['id'] ?>">
						   			<!--<span class="show-for-sr" >Bulbasaur</span>-->
						  		</label>
						</div> */ ?>

						

						  	<!--echo $this->Form->button("<i class='icon-search'></i> Search", array('type' => 'submit','id' => 'search_button', 'class' => 'searchbutton', 'escape' => false))-->
						

			    	</h4>
			    	<!--Niveau (rouge si augmentations à prendre, gris sinon)-->
<!--CSS/!\-->
			    	<?php if($lvlup){ ?>
				 		<div class="label alert" style = "position : absolute; right : 10%; top : 30%; width : 13%; text-align : center">
				 	<?php } else{ ?>
				 		<div class="label secondary" style = "position : absolute; right : 10%; top : 30%; width : 13%; text-align : center">
				 	<?php } ?>
				 			lvl <?= $s['level']." ";?>
				 		</div>
				 </div>

				 <!--Message indicant qu'il faut augmenter les caractéristiques-->
				 <?php if($lvlup){ ?>
				 	<span class="label alert">Augmentez vos capacités !</span>
				 <?php } ?>

				<!--Progress bar pour l'XP-->
				<div class="warning progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
					<!--10/35/50/80/100% S'il reste 4/3/2/1/0 pts d'XP à prendre pour passer au niveau suivant-->
					<?php if($lvlup){ ?>
						<span class="progress-meter xpbar" style="width : 100%">
					<?php } else if($s['xp']%4 == 0){ ?>
						<span class="progress-meter xpbar" style="width : 10%">
					<?php } else if($s['xp']%4 == 1){ ?>
						<span class="progress-meter xpbar" style="width : 35%">
					<?php } else if($s['xp']%4 == 2){ ?>
						<span class="progress-meter xpbar" style="width : 50%">
					<?php } else if($s['xp']%4 == 3){ ?>
						<span class="progress-meter xpbar" style="width : 80%">
					<?php } ?>
					<!--Affiche l'XP totale dans la barre-->
						<p class="progress-meter-text">
						    <?= $s['xp']." ";?> XP
						</p>
					</span>
				</div>

				<!--Image du combattant : A MODIFIER-->
			 	<button class="clear button" type="button" title = "Cliquez pour changer d'avatar" data-toggle="changeAvatar<?= $s['name']; ?>">
			 		<?= $this->Html->image("../webroot/img/".$player_id."_".$s["id"].".jpg"); ?>

			 	</button>

			 	<!--Form (toggle) pour changer l'avatar-->
				<div class="dropdown-pane" id="changeAvatar<?= $s['name']; ?>" data-dropdown data-close-on-click="true" style="width : 30%">
					<p>Changez l'avatar de <?= $s['name']; ?> !</p>
					
					<!--<i>Saisissez un URL</i>-->
				  	<div class="input-group input-group-rounded">
					  	<!--<input class="input-group-field" type="search">-->
					  	<?php  	echo $this->Form->create('avatar',array('enctype'=>'multipart/form-data')); ?>

					  	<?php echo $this->Form->control('name',['style'=>'display:none','value'=>$s['id']]); 
					  	echo $this->Form->file('submittedimageupdatefighter'); ?>
					  	<div class="input-group-button">
					  		<?php echo $this->Form->submit(__('Sauvegarder'), ['class'=>'button secondary']); 
					  		echo $this->Form->end();?>
					  	</div>

					</div>
					<!--<i>Ou</i><input type="file" name="img">-->
				</div>

			 	

			 	
			 	<!--Card section : les caractéristiques-->
			  	<div class="card-section">
					<h5>Caractéristiques</h5>

					<!--Pour que les jauges soient d'une proportion logique-->
					<?php
						$width_sight = $s['skill_sight'];
						$width_strength = $s['skill_strength'];
						if($width_sight < 50 && $width_strength < 50)
						{
							$width_strength = $width_strength*2;
							$width_sight = $width_sight*2;
						}
						if($width_sight < 10) $width_sight = 10;
						else if($width_sight > 100) $width_sight = 100;

						if($width_strength < 10) $width_strength = 10;
						else if($width_strength > 100) $width_strength = 100;

						

						$width_health = ($s['current_health'] *100) / $s['skill_health'] ;
						if($width_health < 10) $width_health = 10;
						else if($width_health > 100) $width_health = 100;
						$sightbar = ' width : ' . $width_sight . '%';
						$strengthbar = ' width : ' . $width_strength . '%';
						$healthbar = ' width : ' . $width_health . '%';
					?>

				   <!--XY Grid : Skill+Jauge / Bouton augmentation-->
				   <!--Vue-->
				    <div class="grid-x">
				    	<div class="auto cell">	
						    <span>Vue</span> 
						    <!--Progress bar-->
							<div class="success progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
							 	<span class="progress-meter" style="<?php echo $sightbar; ?>">
								    <p class="progress-meter-text">
								    	<?= $s['skill_sight']." ";?>
								    </p>
							  	</span>
							</div>
						</div>
						<!--S'il y a des augmentations à prendre : apparition du bouton-->
						<?php if($lvlup){ ?>
							<div class="small-1 small-offset-1 cell">
								<!--Dans fighter(ArenasController) renvoie ensuite sur une fonction improveSkills(FightersTable) qui augmente la compétence et le niveau-->
								<?= $this->Form->PostButton('+1', ['controller' => 'Arenas','action' => 'fighter'], ['data' => ['skill' => 'sight', 'idFighter' => $s['id']],  'class' => 'button primary small improveSkill']); ?>
							</div>
						<?php } ?>
					</div>

					<!--Force-->
					<div class="grid-x">
				    	<div class="auto cell">	
						    <span>Force</span> 
						    <!--Progress bar-->
							<div class="success progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
							  	<span class="progress-meter" style="<?php echo $strengthbar; ?>">
								    <p class="progress-meter-text">
								    	<?= $s['skill_strength']." ";?>
								    </p>
							  	</span>
							</div>
						</div>
						<!--Bouton +1-->
						<?php if($lvlup){ ?>
						<div class="small-1 small-offset-1 cell">
							
							<?= $this->Form->PostButton('+1', ['controller' => 'Arenas','action' => 'fighter'], ['data' => ['skill' => 'strength', 'idFighter' => $s['id']], 'class' => 'button primary small improveSkill']); ?>

						</div>
						<?php } ?>
					</div>


					<!--PV-->
					<div class="grid-x">
				    	<div class="auto cell">	
						    <span>PV</span> 
						    <!--Progress bar-->
							<?php if($width_health > 50) { ?>
							<div class="success progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
							<?php } else if($width_health < 25) { ?>
							<div class="alert progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
							<?php } else { ?>
							<div class="warning progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
							<?php } ?>
							  	<span class="progress-meter" style="<?php echo $healthbar; ?>">
								    <p class="progress-meter-text">
								    	<?= $s['current_health']." ";?>/ <?= $s['skill_health']." ";?>
								    </p>
							  	</span>
							</div>
						</div>
						<!--Bouton +3 : skill+3 et currentPV = max-->
						<?php if($lvlup){ ?>
						<div class="small-1 small-offset-1 cell">
							
							<?= $this->Form->PostButton('+3', ['controller' => 'Arenas','action' => 'fighter'], ['data' => ['skill' => 'health', 'idFighter' => $s['id']], 'class' => 'button primary small improveSkill']); ?>
						</div>
						<?php } ?>
					</div>
			  	</div>
			</div>
		</div>
		<!--Fin de la boucle "pour tous les combattants"-->
		<?php
		} ?>
	<!--Fin de la grille XY pour les cartes-->
	</div>

	<!--Sinon : Créer un combattant-->
	<?php } //else { ?>
		<?php

			echo $this->Form->create('AddFighter', ['enctype'=>'multipart/form-data', 'id'=>'formnewfighter']);

		?>
		<div class="grid-x">
			<!--Carte Combattant-->
			<div class="small-10 small-offset-1 medium-8 medium-offset-2 large-6 large-offset-3 cell">
				<div class="card" style = "margin : 60px 0px">
					<!--Header de la carte-->
					<div class="card-divider" style="position : relative;">
						<?= "<h3>"."Créez un nouveau combattant"."</h3>"; ?>
					 </div>
						

				 	<!--Card section : les caractéristiques-->
				  	<div class="card-section">

				    	<h4 style= "width : 100%"><?= $this->Form->control('name',['id'=>'newname']);?></h4>
				    	<?php //echo $this->Form->control('',['id'=>'id','style'=>'display:none','value'=>$player_id]); ?>

					<!--IMAGE-->
					<div>
						<label class= "float-left">Avatar</label>
				  	<div class="input-group input-group-rounded">
					  	<?php echo $this->Form->file('submittedimagenewfighter',['id'=>'newavatar']); ?>
					</div>
				  	<?php 
				  	echo $this->Form->button('Créer', ['type'=>'submit','class' => 'button secondary']);
					echo $this->Form->end(); 

					  	?>
					</div>
				</div>
				
			</div>
		</div>

	<?php
			/* echo "<h4>Vous avez ".$num." combattants:</h4>";

			foreach($tous as $t){
				echo $t['name']." ";
			    //echo $this->html->link("$t['name']","controller" => Arenas, "action" =>fighter());
			}
			foreach($best as $best){
			echo "<p>Le meilleur combattant est: ".$best['name']." et son niveau est: ".$best['level']."<p>";
			}*/
		?>


	<?php// } ?>

	</div>

</div>

</div>
<?= $this->Html->script(['jquery.min','jquery','navigation']); ?>

