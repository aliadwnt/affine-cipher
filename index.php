<?php
// Fungsi untuk mengenkripsi teks menggunakan Affine Cipher
function affine_encrypt($text, $key) {
    $result = "";

    foreach (str_split($text) as $char) {
        if (ctype_alpha($char)) {
            $ascii = ord(ctype_upper($char) ? 'A' : 'a');
            $result .= chr(($key[0] * (ord($char) - $ascii) + $key[1]) % 26 + $ascii);
        } else {
            $result .= $char;
        }
    }

    return $result;
}

// Fungsi untuk mendekripsi teks menggunakan Affine Cipher
function affine_decrypt($text, $key) {
    $result = "";

    // Mencari modular invers dari kunci pertama
    $modInverse = 0;
    for ($i = 0; $i < 26; $i++) {
        if (($key[0] * $i) % 26 == 1) {
            $modInverse = $i;
            break;
        }
    }

    foreach (str_split($text) as $char) {
        if (ctype_alpha($char)) {
            $ascii = ord(ctype_upper($char) ? 'A' : 'a');
            $result .= chr(($modInverse * (ord($char) - $ascii - $key[1] + 26)) % 26 + $ascii);
        } else {
            $result .= $char;
        }
    }

    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $keyword = "aliadewanto";

    if (isset($_POST['encrypt'])) {
        $plaintext = $_POST['text'];
        $key = [$keyword[0], ord($keyword[1]) - ord('a')];

        $ciphertext = affine_encrypt($plaintext, $key);
    } elseif (isset($_POST['decrypt'])) {
        $ciphertext = $_POST['text'];
        $key = [$keyword[0], ord($keyword[1]) - ord('a')];

        $plaintext = affine_decrypt($ciphertext, $key);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affine Cipher</title>
</head>
<body>
    <h2>Affine Cipher</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="text">Text:</label>
        <input type="text" name="text" id="text" required>
        <br>
        <input type="submit" name="encrypt" value="Encrypt">
        <input type="submit" name="decrypt" value="Decrypt">
    </form>

    <?php if (isset($ciphertext)): ?>
        <h3>Result:</h3>
        <p><?php echo isset($plaintext) ? "Decrypted Text: $plaintext" : "Encrypted Text: $ciphertext"; ?></p>
    <?php endif; ?>
</body>
</html>
