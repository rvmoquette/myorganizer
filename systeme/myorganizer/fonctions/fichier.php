<?php declare(strict_types=1);

class Fichier
{

    private $dossier_fichiers;

    private $dossier_fichiers_base;

    private $filtre;

    private $prefixe;

    private static $db = [
        "aac" => [
            "nom" => "fichier audio AAC",
            "typemime" => "audio/aac"
        ],
        "abw" => [
            "nom" => "document AbiWord",
            "typemime" => "application/x-abiword"
        ],
        "arc" => [
            "nom" => "archive (contenant plusieurs fichiers)",
            "typemime" => "application/octet-stream"
        ],
        "avi" => [
            "nom" => "AVI : Audio Video Interleave",
            "typemime" => "video/x-msvideo"
        ],
        "azw" => [
            "nom" => "format pour eBook Amazon Kindle",
            "typemime" => "application/vnd.amazon.ebook"
        ],
        "bin" => [
            "nom" => "n'importe quelle donnée binaire",
            "typemime" => "application/octet-stream"
        ],
        "bz" => [
            "nom" => "archive BZip",
            "typemime" => "application/x-bzip"
        ],
        "bz2" => [
            "nom" => "archive BZip2",
            "typemime" => "application/x-bzip2"
        ],
        "csh" => [
            "nom" => "script C-Shell",
            "typemime" => "application/x-csh"
        ],
        "css" => [
            "nom" => "fichier Cascading Style Sheets (CSS)",
            "typemime" => "text/css"
        ],
        "csv" => [
            "nom" => "fichier Comma-separated values (CSV)",
            "typemime" => "text/csv"
        ],
        "doc" => [
            "nom" => "Microsoft Word",
            "typemime" => "application/msword"
        ],
        "docx" => [
            "nom" => "Microsoft Word (OpenXML)",
            "typemime" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
        ],
        "eot" => [
            "nom" => "police MS Embedded OpenType",
            "typemime" => "application/vnd.ms-fontobject"
        ],
        "epub" => [
            "nom" => "fichier Electronic publication (EPUB)",
            "typemime" => "application/epub+zip"
        ],
        "gif" => [
            "nom" => "fichier Graphics Interchange Format (GIF)",
            "typemime" => "image/gif"
        ],
        "htm" => [
            "nom" => "fichier HyperText Markup Language (HTML)",
            "typemime" => "text/html"
        ],
        "html" => [
            "nom" => "fichier HyperText Markup Language (HTML)",
            "typemime" => "text/html"
        ],
        "ico" => [
            "nom" => "icône",
            "typemime" => "image/x-icon"
        ],
        "ics" => [
            "nom" => "élément iCalendar",
            "typemime" => "text/calendar"
        ],
        "jar" => [
            "nom" => "archive Java (JAR)",
            "typemime" => "application/java-archive"
        ],
        "jpeg" => [
            "nom" => "image JPEG",
            "typemime" => "image/jpeg"
        ],
        "jpg" => [
            "nom" => "image JPEG",
            "typemime" => "image/jpeg"
        ],
        "js" => [
            "nom" => "JavaScript (ECMAScript)",
            "typemime" => "application/javascript"
        ],
        "json" => [
            "nom" => "donnée au format JSON",
            "typemime" => "application/json"
        ],
        "mid" => [
            "nom" => "fichier audio Musical Instrument Digital Interface (MIDI)",
            "typemime" => "audio/midi"
        ],
        "midi" => [
            "nom" => "fichier audio Musical Instrument Digital Interface (MIDI)",
            "typemime" => "audio/midi"
        ],
        "mpeg" => [
            "nom" => "vidéo MPEG",
            "typemime" => "video/mpeg"
        ],
        "mpkg" => [
            "nom" => "paquet Apple Installer",
            "typemime" => "application/vnd.apple.installer+xml"
        ],
        "odp" => [
            "nom" => "présentation OpenDocument",
            "typemime" => "application/vnd.oasis.opendocument.presentation"
        ],
        "ods" => [
            "nom" => "feuille de calcul OpenDocument",
            "typemime" => "application/vnd.oasis.opendocument.spreadsheet"
        ],
        "odt" => [
            "nom" => "document texte OpenDocument",
            "typemime" => "application/vnd.oasis.opendocument.text"
        ],
        "oga" => [
            "nom" => "fichier audio OGG",
            "typemime" => "audio/ogg"
        ],
        "ogv" => [
            "nom" => "fichier vidéo OGG",
            "typemime" => "video/ogg"
        ],
        "ogx" => [
            "nom" => "OGG",
            "typemime" => "application/ogg"
        ],
        "otf" => [
            "nom" => "police OpenType",
            "typemime" => "font/otf"
        ],
        "png" => [
            "nom" => "fichier Portable Network Graphics",
            "typemime" => "image/png"
        ],
        "pdf" => [
            "nom" => "Adobe Portable Document Format (PDF)",
            "typemime" => "application/pdf"
        ],
        "ppt" => [
            "nom" => "présentation Microsoft PowerPoint",
            "typemime" => "application/vnd.ms-powerpoint"
        ],
        "pptx" => [
            "nom" => "présentation Microsoft PowerPoint (OpenXML)",
            "typemime" => "application/vnd.openxmlformats-officedocument.presentationml.presentation"
        ],
        "pptm" => [
            "nom" => "présentation Microsoft PowerPoint avec des macros",
            "typemime" => "application/vnd.ms-powerpoint.presentation.macroenabled.12"
        ],
        "rar" => [
            "nom" => "archive RAR",
            "typemime" => "application/x-rar-compressed"
        ],
        "rtf" => [
            "nom" => "Rich Text Format (RTF)",
            "typemime" => "application/rtf"
        ],
        "sh" => [
            "nom" => "script shell",
            "typemime" => "application/x-sh"
        ],
        "svg" => [
            "nom" => "fichier Scalable Vector Graphics (SVG)",
            "typemime" => "image/svg+xml"
        ],
        "swf" => [
            "nom" => "fichier Small web format (SWF) ou Adobe Flash",
            "typemime" => "application/x-shockwave-flash"
        ],
        "tar" => [
            "nom" => "fichier d'archive Tape Archive (TAR)",
            "typemime" => "application/x-tar"
        ],
        "tif" => [
            "nom" => "image au format Tagged Image File Format (TIFF)",
            "typemime" => "image/tiff"
        ],
        "tiff" => [
            "nom" => "image au format Tagged Image File Format (TIFF)",
            "typemime" => "image/tiff"
        ],
        "ts" => [
            "nom" => "fichier Typescript",
            "typemime" => "application/typescript"
        ],
        "ttf" => [
            "nom" => "police TrueType",
            "typemime" => "font/ttf"
        ],
        "vsd" => [
            "nom" => "Microsoft Visio",
            "typemime" => "application/vnd.visio"
        ],
        "wav" => [
            "nom" => "Waveform Audio Format",
            "typemime" => "audio/x-wav"
        ],
        "weba" => [
            "nom" => "fichier audio WEBM",
            "typemime" => "audio/webm"
        ],
        "webm" => [
            "nom" => "fichier vidéo WEBM",
            "typemime" => "video/webm"
        ],
        "webp" => [
            "nom" => "image WEBP",
            "typemime" => "image/webp"
        ],
        "woff" => [
            "nom" => "police Web Open Font Format (WOFF)",
            "typemime" => "font/woff"
        ],
        "woff2" => [
            "nom" => "police Web Open Font Format (WOFF)",
            "typemime" => "font/woff2"
        ],
        "xhtml" => [
            "nom" => "XHTML",
            "typemime" => "application/xhtml+xml"
        ],
        "xls" => [
            "nom" => "Microsoft Excel",
            "typemime" => "application/vnd.ms-excel"
        ],
        "xlsx" => [
            "nom" => "Microsoft Excel (OpenXML)",
            "typemime" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        ],
        "xlsm" => [
            "nom" => "Microsoft Excel avec des macros",
            "typemime" => "Content-type: application/vnd.ms-excel.sheet.macroEnabled.12"
        ],
        "xml" => [
            "nom" => "XML",
            "typemime" => "application/xml"
        ],
        "xul" => [
            "nom" => "XUL",
            "typemime" => "application/vnd.mozilla.xul+xml"
        ],
        "zip" => [
            "nom" => "archive ZIP",
            "typemime" => "application/zip"
        ],
        "3gp" => [
            "nom" => "conteneur audio/vidéo 3GPP",
            "typemime" => "video/3gpp"
        ],
        "3g2" => [
            "nom" => "conteneur audio/vidéo 3GPP2",
            "typemime" => "video/3gpp2"
        ],
        "7z" => [
            "nom" => "archive 7-zip",
            "typemime" => "application/x-7z-compressed"
        ]
    ];

