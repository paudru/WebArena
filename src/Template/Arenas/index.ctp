<?php $this->assign('title', 'index');?>

<figure class="hero">
  	<?= $this->Html->image('webArena.png', ['alt' => 'CakePHP']); ?>
  	<div class="wrap row text-center">
  		<?php echo $this->Html->link('Connexion', array('controller' => 'Players', 'action' => 'login'), ['class' => 'button primary large']); ?>
  	 	<?= $this->Html->image('blason.jpg', ['alt' => 'Blason']); ?>
    	<?php echo $this->Html->link('Inscription', array('controller' => 'Players', 'action' => 'add'), ['class' => 'button primary large']); ?>
  	</div>


</figure>

<div class="grid-x">
	<section class="media-object cell medium-8 medium-offset-2 small-10 small-offset-1 grid-x">
	  	<div class="media-object-section cell small-6">
	    	<div class="thumbnail">
	    		<?= $this->Html->image('imgIndex1.png', ['alt' => 'imgIndex1', 'style' => 'width : 100%']); ?>
	    	</div>
	  	</div>
	  	<div class="media-object-section cell small-8">
	    	<h4>Entrez dans l'arène</h4>
	    	<p>Venez combattre vos ennemis, évoluez dans l'univers fantastique de WebArena. Déjouez les nombreux pièges qui pourront vous coûter la vie et tentez de tuer le monstre de l'arène avant qu'il ne vous attrape...</p>
	  	</div>
	</section>

	<section class="media-object cell medium-8 medium-offset-2 small-10 small-offset-1 grid-x">
	  	<div class="media-object-section cell small-8">
	    	<h4>Menez votre combattant jusqu'à la victoire</h4>
	    	<p>Créez votre propre combattant ! En tuant vos ennemis, vous gagnerez de l'expérience. Vous pourrez alors augmenter vos capacités ! Améliorez votre vue pour percevoir vos ennemis de plus loin. Augmentez votre force pour tuer plus rapidement. Gagnez des points de vie pour vivre plus longtemps.</p>
	  	</div>
	  	<div class="media-object-section cell small-6">
	   		<div class="thumbnail">
	      		<?= $this->Html->image('imgIndex2.png', ['alt' => 'imgIndex2', 'style' => 'width : 100%']); ?>
	    	</div>
	  	</div>
	</section>


	<section class="media-object cell medium-8 medium-offset-2 small-10 small-offset-1 grid-x">
	  	<div class="media-object-section cell small-6">
	    	<div class="thumbnail">
	      		<?= $this->Html->image('imgIndex3.png', ['alt' => 'imgIndex3', 'style' => 'width : 100%']); ?>
	    	</div>
	  	</div>
	  	<div class="media-object-section cell small-8">
	    	<h4>Faites partie d'une communauté active</h4>
	    	<p>Chaque jour, vous pourrez voir ce que les autres joueurs ont combattu pendant votre absence. Vous serez au courant de tout !</p>
	  	</div>
	</section>
</div>