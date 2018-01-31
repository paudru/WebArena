<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$WebArenaDescription = "WebArena : Jeu d'arène";
?>
<!DOCTYPE html>

<html>

    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?= $this->fetch('meta') ?>
        
        <title>
            <?= $WebArenaDescription ?> - <?= $this->fetch('title') ?>
        </title>

        <!--Favicon-->
        <?php echo $this->Html->meta('icon', '/img/faviconECE.png', ['type'=>'image/png']); ?>
        
        <!--CSS-->
        <?= $this->Html->css('foundationNew') ?>
        <?= $this->Html->css('style.css.php?php'); ?>
        <?= $this->fetch('css') ?>
        <!-- Insert this within your head tag and after foundation.css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/motion-ui/1.1.1/motion-ui.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    </head>
    <body>
        
        <!--Fixed nav-->
        <nav data-sticky-container>
            <div class="title-bar" data-sticky data-options="marginTop:0;" style="width:100%">
                <div class="top-bar-left title-bar-left">
                    <ul class="menu">
                        <li>
                            <?= $this->Html->image("WebArena.png", [
                                "alt" => "WebArena",
                                'url' => ['controller' => 'Arenas', 'action' => 'index'],
                                'style' => 'height:20px'
                            ]); ?>
                        </li>
                        <li><?php echo $this->Html->link('Combattant', array('controller' => 'Arenas', 'action' => 'fighter')); ?></li>
                        <li><?php echo $this->Html->link('Vision', array('controller' => 'Arenas', 'action' => 'sight')); ?></li>
                        <li><?php echo $this->Html->link('Journal', array('controller' => 'Arenas', 'action' => 'diary')); ?></li>
                        <li>
                                <?php echo $this->Html->link('<i class="fa fa-sign-out" title="Déconnexion" style = "color : grey"></i>', 
                                array('controller' => 'Players', 'action' => 'logout'), 
                                array('escape' => false)); ?>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>



        <!--va chercher le contenu dans les .ctp des =/= pages-->
        <div class="container clearfix" >
            <!--Messages (success ou error)-->
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>


        <!--Footer-->
        <footer style="background-color : #0C0D0B ; color : grey; padding : 20px 0px; border-top : solid thin black; font-size : 13px">
            <div class="grid-x grid-padding-x small-up-11">
                <div class="cell medium-offset-1 medium-3" style = "border-right : solid thin black;">
                    <h5 class="subheader">Groupe SI2</h5>
                    <ul class="no-bullet">
                        <li>Jean BABIN</li>
                        <li>Caroline BEGUINOT</li>
                        <li>Pauline DRUESNE</li>
                    </ul>  
                    <!--Checker si c'est le bon lien ?-->
                    <span> Accès au versionning : </span> <?= $this->Html->link('Ici','/versions.log');?>
                </div>
                <div class="cell medium-4" style = "border-right : solid thin black;">
                    <h5 class="subheader">Options</h5>
                    <ol class="no-bullet">
                        <li>D <i>(Gestion d'éléments du décors)</i></li>
                        <li>G <i>(Foundation 6)</i></li>
                        <li>C <i>(Gestion d'une limite temporelle)</i><p>Pour modifier les variables de temps : AppController.php l97-l98</li>
                        <li>A <i>(Partie Gestion avancée des combattants)</i></li>
                        <li>Bonus : <?php echo $this->Html->link('Mise en ligne sur Internet', 'https://jeanbabin.000webhostapp.com/'); ?></li>
                    </ol>
                </div>
                <div class="cell medium-3">
                    <h5 class="subheader">Pages</h5>
                    <ul class="no-bullet">
                        <li><?php echo $this->Html->link('Index', array('controller' => 'Arenas', 'action' => 'index')); ?></li>
                        <li><?php echo $this->Html->link('Connexion', array('controller' => 'Players', 'action' => 'login')); ?></li>
                        <li><?php echo $this->Html->link('Inscription', array('controller' => 'Players', 'action' => 'add')); ?></li>
                        <li><?php echo $this->Html->link('Combattant', array('controller' => 'Arenas', 'action' => 'fighter')); ?></li>
                        <li><?php echo $this->Html->link('Vision', array('controller' => 'Arenas', 'action' => 'sight')); ?></li>
                        <li><?php echo $this->Html->link('Journal', array('controller' => 'Arenas', 'action' => 'diary')); ?></li>
                    </ul>
                </div>
            </div>
        </footer>



        <!--Scripts JS-->
        <?= $this->Html->script('vendor/jquery'); ?>
        <?= $this->Html->script('vendor/what-input'); ?>
        <?= $this->Html->script('vendor/foundation'); ?>
        <?= $this->Html->script('app'); ?>
        <?= $this->fetch('script') ?>

    </body>
</html>
