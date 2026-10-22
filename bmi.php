<?php
// Controllo dell'accesso alla pagina tramite la superglobale $_SERVER
// Se la pagina non viene richiamata con metodo POST, reindirizza all'index
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

// Recupero dei dati inviati dal form tramite la superglobale $_POST
$nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$peso = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
$altezza_cm = isset($_POST['altezza']) ? floatval($_POST['altezza']) : 0;

$errore = "";
$bmi = 0;
$categoria = "";

// Validazione lato server dei dati ricevuti
if (empty($nome) || $peso <= 0 || $altezza_cm <= 0) {
    $errore = "Inserisci tutti i dati correttamente prima di procedere.";
} else {
    // Conversione dell'altezza da centimetri a metri per la formula del BMI
    $altezza_m = $altezza_cm / 100;

    // Uso della funzione di libreria pow() da manuale PHP per elevare l'altezza al quadrato
    // Documentazione: pow(base, esponente)
    $altezza_quadrato = pow($altezza_m, 2);

    // Calcolo del BMI: peso (kg) / altezza^2 (m)
    if ($altezza_quadrato > 0) {
        $bmi = $peso / $altezza_quadrato;

        // Classificazione della fascia di peso
        if ($bmi < 18.5) {
            $categoria = "Sottopeso";
        } elseif ($bmi < 25.0) {
            $categoria = "Normopeso";
        } elseif ($bmi < 30.0) {
            $categoria = "Sovrappeso";
        } else {
            $categoria = "Obesità";
        }
    } else {
        $errore = "Impossibile calcolare il BMI con i dati forniti.";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Risultato BMI</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="container">
    <h1>Risultato del Calcolo</h1>

    <div class="card result-box">
      <?php if (!empty($errore)): ?>
        <!-- Messaggio di errore rosso nel caso di dati mancanti o negativi -->
        <p style="color: #D9383A; font-weight: bold;"><?php echo $errore; ?></p>
      <?php else: ?>
        <p>Ciao <strong><?php echo htmlspecialchars($nome); ?></strong>, il tuo Indice di Massa Corporea è:</p>
        
        <!-- Visualizzazione del valore del BMI arrotondato a due cifre decimali -->
        <div class="bmi-value"><?php echo number_format($bmi, 2); ?></div>
        
        <p>Categoria: <strong><?php echo $categoria; ?></strong></p>
      <?php endif; ?>

      <a href="index.html" class="btn-back">Torna al Form</a>
    </div>
  </div>

</body>
</html>