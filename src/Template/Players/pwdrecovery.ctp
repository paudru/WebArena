<?php $this->assign('title', 'Récupération du mot de passe'); ?>


<div class="users form hero2p">
	<!--Carte-->
	<div class="grid-x">
		<div class="small-10 small-offset-1 large-12 large-offset-0 cell">
			<div class="card" >
				<!--Header de la carte-->
				<div class="card-divider">
					<h3 class="float-center">Récupération du mot de passe</h3>
				</div>
				<!--Card section-->
				<div class="card-section">
					<!--Form-->
					<?= $this->Form->create(); ?>
				    <fieldset>
				        <?= $this->Form->control('email', ['placeholder' => 'yourAccount@example.com']); ?>
				    </fieldset>

				    <!--Message d'erreur-->
				    <?= $this->Flash->render(); ?>
					
					
					<a id='switch-form' href="#">Changer le mot de passe </a>
				</div>
			</div>
			<div class="card" style="display:none;">
				<!--Header de la carte-->
				<div class="card-divider">
					<h3 class="float-center">Nouveau mot de passe</h3>
				</div>
				<!--Card section-->
				<div class="card-section">
					<!--Form-->
					
				    <fieldset>
				        <?= $this->Form->control('password'); ?>
				    </fieldset>

				    <!--Message d'erreur-->
				    <?= $this->Flash->render(); ?>
					
					<?= $this->Form->submit(__('Enregistrer'), [ 'class' => 'button secondary float-center' , 'style' => 'margin-top : 20px']); ?>
					<?= $this->Form->end(); ?>
				</div>
			</div>
			
		</div>
	</div>
</div>
<?= $this->Html->script(['jquery.min','navigation']); ?>