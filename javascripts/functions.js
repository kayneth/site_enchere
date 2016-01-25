var connexion = '';

function compte_a_rebours(fin)
{

	var date_actuelle = new Date();
	var date_evenement = new Date(fin);
	var total_secondes = (date_evenement - date_actuelle) / 1000;
	var ench;

	var prefixe = "Enchères terminées dans ";
	if (total_secondes < 0)
	{
		prefixe = "Enchères terminées il y a "; // On modifie le préfixe si la différence est négatif

		total_secondes = Math.abs(total_secondes); // On ne garde que la valeur absolue

	}

	if (total_secondes > 0)
	{
		var jours = Math.floor(total_secondes / (60 * 60 * 24));
		var heures = Math.floor((total_secondes - (jours * 60 * 60 * 24)) / (60 * 60));
		minutes = Math.floor((total_secondes - ((jours * 60 * 60 * 24 + heures * 60 * 60))) / 60);
		secondes = Math.floor(total_secondes - ((jours * 60 * 60 * 24 + heures * 60 * 60 + minutes * 60)));

		var et = "et";
		var mot_jour = "jours,";
		var mot_heure = "heures,";
		var mot_minute = "minutes,";
		var mot_seconde = "secondes";

		if (jours == 0)
		{
			jours = '';
			mot_jour = '';
		}
		else if (jours == 1)
		{
			mot_jour = "jour,";
		}

		if (heures == 0)
		{
			heures = '';
			mot_heure = '';
		}
		else if (heures == 1)
		{
			mot_heure = "heure,";
		}

		if (minutes == 0)
		{
			minutes = '';
			mot_minute = '';
		}
		else if (minutes == 1)
		{
			mot_minute = "minute,";
		}

		if (secondes == 0)
		{
			secondes = '';
			mot_seconde = '';
			et = '';
		}
		else if (secondes == 1)
		{
			mot_seconde = "seconde";
		}

		if (minutes == 0 && heures == 0 && jours == 0)
		{
			et = "";
		}

		ench = prefixe + jours + ' ' + mot_jour + ' ' + heures + ' ' + mot_heure + ' ' + minutes + ' ' + mot_minute + ' ' + et + ' ' + secondes + ' ' + mot_seconde;
	}

	return ench;
}


function affichTps(){
	var fins = document.getElementsByClassName("tps");
	finsLen = fins.length;
	for(var i = 0; i < finsLen; i++){
		calcfin = compte_a_rebours(fins[i].innerHTML);
		fins[i].innerHTML = calcfin;
	}
	
}

affichTps();
//setInterval(affichTps, 1000);




