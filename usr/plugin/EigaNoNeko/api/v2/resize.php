<?php
 
/**
 * Redimensionne une image au format 720x1080 px et l'enregistre en .webp.
 *
 * Comportement :
 *  - Si le ratio source correspond déjà à 720x1080 (2:3), l'image est simplement
 *    redimensionnée sans recadrage.
 *  - Si le ratio est différent, l'image est zoomée puis cadrée (cover) pour remplir
 *    entièrement le format cible, sans déformation ni bande noire.
 *  - Si l'image est déjà exactement 720x1080, elle est convertie en webp sans
 *    redimensionnement.
 *  - Si GD ne supporte pas WebP (ex. XAMPP macOS sans libwebp),
 *    une RuntimeException explicite est levée plutôt qu'un Fatal Error.
 *
 * Prérequis : extension GD activée (php-gd), PHP >= 7.4
 * Support WebP de GD : compilé avec libwebp (vérifiable via gd_info())
 *
 * @param  string  $sourcePath  Chemin vers l'image source (jpg, png, gif, webp...)
 * @param  string  $outputPath  Chemin de destination du fichier .webp produit
 * @param  int     $quality     Qualité webp de 0 (moins bonne) à 100 (meilleure) — défaut 85
 * @param  int     $targetW     Largeur cible en pixels — défaut 720
 * @param  int     $targetH     Hauteur cible en pixels — défaut 1080
 *
 * @return bool  true en cas de succès, false en cas d'erreur
 *
 * @throws \RuntimeException  Si GD n'est pas dispo, WebP non supporté, ou fichier illisible
 */
function resizeToWebp(
    string $sourcePath,
    string $outputPath,
    int    $quality = 90,
    int    $targetW = 360,
    int    $targetH = 540
): bool {
 
    // ── 0. Vérifications préalables ──────────────────────────────────────────
 
    if (!extension_loaded('gd')) {
        throw new \RuntimeException('L\'extension GD est requise mais non chargée.');
    }
 
    // Vérifie que GD a bien été compilé avec le support WebP.
    // Sur certaines distributions XAMPP (notamment macOS), GD est présent
    // mais sans libwebp → imagecreatefromwebp() et imagewebp() sont absentes.
    $gdInfo = gd_info();
    if (empty($gdInfo['WebP Support'])) {
        throw new \RuntimeException(
            'Votre installation de GD ne supporte pas WebP. ' .
            'Solutions : ' .
            '(1) Mettez à jour XAMPP vers la version 8.x ou supérieure. ' .
            '(2) Recompilez PHP avec --with-webp. ' .
            '(3) Sur macOS avec Homebrew : brew reinstall php. ' .
            'Support GD actuel : ' . implode(', ', array_keys(array_filter($gdInfo)))
        );
    }
 
    if (!is_file($sourcePath) || !is_readable($sourcePath)) {
        throw new \RuntimeException("Fichier source introuvable ou illisible : $sourcePath");
    }
 
    // ── 1. Chargement de l'image source ──────────────────────────────────────
 
    $imageInfo = getimagesize($sourcePath);
    if ($imageInfo === false) {
        throw new \RuntimeException("Impossible de lire les informations de l'image : $sourcePath");
    }
 
    $srcW = $imageInfo[0];
    $srcH = $imageInfo[1];
    $mime = $imageInfo['mime'];
 
    // Chargement sécurisé : on passe par loadImageSafely() qui vérifie
    // l'existence de chaque fonction GD avant de l'appeler.
    $src = loadImageSafely($sourcePath, $mime);
 
    if ($src === false) {
        throw new \RuntimeException("Echec du chargement de l'image : $sourcePath");
    }
 
    // ── 2. Correction de l'orientation EXIF (JPEG seulement) ─────────────────
 
    if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
        $exif        = @exif_read_data($sourcePath);
        $orientation = $exif['Orientation'] ?? 1;
 
        if ($orientation !== 1) {
            $src  = correctOrientation($src, $orientation);
            $srcW = imagesx($src);
            $srcH = imagesy($src);
        }
    }
 
    // ── 3. Cas : image déjà au bon format exact ───────────────────────────────
 
    if ($srcW === $targetW && $srcH === $targetH) {
        $result = imagewebp($src, $outputPath, $quality);
        imagedestroy($src);
        return $result;
    }
 
    // ── 4. Calcul du recadrage "cover" ────────────────────────────────────────
    //
    //  scale = max(targetW/srcW, targetH/srcH)
    //    → facteur minimal qui couvre entièrement le cadre (pas de bandes noires)
    //
    //  Zone à découper dans la source (centrée) :
    //    cropW = targetW / scale
    //    cropH = targetH / scale
    //    cropX = (srcW - cropW) / 2
    //    cropY = (srcH - cropH) / 2
 
    $scaleX = $targetW / $srcW;
    $scaleY = $targetH / $srcH;
    $scale  = max($scaleX, $scaleY);
 
    $cropW = (int) round($targetW / $scale);
    $cropH = (int) round($targetH / $scale);
    $cropX = (int) round(($srcW - $cropW) / 2);
    $cropY = (int) round(($srcH - $cropH) / 2);
 
    // ── 5. Création de l'image de destination et rééchantillonnage ────────────
 
    $dst = imagecreatetruecolor($targetW, $targetH);
 
    if ($dst === false) {
        imagedestroy($src);
        throw new \RuntimeException('Impossible de créer l\'image de destination.');
    }
 
    // Préserve la transparence pour PNG / WebP
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
    imagefill($dst, 0, 0, $transparent);
 
    $ok = imagecopyresampled(
        $dst,            // destination
        $src,            // source
        0, 0,            // coin sup. gauche destination
        $cropX, $cropY,  // coin sup. gauche zone source
        $targetW,        // largeur destination
        $targetH,        // hauteur destination
        $cropW,          // largeur zone source
        $cropH           // hauteur zone source
    );
 
    imagedestroy($src);
 
    if (!$ok) {
        imagedestroy($dst);
        throw new \RuntimeException('Echec du redimensionnement (imagecopyresampled).');
    }
 
    // ── 6. Sauvegarde en WebP ─────────────────────────────────────────────────
 
    $dir = dirname($outputPath);
    if ($dir !== '' && $dir !== '.' && !is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
 
    $result = imagewebp($dst, $outputPath, $quality);
    imagedestroy($dst);
 
    return $result;
}
 
