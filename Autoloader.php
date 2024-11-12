<?php

/**
 * Permet de charger les classes sans utiliser explicitement require(), require_once()
 * include()ou include_once()
 * Vous devez nommer vos fichiers PHP du même nom que la classe
 * Exemples : Classe Produit dans fichier Produit.php
 *            Classe ProduitDao dans fichier ProduitDao.php
 */
define('BASE_PATH', realpath(dirname(__FILE__)));

class Autoloader {
    static public function loader($className) {
        $filename = BASE_PATH.DIRECTORY_SEPARATOR.str_replace("\\", DIRECTORY_SEPARATOR, $className) . ".php";
        if (file_exists($filename)) {
            include($filename);
            if (class_exists($className)) {
                return TRUE;
            }
        }
        return FALSE;
    }
}
spl_autoload_register('Autoloader::loader');