    function __construct(string $filtre = '')
    {
        /*
         * application/pdf
         * image/
         * image/png
         * image/png image/jpeg image/jpg
         * ...
         */
        global $mf_get_HTTP_HOST;
        $this->dossier_fichiers = __DIR__ . '/../../../fichiers/';
        if (! file_exists($this->dossier_fichiers)) {
            mkdir($this->dossier_fichiers);
        }
        // dossier $mf_get_HTTP_HOST
        $this->dossier_fichiers = $this->dossier_fichiers . $mf_get_HTTP_HOST . '/';
        if (! file_exists($this->dossier_fichiers)) {
            mkdir($this->dossier_fichiers);
        }
        // dossier NOM_PROJET
        $this->dossier_fichiers = $this->dossier_fichiers . NOM_PROJET . '/';
        if (! file_exists($this->dossier_fichiers)) {
            mkdir($this->dossier_fichiers);
        }
        $this->dossier_fichiers_base = $this->dossier_fichiers;
        if (TABLE_INSTANCE != '') {
            $instance = 'inst_' . get_instance();
            $this->dossier_fichiers .= $instance . '/';
            if (! file_exists($this->dossier_fichiers)) {
                mkdir($this->dossier_fichiers);
            }
            $this->prefixe = 'inst_' . get_instance() . '__';
        } else {
            $this->prefixe = '';
        }
        $this->filtre = $filtre;
    }

