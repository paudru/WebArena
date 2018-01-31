<?php $this->assign('title', 'Inscription'); ?>


<div class="users form hero2p">
	<!--Carte-->
	<div class="grid-x">
		<!--Je sais pas pourquoi, mais ça fait de la merde au niveau de la taille-->
		<div class="small-10 small-offset-1 large-12 large-offset-0 cell">
			<div class="card" >
				<!--Header de la carte-->
				<div class="card-divider">
					<h3 class="float-center">Inscription</h3>
				</div>
				<!--Card section-->
				<div class="card-section">
					<!--Form-->
					<?= $this->Form->create($user) ?>
				    <fieldset>
				        <?= $this->Form->control('email', ['placeholder' => 'yourAccount@example.com']) ?>
				        <?= $this->Form->control('password', ['placeholder' => 'password']) ?>
				    </fieldset>

				    <!--Message d'erreur-->
				    <?= $this->Flash->render(); ?>
					
					<?= $this->Form->button(__('Ajouter'), [ 'class' => 'button secondary float-center' , 'style' => 'margin-top : 20px']); ?>
					<?= $this->Form->end(); ?>

                    <?php echo $this->Html->link("J'ai déjà un compte", array('controller' => 'Players', 'action' => 'login')); ?>
			</div>
		</div>
	</div>
</div>


