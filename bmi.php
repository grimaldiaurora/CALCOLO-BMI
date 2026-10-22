<?php
// Gestione dello stato e inizializzazione delle variabili
$mostra_risultato = false;
$errore = "";
$nome = "";
$bmi = 0;
$categoria = "";

// Controllo tramite superglobale $_SERVER se la pagina è stata richiamata tramite form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recupero ed elaborazione dei dati inviati tramite la superglobale $_POST
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $peso = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
    $altezza_cm = isset($_POST['altezza']) ? floatval($_POST['altezza']) : 0;

    // Validazione dei dati lato server
    if (empty($nome) || $peso <= 0 || $altezza_cm <= 0) {
        $errore = "Inserisci tutti i dati correttamente.";
    } else {
        // Conversione dell'altezza in metri
        $altezza_m = $altezza_cm / 100;

        // Uso della funzione di libreria pow() per il calcolo dell'altezza al quadrato
        $altezza_quadrato = pow($altezza_m, 2);

        if ($altezza_quadrato > 0) {
            $bmi = $peso / $altezza_quadrato;

            // Classificazione della categoria di peso
            if ($bmi < 18.5) {
                $categoria = "Sottopeso";
            } elseif ($bmi < 25.0) {
                $categoria = "Normopeso";
            } elseif ($bmi < 30.0) {
                $categoria = "Sovrappeso";
            } else {
                $categoria = "Obesità";
            }

            $mostra_risultato = true;
        } else {
            $errore = "Valore di altezza non valido.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calcolatore BMI</title>
  
  <style>
    /* Reset di base */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    /* Layout singola colonna, centrato orizzontalmente e verticalmente */
    body {
      font-family: sans-serif;
      background-color: #F1F3F5;
      color: #1F2328;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    /* Contenitore principale con larghezza minima di 250px */
    .container {
      width: 100%;
      min-width: 250px;
      max-width: 400px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* Titolo */
    h1 {
      font-size: 1.8rem;
      margin-bottom: 20px;
      text-align: center;
    }

    /* Card con sfondo bianco, padding 24px, angoli 12px e ombra */
    .card {
      background-color: #FFFFFF;
      width: 100%;
      padding: 24px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Gruppo di campi con distanza di 16px verso il gruppo successivo */
    .form-group {
      display: flex;
      flex-direction: column;
      margin-bottom: 16px;
    }

    /* Distanza di 24px tra l'ultimo campo (altezza) e il pulsante calcola */
    .form-group.last-group {
      margin-bottom: 24px;
    }

    /* Label a tutta larghezza con distanza di 6px dal box */
    label {
      width: 100%;
      font-weight: 600;
      font-size: 0.95rem;
      margin-bottom: 6px;
    }

    /* Campo con placeholder a tutta larghezza */
    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #D0D7DE;
      border-radius: 6px;
      font-size: 1rem;
      font-family: sans-serif;
      color: #1F2328;
      outline: none;
      transition: border-color 0.2s ease;
    }

    input[type="text"]:focus,
    input[type="number"]:focus {
      border-color: #1F2328;
    }

    /* Messaggio di errore rosso */
    .error-box {
      color: #D9383A;
      font-size: 0.85rem;
      margin-bottom: 16px;
      text-align: center;
      font-weight: bold;
    }

    /* Pulsante Calcola con sfondo #1F2328, testo bianco e angoli a 8px */
    .btn-submit {
      width: 100%;
      background-color: #1F2328;
      color: #FFFFFF;
      border: none;
      padding: 12px;
      font-size: 1rem;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .btn-submit:hover {
      background-color: #32383E;
    }

    /* Stili per la visualizzazione del risultato */
    .result-box {
      text-align: center;
      line-height: 1.6;
    }

    .bmi-value {
      font-size: 2.2rem;
      font-weight: bold;
      margin: 12px 0;
      color: #1F2328;
    }

    .btn-back {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #FFFFFF;
      background-color: #1F2328;
      padding: 10px 18px;
      border-radius: 8px;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>Calcolatore BMI</h1>

    <div class="card">
      
      <?php if ($mostra_risultato): ?>
        
        <!-- VISTA RISULTATO (dopo invio form via POST) -->
        <div class="result-box">
          <p>Ciao <strong><?php echo htmlspecialchars($nome); ?></strong>, il tuo Indice di Massa Corporea è:</p>
          <div class="bmi-value"><?php echo number_format($bmi, 2); ?></div>
          <p>Categoria: <strong><?php echo $categoria; ?></strong></p>

          <a href="index.php" class="btn-back">Calcola di nuovo</a>
        </div>

      <?php else: ?>

        <!-- VISTA FORM (primo caricamento o se presenti errori) -->
        <?php if (!empty($errore)): ?>
          <div class="error-box">
            <?php echo $errore; ?>
          </div>
        <?php endif; ?>

        <form action="index.php" method="POST">
          
          <!-- Campo Nome -->
          <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" placeholder="Inserisci il tuo nome" value="<?php echo htmlspecialchars($nome); ?>" required>
          </div>

          <!-- Campo Peso -->
          <div class="form-group">
            <label for="peso">Peso (kg)</label>
            <input type="number" id="peso" name="peso" step="0.1" min="1" placeholder="Es. 70.5" required>
          </div>

          <!-- Campo Altezza -->
          <div class="form-group last-group">
            <label for="altezza">Altezza (cm)</label>
            <input type="number" id="altezza" name="altezza" step="0.1" min="1" placeholder="Es. 175" required>
          </div>

          <!-- Pulsante Calcola -->
          <button type="submit" class="btn-submit">Calcola</button>

        </form>

      <?php endif; ?>

    </div>
  </div>

</body>
</html>