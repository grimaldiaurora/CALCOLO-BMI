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
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calcolatore BMI</title>
  <style>
    
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

/* Layout principale: centratura orizzontale e verticale */
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

/* Contenitore principale */
.container {
  width: 100%;
  min-width: 250px;
  max-width: 400px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Titolo della pagina */
h1 {
  font-size: 1.8rem;
  margin-bottom: 20px;
  text-align: center;
}

/* Componente Card */
.card {
  background-color: #FFFFFF;
  width: 100%;
  padding: 24px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

/* Gruppo formato da label, input e messaggio di errore */
.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 16px; 
}


.form-group.last-group {
  margin-bottom: 24px;
}


label {
  width: 100%;
  font-weight: 600;
  font-size: 0.95rem;
  margin-bottom: 6px;
}


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


.error-message {
  color: #D9383A;
  font-size: 0.8rem;
  margin-top: 4px;
  display: none;
}


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
      <form action="bmi.php" method="POST">
        
        <!-- Campo Nome -->
        <div class="form-group">
          <label for="nome">Nome</label>
          <input type="text" id="nome" name="nome" placeholder="Inserisci il tuo nome" required>
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

        <!-- Pulsante di Invio -->
        <button type="submit" class="btn-submit">Calcola</button>

      </form>
    </div>
  </div>

</body>
</html>