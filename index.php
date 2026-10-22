<?php
// Inizializzazione variabili
$errore = "";
$nome = "";
$bmi = 0;
$categoria = "";

// Colore di sfondo di default per il caricamento iniziale (GET)
$colore_sfondo = "#F1F3F5";

// Se la richiesta è in POST, elaboriamo i dati inviati dal form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recupero dati inviati con la superglobale $_POST
    $nome_input = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $peso_input = isset($_POST['peso']) ? trim($_POST['peso']) : '';
    $altezza_input = isset($_POST['altezza']) ? trim($_POST['altezza']) : '';

    // 1. Controllo se i campi sono vuoti o contengono testo non numerico
    if (empty($nome_input) || empty($peso_input) || empty($altezza_input)) {
        $errore = "Tutti i campi sono obbligatori.";
    } elseif (!is_numeric($peso_input) || !is_numeric($altezza_input)) {
        $errore = "Inserisci solo valori numerici nei campi peso e altezza.";
    } else {
        $peso = floatval($peso_input);
        $altezza_cm = floatval($altezza_input);

        // 2. Controllo valori minori o uguali a zero
        if ($peso <= 0 || $altezza_cm <= 0) {
            $errore = "Peso e altezza devono essere valori maggiori di zero.";
        } else {
            // Conversione dell'altezza da cm a metri
            $altezza_m = $altezza_cm / 100;

            // Calcolo dell'altezza al quadrato tramite la funzione di libreria pow()
            $altezza_quadrato = pow($altezza_m, 2);

            if ($altezza_quadrato > 0) {
                // Formula BMI: peso / (altezza * altezza)
                $bmi = $peso / $altezza_quadrato;

                // Griglia BMI per categoria e colore di sfondo
                if ($bmi < 18.5) {
                    $categoria = "Sottopeso";
                    $colore_sfondo = "#FFF9C4"; // Giallo chiaro
                } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
                    $categoria = "Normopeso (peso forma)";
                    $colore_sfondo = "#C8E6C9"; // Verde
                } elseif ($bmi >= 25.0 && $bmi <= 29.9) {
                    $categoria = "Sovrappeso";
                    $colore_sfondo = "#FFE0B2"; // Arancione
                } else {
                    // $bmi >= 30.0
                    $categoria = "Obesità";
                    $colore_sfondo = "#FFCDD2"; // Rosso
                }

                $nome = htmlspecialchars($nome_input);
            }
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

    /* Colore di sfondo dinamico impostato dal PHP */
    body {
      font-family: sans-serif;
      background-color: <?php echo $colore_sfondo; ?>;
      color: #1F2328;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 20px;
      transition: background-color 0.4s ease;
    }

    /* Contenitore principale (minimo 250px di larghezza) */
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

    /* Card bianca con padding 24px, angoli 12px e ombra */
    .card {
      background-color: #FFFFFF;
      width: 100%;
      padding: 24px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Struttura form e spaziature */
    .form-group {
      display: flex;
      flex-direction: column;
      margin-bottom: 16px; /* Distanza tra un box e la label successiva */
    }

    .form-group.last-group {
      margin-bottom: 24px; /* Distanza tra ultimo campo e pulsante */
    }

    /* Label a tutta larghezza con distanza di 6px dal box */
    label {
      width: 100%;
      font-weight: 600;
      font-size: 0.95rem;
      margin-bottom: 6px;
    }

    /* Input a tutta larghezza */
    input[type="text"] {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #D0D7DE;
      border-radius: 6px;
      font-size: 1rem;
      font-family: sans-serif;
      color: #1F2328;
      outline: none;
    }

    input[type="text"]:focus {
      border-color: #1F2328;
    }

    /* Messaggio di errore */
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
    }

    .btn-submit:hover {
      background-color: #32383E;
    }

    /* Schermata risultati */
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
      
      <?php if ($_SERVER['REQUEST_METHOD'] === 'GET'): ?>

        <!-- VISUALIZZAZIONE FORM (QUANDO LA RICHIESTA È IN GET) -->
        <form action="index.php" method="POST">
          
          <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" placeholder="Inserisci il tuo nome">
          </div>

          <div class="form-group">
            <label for="peso">Peso (kg)</label>
            <input type="text" id="peso" name="peso" placeholder="Es. 70.5">
          </div>

          <div class="form-group last-group">
            <label for="altezza">Altezza (cm)</label>
            <input type="text" id="altezza" name="altezza" placeholder="Es. 175">
          </div>

          <button type="submit" class="btn-submit">Calcola</button>

        </form>

      <?php else: ?>

        <!-- VISUALIZZAZIONE RISULTATI O ERRORI (QUANDO LA RICHIESTA È IN POST) -->
        <?php if (!empty($errore)): ?>
          
          <!-- Se ci sono errori nei dati inviati -->
          <div class="result-box">
            <div class="error-box"><?php echo $errore; ?></div>
            <a href="index.php" class="btn-back">Riprova</a>
          </div>

        <?php else: ?>

          <!-- Se i dati sono corretti mostra il calcolo del BMI -->
          <div class="result-box">
            <p>Ciao <strong><?php echo $nome; ?></strong>, il tuo BMI è:</p>
            <div class="bmi-value"><?php echo number_format($bmi, 2); ?></div>
            <p>Categoria: <strong><?php echo $categoria; ?></strong></p>

            <a href="index.php" class="btn-back">Nuovo Calcolo</a>
          </div>

        <?php endif; ?>

      <?php endif; ?>

    </div>
  </div>

</body>
</html>