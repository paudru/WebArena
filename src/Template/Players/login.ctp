<?php $this->assign('title', 'Connexion'); ?>


<div class="users form hero2p">
	<!--Carte-->
	<div class="grid-x">
		<div class="small-10 small-offset-1 medium-10 medium-offset-1 large-12 large-offset-0 cell">
			<div class="card" >
				<!--Header de la carte-->
				<div class="card-divider">
					<h3 class="float-center">Connexion</h3>
				</div>
				<!--Card section-->
				<div class="card-section">
					<!--Form-->
					<?= $this->Form->create(); ?>
				    <fieldset>
				        <legend><?= __("Merci de saisir votre adresse e-mail et mot de passe"); ?></legend>
				        <?= $this->Form->input('email', ['placeholder' => 'yourAccount@example.com']); ?>
				        <?= $this->Form->input('password', ['placeholder' => 'password']); ?>
				    </fieldset>

				    <!--Message d'erreur-->
				    <?= $this->Flash->render(); ?>
					
					<?= $this->Form->button(__('Se Connecter'), [ 'class' => 'button secondary float-center' , 'style' => 'margin-top : 20px']); ?>
					<?= $this->Form->end(); ?>

                    <?php echo $this->Html->link("Pas encore de compte ?", array('controller' => 'Players', 'action' => 'add')); ?>
                    <br>
                    <?php echo $this->Html->link("Mot de passe oublié ?", array('controller' => 'Players', 'action' => 'pwdrecovery')); ?>
			</div>
		</div>
	</div>
</div>
