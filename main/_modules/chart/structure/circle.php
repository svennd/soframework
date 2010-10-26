<?php
// http://wmcity.nl/scripts.php?actie=bekijk&id=2019

class Grafiek
{
    private $sTitel,
            $aData,
            $aKleuren,
            $afbeelding,
            $iGraden;
    
    /**
    * Constructor van Grafiek
    * @param String $sTitel, de titel van de grafiek
    * @param Array $aData, een array met de gegevens bestaande uit ( String TitelVanGegeven, Integer Getal )
    */    
    public function __construct( $sTitel, $aData ) {
        foreach( $aData as $iData ) {
            if( !ctype_digit( $iData[1] ) ) {
                throw new Exception( 'Er kunnen alleen numerieke waardes ingevoerd worden' );
            }
        }
        if( count($aData) > 10 ) {
            throw new Exception( 'Er kunnen maximimaal 10 keys worden gebruikt' );
        }
        else {
            $this->sTitel = $sTitel;
            $this->aData = $aData;
            $this->aKleuren = array();
            $this->iGraden = 0;
            $this->afbeelding = imagecreatetruecolor( 500, 300 );
            
            $this->getData();
            $this->maakKleuren();
            imagefill( $this->afbeelding, 0, 0, $this->aKleuren[1] );
        }
    }
    
    /**
    * Destructor van Grafiek, vernietigd de afbeelding
    */    
    public function __destruct() {
        imagedestroy( $this->afbeelding );
    }
    
    /**
    * Publieke methode om aantal graden te setten, deze methode is optioneel
    * @param Integer $iGraden
    */    
    public function setGraden( $iGraden ) {
        if( !ctype_digit( $iGraden ) ) {
            throw new Exception( 'Er kan alleen een numerieke waarde ingevoerd worden' );
        }
        elseif( $iGraden > 90 ) {
            throw new Exception( 'Vul een waarde in die kleiner ofgelijk aan 90 is' );
        }
        else {
            $this->iGraden = $iGraden;
        }
    }
    
    
    /**
    * Prive methode om alle gegevens uit te rekenen
    * - Begin graden van elk gegeven
    * - Eind graden van elk gegeven
    * - Aantal procent van het totaal
    */    
    private function getData() {
    
        $totaal = 0;
        foreach( $this->aData as $aData ) {
            $totaal += $aData[1];
        }
                
        for( $i = 0; $i < count( $this->aData ); $i++ ) {
            $percentage = $this->aData[$i][1] / ( $totaal / 100 );
            $percentage = round( $percentage, 1 );
            $this->aData[$i][0] .= ' ('.$percentage.' %)';

            $graden = $this->aData[$i][1] / ( $totaal / 360 );
            
            if($i == 0) {
                $this->aData[$i][] = 0;         // Begin graden
                $this->aData[$i][] = $graden;     // Eind graden
            }
            else {
                $this->aData[$i][] = $this->aData[$i-1][3];             // Begin graden
                $this->aData[$i][] = $this->aData[$i-1][3] + $graden;    // Eind graden
            }
        }
    }

    /**
    * Prive methode om alle kleuren te maken
    */    
    private function maakKleuren() {
    
        $Kleuren[] = '000000'; // Zwart
        $Kleuren[] = 'FFFFFF'; // Wit
        $Kleuren[] = '0000FF'; // Blauw
        $Kleuren[] = '00FFFF'; // Cyaan
        $Kleuren[] = 'FF00FF'; // Paars ( Magenta )
        $Kleuren[] = '00FF00'; // Groen
        $Kleuren[] = 'FF9900'; // Oranje
        $Kleuren[] = 'FF0000'; // Rood
        $Kleuren[] = 'FFFF00'; // Geel
        $Kleuren[] = 'A52A2A'; // Bruin
        $Kleuren[] = 'FFC0CB'; // Roze
        $Kleuren[] = '006400'; // Donkergroen
        
        foreach( $Kleuren as $sKleur ) {
            $aKleur = str_split( $sKleur, 2 );
            $this->aKleuren[] = imagecolorallocate( $this->afbeelding, hexdec( '0x'.$aKleur[0] ), hexdec( '0x'.$aKleur[1] ), hexdec( '0x'.$aKleur[2] ) );
        }
    }
    
    /**
    * Prive methode om de legenda te tekenen met hokjes en naam van elk gegeven
    */    
    private function tekenLegenda() {
        for( $i = 0; $i < count( $this->aData ); $i++ ) {
            $x = 260;
            
            imagefilledrectangle( $this->afbeelding , $x, ($i*20) + 55, $x+10, ($i * 20 + 65), $this->aKleuren[$i+2]  );
            imagerectangle( $this->afbeelding, $x, ($i * 20) + 55, $x+10, ($i * 20 + 65), $this->aKleuren[0] );
            imagestring( $this->afbeelding, 3, $x+15, ($i * 20 + 54), $this->aData[$i][0], $this->aKleuren[0] );
        }
    }
    
    /**
    * Prive methode om de cirkel met alle gegevens te tekenen
    */    
    private function tekenCirkel() {
        $y = sin(deg2rad(90 - $this->iGraden)) * 200;
        
        if( $this->iGraden > 0 ) {
            for( $i = 0; $i < count( $this->aData ); $i++ ) {
                
                for( $b = 15; $b > 0; $b-- ) {
                    imagefilledarc( $this->afbeelding, 120, (150 + $b), 200, $y , $this->aData[$i][2], $this->aData[$i][3], $this->aKleuren[$i+2], IMG_ARC_PIE );
                }
            }
            imagearc( $this->afbeelding, 120, 150+15, 200, $y , 0, 0, $this->aKleuren[0] );
        }
        
        for( $i = 0; $i < count( $this->aData ); $i++ ) {
            imagefilledarc( $this->afbeelding, 120, 150, 200, $y, $this->aData[$i][2], $this->aData[$i][3], $this->aKleuren[$i+2], IMG_ARC_PIE );
        }
        imagearc( $this->afbeelding, 120, 150, 200, $y, 0, 0, $this->aKleuren[0] );
    }
    
    /**
    * Publieke functie om de hele afbeelding te generenen met alle data erop
    */    
    public function printAfbeelding() {
        imagestring( $this->afbeelding, 5, ((500 / 2) - strlen($this->sTitel) * 6), 10, $this->sTitel, $this->aKleuren[0] );
        $this->tekenCirkel();
        $this->tekenLegenda();
        
        header("Content-Type: image/png");
        imagepng( $this->afbeelding );
    } 
}


try {
    $aData = array(
    array('Nintendo Wii', 8178979),
    array('Xbox260', 10125265),
    array('Sony PSP', 11094851),
    array('GameCube', 12736074),
    array('Gameboy Advanced', 41393552),
    array('Nintendo DS', 20120614),
    array('Playstation 2', 46565604),
    array('Playstation 3', 3363522)
    );
    
    $g = new Grafiek( "Game Consoles Verkocht", $aData );
    $g->setGraden('45');  // Optionele methode, standaard word hij plat weergegeven
    $g->printAfbeelding();
}
catch( Exception $e ) {
    die( $e->getMessage() );
} 
?>