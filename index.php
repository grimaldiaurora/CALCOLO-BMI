<?php
$errore = "";
$colore_sfondo = "#F1F3F5";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $peso = floatval($_POST['peso'] ?? 0);
    $altezza_cm = floatval($_POST['altezza'] ?? 0);

    if (empty($nome) || $peso <= 0 || $altezza_cm <= 0) {
        $errore = "Inserisci valori validi per tutti i campi.";
    } else {
        $altezza_m = $altezza_cm / 100;
        $bmi = $peso / pow($altezza_m, 2);

        if ($bmi < 18.5) {
            $categoria = "Sottopeso";
            $colore_sfondo = "#FFF9C4";
        } elseif ($bmi <= 24.9) {
            $categoria = "Normopeso (peso forma)";
            $colore_sfondo = "#C8E6C9";
        } elseif ($bmi <= 29.9) {
            $categoria = "Sovrappeso";
            $colore_sfondo = "#FFE0B2";
        } else {
            $categoria = "Obesità";
            $colore_sfondo = "#FFCDD2";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Calcolatore BMI</title>
  <link rel="stylesheet" href="style.css">
</head>
<body style="--bg-color: <?php echo $colore_sfondo; ?>;">

  <div class="container">
    <h1>Calcolatore BMI</h1>

    <div class="card">
      <?php if ($_SERVER['REQUEST_METHOD'] === 'GET'): ?>

        <form action="index.php" method="POST">
          <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" placeholder="Inserisci il tuo nome" required>
          </div>

          <div class="form-group">
            <label for="peso">Peso (kg)</label>
            <input type="number" id="peso" name="peso" step="0.1" placeholder="Es. 70.5" required>
          </div>

          <div class="form-group last-group">
            <label for="altezza">Altezza (cm)</label>
            <input type="number" id="altezza" name="altezza" step="0.1" placeholder="Es. 175" required>
          </div>

          <button type="submit" class="btn-submit">Calcola</button>
        </form>

      <?php else: ?>

        <div class="result-box">
          <?php if (!empty($errore)): ?>
            <div class="error-box"><?php echo $errore; ?></div>
            <a href="index.php" class="btn-back">Riprova</a>
          <?php else: ?>
            <p>Ciao <strong><?php echo htmlspecialchars($nome); ?></strong>, il tuo BMI è:</p>
            <div class="bmi-value"><?php echo number_format($bmi, 2); ?></div>
            <p>Categoria: <strong><?php echo $categoria; ?></strong></p>
            <a href="index.php" class="btn-back">Nuovo Calcolo</a>
          <?php endif; ?>
        </div>

      <?php endif; ?>
    </div>
  </div>

</body>
</html>