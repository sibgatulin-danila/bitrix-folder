<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Pers�nlicher Bereich");
?>					
<div class="bx_page">
	<p>Im pers�nlichen Bereich k�nnen Sie den aktuellen Warenkorb und den Status Ihrer Bestellungen pr�fen, Ihre pers�nlichen Informationen einsehen oder �ndern, sowie Nachrichten und Newsletter abonnieren. </p>
	<div>
		<h2>Pers�nliche Informationen</h2>
		<ul>
			<li><a href="profile/">Registrierungsdaten �ndern</a></li>
		</ul>
	</div>
	<div>
		<h2>Bestellungen</h2>
		<ul>
			<li><a href="order/">Bestellstatus anzeigen</a></li>
			<li><a href="cart/">Warenkorb anzeigen</a></li>
			<li><a href="order/">Historie der Bestellungen anzeigen</a></li>
		</ul>
	</div>
	<div>
		<h2>Abonnement</h2>
		<ul>
			<li><a href="subscribe/">Abonnement �ndern</a></li>
		</ul>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>