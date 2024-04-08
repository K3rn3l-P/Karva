<?php
// Preparazione della query SQL utilizzando PDO
$stmt = $conn->prepare("SELECT product_name, price FROM PS_WebSite.dbo.products WHERE id = :productId");
$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
$stmt->execute();

// Verifica se ci sono righe restituite dalla query
if ($stmt->rowCount() == 0) {
    // Nessun prodotto trovato, interrompe l'esecuzione dello script
    return;
}

// Recupero dei dati del prodotto
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Calcolo del costo totale per il prodotto
$cost = $product["price"] * $productCount;

// Aggiornamento delle variabili totalcount e total
$totalcount += $productCount;
$total += $cost;
?>

<tr>
    <td><?= $product["product_name"] ?></td>
    <td align="center"><?= $productCount ?></td>
    <td align="center"><?= $cost ?></td>
    <td align="center">
        <a href="<?= $TemplateUrl ?>actions/itemmall/cart-del.php?id=<?= $productId ?>">
            <img src="<?= $AssetUrl ?>images/icon_trash.gif" alt="Delete">
        </a>
    </td>
</tr>
