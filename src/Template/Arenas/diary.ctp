<?php $this->assign('title', 'diary');?>
<table>
  <thead>
    <tr>
      <th>Message</th>
      <th>Date</th>
      <th>Position</th>
    </tr>
  </thead>
  <tbody>

  <?php 
  if($vide == 0){
  	foreach ($array as $a) {
    echo "<tr><td>".$a['name']."</td><td>".$a['date']."</td><td>".$a['coordinate_x']." - ".$a['coordinate_y']."</td></tr>";
  }
	}else
  if($vide == 2){
  	
  	

  	echo "<tr><td>"."Rien à afficher"."</td><td>"." </td><td>"." "."</td></tr>";
  
  }
  
    ?>
  </tbody>
</table>
