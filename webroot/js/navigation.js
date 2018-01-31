$(document).ready(function() {
	$('#switch-form').click(function(){
	   $('.card').animate({height: "toggle", opacity: "toggle"}, "slow");
	});
});


$("input[name='currentFighter']").change(function(){
	var currentfighter = $(this).val();
    $.ajax({  
		type: 'POST',
	    url: '../arenas/fighter',
	    data: {currentfighter: currentfighter},
	    dataType: 'json',        
	    // 2 . En cas de succès, modification de la grille
        success: function (response) {
            console.log(response);
        },
        //3. En cas d'erreur, afficher le statut et l'erreur
        error:function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});

$(document).on('click', '.sendmov', function(){
    // Récuperation de la direction
    var dir = $(this).attr('data-dir'); 
    console.log(dir);
    var useActionPt = 1;
   // Requete AJAX
    $.ajax({  
		type: 'POST',
	    url: '../arenas/sight',
	    data: {dir: dir},
	    dataType: 'json',        
	    // 2 . En cas de succès, modification de la grille
        success: function (response) {
            successAction(response, useActionPt);
        },
        //3. En cas d'erreur, afficher le statut et l'erreur
        error:function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});

$(document).on('click', '.sendatta', function(){
    // Récuperation de la direction de l'attaque
    var atta = $(this).attr('data-atta'); 
    console.log(atta);
    var useActionPt = 1;
       
   // Requete AJAX
    $.ajax({  
		type: 'POST',
	    url: '../arenas/sight',
	    data: {atta: atta},
	    dataType: 'json',        
	    // 2 . En cas de succès, modification de la grille
        success: function (response) {
	        successAction(response, useActionPt);
        },
        //3. En cas d'erreur, afficher le statut et l'erreur
        error:function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});

$(document).on('click', '.reinitobs', function(){
    // Récuperation de la direction de l'attaque
    var obs = $(this).attr('data-obs'); 
    console.log(obs);
    var useActionPt = 0;
   // Requete AJAX
    $.ajax({  
		type: 'POST',
	    url: '../arenas/sight',
	    data: {obs: obs},
	    dataType: 'json',        
	    // 2 . En cas de succès, modification de la grille
        success: function (response) {
	        successAction(response, useActionPt);
        },
        //3. En cas d'erreur, afficher le statut et l'erreur
        error:function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

});

function successAction(response, useActionPt){
	console.log(response);
    var sites = response.sites;
    var fbypos = response.fbypos;
    /*var val = document.getElementById("progress").value;
	val = val - response.tpsrecup;
	document.getElementById("progress").value=val; */

	//Si erreur de pts d'action, on affiche directement l'erreur
    if(sites==="ptaction"){
		document.getElementById('arene-errors').innerHTML='<div data-abide-error role="alert" class="alert callout" style="display: block;"><p><i class="fi-alert"></i>Vous n\'avez pas assez de points d\'action. Patience !</p></div>';
	}else{
		//On réaffiche la grille
        for(i=0; i<15;i++){
        	for(j=0; j<10;j++){
        		if(sites[i][j]=='B'){
        			var n = Math.floor((Math.random() * 3) + 1);
        			$('#'+i+'_'+j).attr('src', '../img/Fog'+n+'.png');
        		}else if(sites[i][j]=='A'){
        			$('#'+i+'_'+j).attr('src', '../img/vide.jpg');
        		}else if(sites[i][j]=='P'){
        			$('#'+i+'_'+j).attr('src', '../img/castle.png');
        		}else if(sites[i][j]=='F'){

        			for(k=0; k<fbypos.length; k++){
        				if(fbypos[k]['coordinate_x']==i && fbypos[k]['coordinate_y']==j){
        					$('#'+i+'_'+j).attr('src', '../img/'+fbypos[k]['player_id']+'_'+fbypos[k]['id']+'.jpg');
        					$('#'+i+'_'+j).attr('alt', fbypos[k]['name']);

        				}
        			}

        		}
            }
        }
        //On affiche les avertissements des pièges et monstres
        if(response.alert!==""){
    		document.getElementById('arene-errors').innerHTML='<div data-abide-error role="alert" class="alert callout" style="display: block;"><p style="color : red;"><i class="fi-alert"></i>'+response.alert+'</p></div>';
        }else{
        	document.getElementById('arene-errors').innerHTML='';
        }
        //Si le combattant est mort, on rafraichit la page
        if(response.isdead){
        	location.reload();
        }
    }
    if(useActionPt==1 && sites!=="ptaction"){
    	var time = response.time;
    	var newtime = time-response.tpsrecup;
    	if(newtime>=0){
    		//document.getElementById("progress").value=newtime;
            var width_time = newtime/(response.ptmax*response.tpsrecup)*100;
            var timebar = "width : " + width_time + "%";
            $("#progress").attr("style",timebar);
    		document.getElementById("actionpt").innerHTML = "Vous avez " + Math.floor(newtime/response.tpsrecup) + " point(s) d\'action";
    	}else{
    		//document.getElementById("progress").value=0;
            $("#progress").attr("style","width : 0%");
    		document.getElementById("actionpt").innerHTML = "Vous avez 0 point(s) d\'action";
    		newtime=0;
    	}
    	clearInterval(inter);
    	inter = setInterval( function(){
        var seconds = pad(++newtime);
        if(seconds<=(response.ptmax*response.tpsrecup)){
            var width_time = seconds/(response.ptmax*response.tpsrecup)*100;
            var timebar = "width : " + width_time + "%";
            $("#progress").attr("style",timebar);
            document.getElementById("actionpt").innerHTML = "Vous avez " + Math.floor(seconds/response.tpsrecup) + " point(s) d\'action";
        }else{
            document.getElementById("actionpt").innerHTML = "Vous avez "+response.ptmax+" point(s) d\'action";
        }

    }, 1000);
    }
}

