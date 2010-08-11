<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

// check config
if(!file_exists('./config.php')){echo 'Please rename config.default.php to config.php and edit its value\'s';}

// is posted
if( $_GET['proceed'] )
{

      $configinhoud = " <?
      mysql_connect('".$dbhost."','".$dbuser."','".$dbpass."');
      mysql_select_db('".$dbdata."');
                                ?>
                              ";
                   // Deze data wordt zo dadelijk
                   // in config.php geschreven
                   // Je kan natuurlijk altijd
                   // meer tekst erin schrijven!
        fwrite($openconfig, $configinhoud);
           // De data is er nu ingeschreven
           // Dus nu gaan we het bestand
           // weer netjes sluiten!
         fclose($openconfig);

        /* Nu we de config data in config.php
           hebben geschreven kunnen we de MySQL
           queries invoeren, natuurlijk moet
           je die wel aanpassen aan je eigen
           site!
 
           Als voorbeeld neem ik dit keer een
           nieuwssysteem..
           Maar eerst moeten we natuurlijk weer een
           next knopje toevoegen! */
         echo "De info is toegevoegd aan config.php <BR>";
         echo "<form action=./install.php method=post>";
         echo "<input type=submit name=finish value=Voltooien>";
         echo "</form>";
}
else
{
	echo '<form method="post" action="?finish">
		<table width="auto" height="auto" border="0">
        <tr>
			<td>File access pasword :</td>
			<td><input type="text" name="file_access"></td>
		</tr>
        </table>';
}

?>