// ─────────────────────────────────────────────────────────────────────────────
 
/**
 * Charge une image GD de façon sécurisée selon son MIME type.
 *
 * Contrairement à un simple match/switch, cette fonction vérifie l'existence
 * de chaque fonction GD avant de l'appeler. Cela évite les Fatal Error sur les
 * installations de GD incomplètes (ex. XAMPP sans WebP, sans BMP, etc.).
 *
 * @param  string  $path  Chemin vers le fichier image
 * @param  string  $mime  Type MIME détecté par getimagesize()
 *
 * @return \GdImage|false  Ressource GD ou false en cas d'échec
 *
 * @throws \RuntimeException  Si le format est non supporté ou la fonction GD absente
 */
function loadImageSafely(string $path, string $mime): \GdImage|false
{
    $loaders = [
        'image/jpeg'     => 'imagecreatefromjpeg',
        'image/jpg'      => 'imagecreatefromjpeg',
        'image/png'      => 'imagecreatefrompng',
        'image/gif'      => 'imagecreatefromgif',
        'image/webp'     => 'imagecreatefromwebp',
        'image/bmp'      => 'imagecreatefrombmp',
        'image/x-bmp'    => 'imagecreatefrombmp',
        'image/x-ms-bmp' => 'imagecreatefrombmp',
    ];
 
    if (!array_key_exists($mime, $loaders)) {
        throw new \RuntimeException(
            "Format d'image non supporte : $mime. " .
            'Formats acceptes : JPEG, PNG, GIF, WebP, BMP.'
        );
    }
 
    $fn = $loaders[$mime];
 
    if (!function_exists($fn)) {
        throw new \RuntimeException(
            "La fonction GD \"$fn\" est absente sur ce serveur. " .
            "Votre installation de GD ne supporte pas le format $mime. " .
            'Verifiez gd_info() pour connaitre les formats disponibles.'
        );
    }
 
    return $fn($path);
}
 
// ─────────────────────────────────────────────────────────────────────────────
 
/**
 * Corrige l'orientation d'une image GD en fonction de la valeur EXIF.
 *
 * @param  \GdImage  $image        Ressource GD source
 * @param  int       $orientation  Valeur EXIF Orientation (1-8)
 * @return \GdImage  Image corrigée
 */
