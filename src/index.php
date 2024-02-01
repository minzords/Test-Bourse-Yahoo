<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Scheb\YahooFinanceApi\ApiClientFactory;

/**
 * TODO fix the slow loading on lib yahoo finance api client (fc.yahoo.com)
 * 
 */


/**
 * L'action à récupérer (par défaut : TTE.PA, SU.PA, AI.PA)
 * si aucun paramètre n'est passé dans l'URL (ex: index.php?symbol=BN.PA)
 */
if (isset($_GET['symbol'])) {
    $symbol = [$_GET['symbol']];
} else {
    $symbol = ["TTE.PA", "SU.PA", "AI.PA"];
    // $symbol = ["TTE.PA", "SU.PA", "AI.PA", "BN.PA", "AC.PA", "SW.PA", "PSP5.PA", "MMB.PA", "VIV.PA", ""]; // Error time out (lib error)
}

// Créer l'API client
$apiClient = ApiClientFactory::createApiClient();

$i=0;
foreach ($symbol as $value) {
    // Récupérer les informations de l'action
    $quote = $apiClient->getQuote($value);

    // Récupérer le prix de l'action
    $price = $quote->getRegularMarketPrice();

    // Supposer la devise en fonction de la bourse
    $exchange = $quote->getExchange();
    $currencySymbol = ($exchange === 'PAR') ? '€' : '$';

    // Stocker le prix dans un tableau avec le symbole de l'action
    $prices[$value] = $price;
}

// Afficher le prix de l'action avec la devise
?>
<style>
    table {
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }
</style>
<table>
    <tr>
        <th>Symbol</th>
        <th>Price</th>
    </tr>

    <?php
    foreach ($prices as $key => $value) {
        echo "<tr>";
        echo "<td>" . $key . "</td>";
        echo "<td>" . $value . "</td>";
        echo "</tr>";
    }
    ?>
</table>