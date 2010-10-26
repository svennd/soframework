<h3>Resultaten</h3>
<?php
$c = $data;
$b = array_reverse($c);
$a = $b['0'];
?>
<div id='right_top'>
	<ul>
		<li><a href='#first_account'>Eerste volgaccount</a></li>
		<li><a href='#second_account'>Tweede volgaccount (huidig)</a></li>
	</ul>
</div>

<marquee name='rolbar' width='600'>
Ons dagresultaat op <?php echo date ("d/m", $fetch['last_edit']); ?> was <span style='<?php echo ($fetch['value'] >= 0) ? "color:#009900;" : "color:red" ; ?>'><b><?php echo $fetch['value']; ?></b></span> % ! ||
Op <?php echo date ("d/m", $fetch['last_edit']); ?> hebben we <b><?php echo number_format(floor($a['cumul_euro'] * ($fetch['value'] / 100)), 0, ',', '.'); ?></b>,- euro winst gemaakt! ||
Ons startbedrag bedroeg <b><?php echo number_format($start_bedrag, 0, ',', '.'); ?></b>,- euro, dat is deze maand <b><?php echo number_format(floor($a['cumul_euro']), 0, ',', '.'); ?></b>,- euro (<span style='<?php echo ($fetch['value'] >= 0) ? "color:#009900;>' > +" : "color:red' > -" ; ?><?php echo number_format(floor($a['cumul_euro'] - $start_bedrag ), 0, ',', '.'); ?></span>,- euro) 
</marquee>
<!-- start_blok name=eerste_volg_account -->
<h4><a name="first_account"></a>Ons eerste volgaccount;</h4>
<ul>
	<li>Start in april 2008 met een startbedrag van 15.000 &euro; en loopt tot april 2010.</li>

	<li>Broker: MBTrading (USA)</li>
	<li>Wegens onvoldoende liquiditeit &amp; volume genoodzaakt om de samenwerking met MBTrading stop te zetten.</li>
</ul>
<!-- end_blok -->
<div id="tabs">
    <ul>
        <li><a href="#fragment-0"><span>Tabel</span></a></li>
        <li><a href="#fragment-1"><span>Winst</span></a></li>

        <li><a href="#fragment-2"><span>% Winst</span></a></li>
        <li><a href="#fragment-3"><span>Cumul</span></a></li>
        <li><a href="#fragment-4"><span>% Cumul</span></a></li>
    </ul>
	<div id="fragment-0">
		<table>
			<tr>

				<td colspan='2' width='20%'>&nbsp;</td>
				<td>&nbsp;</td>
				<td>winst</td>
				<td>cumul</td>
				<td>winst</td>
				<td>cumul</td>
			</tr>

			<tr>
				<td colspan='2'><b>Startbedrag</b></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><span style='color:#333366;'>15.000 &euro;</span></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

		<tr><td align='right'>Apr</td><td style=' border-right: 1px solid grey;'>'08</td><td>&nbsp;</td><td><span style='color:#cc0033;'>1.687 &euro;</span></td><td><span style='color:#333366;'>16.531 &euro;</span></td><td><span style='color:#ff9900;'>10.21 %</span></td><td><span style='color:#009900;'>10 %</span></td></tr><tr><td align='right'>Mei</td><td style=' border-right: 1px solid grey;'>'08</td><td>&nbsp;</td><td><span style='color:#cc0033;'>1.882 &euro;</span></td><td><span style='color:#333366;'>18.237 &euro;</span></td><td><span style='color:#ff9900;'>10.32 %</span></td><td><span style='color:#009900;'>21 %</span></td></tr><tr><td align='right'>Jun</td><td style=' border-right: 1px solid grey;'>'08</td><td>&nbsp;</td><td><span style='color:#cc0033;'>2.209 &euro;</span></td><td><span style='color:#333366;'>20.229 &euro;</span></td><td><span style='color:#ff9900;'>10.92 %</span></td><td><span style='color:#009900;'>34 %</span></td></tr><tr><td align='right'>Jul</td><td style=' border-right: 1px solid grey;'>'08</td><td>&nbsp;</td><td><span style='color:#cc0033;'>1.962 &euro;</span></td><td><span style='color:#333366;'>22.031 &euro;</span></td><td><span style='color:#ff9900;'>8.91 %</span></td><td><span style='color:#009900;'>46 %</span></td></tr><tr><td align='right'>Aug</td><td style=' border-right: 1px solid grey;'>'08</td><td>&nbsp;</td><td><span style='color:#cc0033;'>2.289 &euro;</span></td><td><span style='color:#333366;'>24.122 &euro;</span></td><td><span style='color:#ff9900;'>9.49 %</span></td><td><span style='color:#009900;'>60 %</span></td></tr><tr><td align='right'>Sep</td><td style=' border-right: 1px solid grey;'>'08</td><td>&nbsp;</td><td><span style='color:#cc0033;'>2.332 &euro;</span></td><td><span style='color:#333366;'>26.264 &euro;</span></td><td><span style='color:#ff9900;'>8.88 %</span></td><td><span style='color:#009900;'>75 %</span></td></tr><tr><td align='right'>Okt</td><td style=' border-right: 1px solid grey;'>'08</td><td>&nbsp;</td><td><span style='color:#cc0033;'>1.278 &euro;</span></td><td><span style='color:#333366;'>27.485 &euro;</span></td><td><span style='color:#ff9900;'>4.65 %</span></td><td><span style='color:#009900;'>83 %</span></td></tr><tr><td align='right'>Nov</td><td style=' border-right: 1px solid grey;'>'08</td><td>&nbsp;</td><td><span style='color:#cc0033;'>1.485 &euro;</span></td><td><span style='color:#333366;'>28.898 &euro;</span></td><td><span style='color:#ff9900;'>5.14 %</span></td><td><span style='color:#009900;'>92 %</span></td></tr><tr><td align='right'>Dec</td><td style=' border-right: 1px solid grey;'>'08</td><td>&nbsp;</td><td><span style='color:#cc0033;'>1.533 &euro;</span></td><td><span style='color:#333366;'>30.357 &euro;</span></td><td><span style='color:#ff9900;'>5.05 %</span></td><td><span style='color:#009900;'>102 %</span></td></tr><tr><td align='right'>Jan</td><td style=' border-right: 1px solid grey;'>'09</td><td>&nbsp;</td><td><span style='color:#cc0033;'>1.338 &euro;</span></td><td><span style='color:#333366;'>31.641 &euro;</span></td><td><span style='color:#ff9900;'>4.23 %</span></td><td><span style='color:#009900;'>110 %</span></td></tr><tr><td align='right'>Feb</td><td style=' border-right: 1px solid grey;'>'09</td><td>&nbsp;</td><td><span style='color:#cc0033;'>1.460 &euro;</span></td><td><span style='color:#333366;'>33.040 &euro;</span></td><td><span style='color:#ff9900;'>4.42 %</span></td><td><span style='color:#009900;'>120 %</span></td></tr><tr><td align='right'>Mar</td><td style=' border-right: 1px solid grey;'>'09</td><td>&nbsp;</td><td><span style='color:#cc0033;'>1.413 &euro;</span></td><td><span style='color:#333366;'>34.398 &euro;</span></td><td><span style='color:#ff9900;'>4.11 %</span></td><td><span style='color:#009900;'>129 %</span></td></tr><tr><td align='right'>Apr</td><td style=' border-right: 1px solid grey;'>'09</td><td>&nbsp;</td><td><span style='color:#cc0033;'>2.513 &euro;</span></td><td><span style='color:#333366;'>36.751 &euro;</span></td><td><span style='color:#ff9900;'>6.84 %</span></td><td><span style='color:#009900;'>145 %</span></td></tr><tr><td align='right'>Mei</td><td style=' border-right: 1px solid grey;'>'09</td><td>&nbsp;</td><td><span style='color:#cc0033;'>726 &euro;</span></td><td><span style='color:#333366;'>37.464 &euro;</span></td><td><span style='color:#ff9900;'>1.94 %</span></td><td><span style='color:#009900;'>149 %</span></td></tr><tr><td align='right'>Jun</td><td style=' border-right: 1px solid grey;'>'09</td><td>&nbsp;</td><td><span style='color:#cc0033;'>694 &euro;</span></td><td><span style='color:#333366;'>38.146 &euro;</span></td><td><span style='color:#ff9900;'>1.82 %</span></td><td><span style='color:#009900;'>154 %</span></td></tr><tr><td align='right'>Jul</td><td style=' border-right: 1px solid grey;'>'09</td><td>&nbsp;</td><td><span style='color:#cc0033;'>695 &euro;</span></td><td><span style='color:#333366;'>38.828 &euro;</span></td><td><span style='color:#ff9900;'>1.79 %</span></td><td><span style='color:#009900;'>158 %</span></td></tr><tr><td align='right'>Aug</td><td style=' border-right: 1px solid grey;'>'09</td><td>&nbsp;</td><td><span style='color:#cc0033;'>796 &euro;</span></td><td><span style='color:#333366;'>39.609 &euro;</span></td><td><span style='color:#ff9900;'>2.01 %</span></td><td><span style='color:#009900;'>164 %</span></td></tr><tr><td align='right'>Sep</td><td style=' border-right: 1px solid grey;'>'09</td><td>&nbsp;</td><td><span style='color:#cc0033;'>882 &euro;</span></td><td><span style='color:#333366;'>40.472 &euro;</span></td><td><span style='color:#ff9900;'>2.18 %</span></td><td><span style='color:#009900;'>169 %</span></td></tr><tr><td align='right'>Okt</td><td style=' border-right: 1px solid grey;'>'09</td><td>&nbsp;</td><td><span style='color:#cc0033;'>2.044 &euro;</span></td><td><span style='color:#333366;'>42.423 &euro;</span></td><td><span style='color:#ff9900;'>4.82 %</span></td><td><span style='color:#009900;'>182 %</span></td></tr><tr><td align='right'>Nov</td><td style=' border-right: 1px solid grey;'>'09</td><td>&nbsp;</td><td><span style='color:#cc0033;'>1.668 &euro;</span></td><td><span style='color:#333366;'>44.031 &euro;</span></td><td><span style='color:#ff9900;'>3.79 %</span></td><td><span style='color:#009900;'>193 %</span></td></tr><tr><td align='right'>Dec</td><td style=' border-right: 1px solid grey;'>'09</td><td>&nbsp;</td><td><span style='color:#cc0033;'>1.807 &euro;</span></td><td><span style='color:#333366;'>45.770 &euro;</span></td><td><span style='color:#ff9900;'>3.95 %</span></td><td><span style='color:#009900;'>205 %</span></td></tr><tr><td align='right'>Jan</td><td style=' border-right: 1px solid grey;'>'10</td><td>&nbsp;</td><td><span style='color:#cc0033;'>2.172 &euro;</span></td><td><span style='color:#333366;'>47.848 &euro;</span></td><td><span style='color:#ff9900;'>4.54 %</span></td><td><span style='color:#009900;'>218 %</span></td></tr><tr><td align='right'>Feb</td><td style=' border-right: 1px solid grey;'>'10</td><td>&nbsp;</td><td><span style='color:#cc0033;'>2.156 &euro;</span></td><td><span style='color:#333366;'>49.915 &euro;</span></td><td><span style='color:#ff9900;'>4.32 %</span></td><td><span style='color:#009900;'>232 %</span></td></tr><tr><td align='right'>Mar</td><td style=' border-right: 1px solid grey;'>'10</td><td>&nbsp;</td><td><span style='color:#cc0033;'>3.528 &euro;</span></td><td><span style='color:#333366;'>53.225 &euro;</span></td><td><span style='color:#ff9900;'>6.63 %</span></td><td><span style='color:#009900;'>254 %</span></td></tr><tr><td align='right'>Apr</td><td style=' border-right: 1px solid grey;'>'10</td><td>&nbsp;</td><td><span style='color:#cc0033;'>3.183 &euro;</span></td><td><span style='color:#333366;'>56.237 &euro;</span></td><td><span style='color:#ff9900;'>5.66 %</span></td><td><span style='color:#009900;'>274 %</span></td></tr>		</table>

    </div>
    <div id="fragment-1">
        <p>De maandelijkse bruto winst over de periode April 2008 tot April 2010. De waarden zijn in euro.</p>
		<img src='/main/_view/images/static/winst_euro.png' />
    </div>
	<div id="fragment-2">
        <p>De maandelijkse bruto procent winst gemaakt over de periode April 2008 tot April 2010.</p>
		<img src='/main/_view/images/static/winst_procent.png' />

    </div>
    <div id="fragment-3">
        <p>De evolutie van het volgaccount over de beschouwde periode. </p>
		<img src='/main/_view/images/static/cumul_euro.png' />
    </div>
	<div id="fragment-4">
        <p>De verandering van % winst over de beschouwde periode.</p>
		<img src='/main/_view/images/static/culum_procent.png' />

    </div>
</div>
<!-- start_blok name=sec_account -->
<h4><a name="second_account"></a>Ons tweede volgaccount;</h4>
<p>Wegens bovengenoemde redenen verhuisden we in mei 2010 naar een nieuwe broker, DUKASCOPY (Zwitserland). Hiermee realiseren we een samenwerking met de grootste ECN en onafhankelijke forex market place met de grootste liquiditeit!</p>
<ul>
	<li>Herstart met een nieuwe volgaccount van 220.000 &euro; op 17 augustus 2010.</li>
	<li>Broker: DUKASCOPY (Suisse) / UBS (Gen&eacute;ve)</li>

	<li>Momenteel lopende.</li>
</ul>
<!-- end_blok -->

<div id="tabsalfa">
    <ul>
        <li><a href="#fragment-5"><span>Tabel</span></a></li>
        <li><a href="#fragment-6"><span>Winst</span></a></li>
        <li><a href="#fragment-7"><span>% Winst</span></a></li>
        <li><a href="#fragment-8"><span>Cumul</span></a></li>
        <li><a href="#fragment-9"><span>% Cumul</span></a></li>
    </ul>
	<div id="fragment-5">
		<table>
			<tr>
				<td colspan='2' width='20%'>&nbsp;</td>
				<td>&nbsp;</td>
				<td>winst</td>
				<td>cumul</td>
				<td>winst</td>
				<td>cumul</td>
			</tr>
			<tr>
				<td colspan='2'><b>Startbedrag</b></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><span style='color:#333366;'><?php echo number_format($start_bedrag, 0, ',', '.'); ?> &euro;</span></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		<?php

			foreach($data as $k => $v)
			{
				echo "<tr>";
					echo "<td align='right'>" . $v['maand'] 											. "</td>";
					echo "<td style=' border-right: 1px solid grey;'>" . $v['jaar'] 	. "</td>";
					echo "<td>&nbsp;</td>";
					echo "<td><span style='color:#cc0033;'>" . number_format($v['winst_euro'], 0, ',', '.')			. " &euro;</span></td>";
					echo "<td><span style='color:#333366;'>" . number_format($v['cumul_euro'], 0, ',', '.') 		. " &euro;</span></td>";
					echo "<td><span style='color:#ff9900;'>" . $v['winst_procent'] 									. " %</span></td>";
					echo "<td><span style='color:#009900;'>" . $v['cumul_procent'] 									. " %</span></td>";
				echo "</tr>";
			}
		?>
		</table>
    </div>
    <div id="fragment-6">
        <p>De maandelijkse bruto winst over de periode April 2008 tot April 2010. De waarden zijn in euro.</p>
		<img src='/main/_modules/chart/img/winst_euro.png' />
    </div>
	<div id="fragment-7">
        <p>De maandelijkse bruto procent winst gemaakt over de periode April 2008 tot April 2010.</p>
		<img src='/main/_modules/chart/img/winst_procent.png' />

    </div>
    <div id="fragment-8">
        <p>De evolutie van het volgaccount over de beschouwde periode. </p>
		<img src='/main/_modules/chart/img/culum_euro.png' />
    </div>
	<div id="fragment-9">
        <p>De verandering van % winst over de beschouwde periode.</p>
		<img src='/main/_modules/chart/img/cumul_procent.png' />

    </div>
</div>