function correctOrientation(\GdImage $image, int $orientation): \GdImage
{
    return match ($orientation) {
        2 => (imageflip($image, IMG_FLIP_HORIZONTAL) ? $image : $image),
        3 => (imagerotate($image, 180, 0) ?: $image),
        4 => (imageflip($image, IMG_FLIP_VERTICAL) ? $image : $image),
        5 => (function (\GdImage $img): \GdImage {
            $r = imagerotate($img, -90, 0) ?: $img;
            imageflip($r, IMG_FLIP_HORIZONTAL);
            return $r;
        })($image),
        6 => (imagerotate($image, -90, 0) ?: $image),
        7 => (function (\GdImage $img): \GdImage {
            $r = imagerotate($img, 90, 0) ?: $img;
            imageflip($r, IMG_FLIP_HORIZONTAL);
            return $r;
        })($image),
        8 => (imagerotate($image, 90, 0) ?: $image),
        default => $image,
    };
}
 
// ─────────────────────────────────────────────────────────────────────────────
 
/**
 * Affiche un diagnostic WebP/GD utile pour déboguer les problèmes de support.
 * Appelez cette fonction une fois pour vérifier l'environnement.
 *
 * Usage CLI : php resize_to_webp.php --diag
 */
function printGdDiagnostic(): void
{
    echo "=== Diagnostic GD / WebP ===<br>";
    echo 'PHP version : ' . PHP_VERSION . "<br>";
    echo 'GD charge   : ' . (extension_loaded('gd') ? 'oui' : 'NON') . "<br>";
 
    if (!extension_loaded('gd')) {
        echo "-> Activez extension=gd dans php.ini<br>";
        return;
    }
 
    $info = gd_info();
    echo 'GD version  : ' . ($info['GD Version'] ?? 'inconnue') . "<br>";
    echo 'WebP Support: ' . (!empty($info['WebP Support']) ? 'OUI' : 'NON') . "<br>";
    echo 'JPEG Support: ' . (!empty($info['JPEG Support']) ? 'oui' : 'non') . "<br>";
    echo 'PNG Support : ' . (!empty($info['PNG Support']) ? 'oui' : 'non') . "<br>";
    echo 'EXIF charge : ' . (extension_loaded('exif') ? 'oui' : 'non') . "<br>";
 
    if (empty($info['WebP Support'])) {
        echo "<br>WebP non supporte. Solutions :<br>";
        echo "  - XAMPP macOS : mettez a jour vers XAMPP 8.x<br>";
        echo "  - Homebrew    : brew reinstall php<br>";
        echo "  - Linux/apt   : apt install php-gd  (puis redemarrer Apache)<br>";
        echo "  - Compilation : ./configure --with-webp<br>";
    }
}
 
// ── CLI diagnostic rapide ─────────────────────────────────────────────────────
if (PHP_SAPI === 'cli' && in_array('--diag', $argv ?? [], true)) {
    printGdDiagnostic();
    exit(0);
}
 

// ── Exemple d'utilisation ──────────────────────────────────────────────────────

foreach (scandir(__Root . 'share/content-delivry/image/') as $value) {
    if(in_array($value, ['.', '..'])) continue;
    if(!str_contains($value, 'poster')) continue;
    $name = pathinfo($value, PATHINFO_FILENAME);
    echo '<br>';
    echo $name; // document
    //echo $value;
    echo '<br>';

    try {
        $success = resizeToWebp(
            sourcePath: __Root . 'share/content-delivry/image/' . $value,
            outputPath: __Root . 'share/content-delivry/imagenews/' . $name . '.webp',
            quality: 90
        );

        if(file_exists(__Root . 'share/content-delivry/imagenews/' . $name . '.webp')) __OmsySql->execute(
            'UPDATE enncore_catalogue_obj SET content = ? WHERE content = ?',
            ['/share/content-delivry/image/' . $name . '.webp', '/share/content-delivry/image/' . $value]
        );

        if ($success) {
            echo "Image convertie avec succès.\n";
        } else {
            echo "La conversion a échoué (imagewebp a retourné false).\n";
        }
    } catch (\RuntimeException $e) {
        echo "Erreur : " . $e->getMessage() . "\n";
    }
}