    function importer(array $file): string
    {
        if ($file['name'] != '' && stripos(' ' . $this->filtre, ' ' . substr($file['type'], 0, strlen($this->filtre))) !== false) {
            $extension = strtolower(strrchr($file['name'], '.'));
            $nom = $this->prefixe . 'f_' . salt_minuscules(100) . $extension;
            while (file_exists($this->dossier_fichiers . $nom)) {
                $nom = $this->prefixe . 'f_' . salt_minuscules(100) . $extension;
            }
            $resultat = move_uploaded_file($file['tmp_name'], $this->dossier_fichiers . $nom);
            if (! $resultat) {
                $nom = '';
            }
        } else {
            $nom = '';
        }
        return $nom;
    }

    function importer_depuis_fichier(string $adresse)
    {
        $extension = strtolower(strrchr($adresse, '.'));
        $nom = $this->prefixe . 'f_' . salt_minuscules(100) . $extension;
        while (file_exists($this->dossier_fichiers . $nom)) {
            $nom = $this->prefixe . 'f_' . salt_minuscules(100) . $extension;
        }
        $resultat = copy($adresse, $this->dossier_fichiers . $nom);
        if (! $resultat) {
            $nom = '';
        }
        return $nom;
    }

    function set($contenu, string $filename = '', string $extension = '')
    {
        if ($filename == '') {
            $filename = $this->prefixe . 'f_' . salt_minuscules(100) . $extension;
            while (file_exists($this->dossier_fichiers . $filename)) {
                $filename = $this->prefixe . 'f_' . salt_minuscules(100) . $extension;
            }
        }
        file_put_contents($this->dossier_fichiers . $filename, $contenu);
        return $filename;
    }

    function get(string $nom)
    {
        if ($nom != '') {
            $filename = $this->nom_vers_filename($nom);
            if (file_exists($filename)) {
                return file_get_contents($filename);
            }
        }
        return false;
    }

    function get_extention(string $nom)
    {
        $p = strrpos($nom, '.');
        if ($p > 0)
            $ext = strtolower(substr($nom, $p + 1));
        else
            $ext = 'null'; // https://www.freeformatter.com/mime-types-list.html
        return $ext;
    }

    function get_adresse(string $nom)
    {
        return $this->nom_vers_filename($nom);
    }

    function supprimer()
    {}

    function get_mine_type(string $ext): string
    {
        if (isset(self::$db[$ext])) {
            return self::$db[$ext]['typemime'];
        } else {
            return "application/octet-stream";
        }
    }

    // partie privée
    private function nom_vers_filename(string $nom): string
    {
        $p = stripos($nom, '__');
        if ($p === false) {
            $filename = $this->dossier_fichiers . $nom;
        } else {
            $instance = (int) substr($nom, 5, $p - 5);
            $filename = $this->dossier_fichiers_base . 'inst_' . $instance . '/' . $nom;
        }
        return $filename;
    }
}
