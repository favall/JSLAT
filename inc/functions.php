<?php
function debug($variable){
    echo '<pre>' . print_r($variable, true) . '</pre>';
}


function str_random($length){
    $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
    return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

function logged_only(){
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['auth'])){
        $_SESSION['flash']['danger'] = "Vous n'avez pas le droit d'accéder à cette page";
        header('Location: connect.php');
        exit();
    }
}

function reconnect_from_cookie(){
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    if(isset($_COOKIE['remember']) && !isset($_SESSION['auth']) ){
        require_once 'connection.php';
        if(!isset($pdo)){
            global $pdo;
        }
        $remember_token = $_COOKIE['remember'];
        $parts = explode('==', $remember_token);
        $user_id = $parts[0];
        $req = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $req->execute([$user_id]);
        $user = $req->fetch();
        if($user){
            $expected = $user_id . '==' . $user->remember_token . sha1($user_id . '6Hdrv8x!');
            if($expected == $remember_token){
                if(!isset($_SESSION)) { session_start();}
                $_SESSION['auth'] = $user;
                setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
            } else{
                setcookie('remember', null, -1);
            }
        }else{
            setcookie('remember', null, -1);
        }
    }
}

function retouraccueil($retour) {
print ("<script language = \"JavaScript\">");
print ("location.href = '" . $retour . "';");
print ("</script>");
}

function f_crypt($private_key, $str_to_crypt) {
	$private_key = md5($private_key);
	$letter = -1;
	$new_str = '';
	$strlen = strlen($str_to_crypt);

	for ($i = 0; $i < $strlen; $i++) {
		$letter++;
		if ($letter > 31) {
			$letter = 0;
		}
		$neword = ord($str_to_crypt{$i}) + ord($private_key{$letter});
		if ($neword > 255) {
			$neword -= 256;
		}
		$new_str .= chr($neword);
	}
	return base64_encode($new_str);
}

function f_decrypt($private_key, $str_to_decrypt) {
	$private_key = md5($private_key);
	$letter = -1;
	$new_str = '';
	$str_to_decrypt = base64_decode($str_to_decrypt);
	$strlen = strlen($str_to_decrypt);
	for ($i = 0; $i < $strlen; $i++) {
		$letter++;
		if ($letter > 31) {
			$letter = 0;
		}
		$neword = ord($str_to_decrypt{$i}) - ord($private_key{$letter});
		if ($neword < 1) {
			$neword += 256;
		}
		$new_str .= chr($neword);
	}
	return $new_str;
}

function deleteDir($dirPath)
{
    if (!is_dir($dirPath)) {
        if (file_exists($dirPath) !== false) {
            unlink($dirPath);
        }
        return;
    }

    if ($dirPath[strlen($dirPath) - 1] != '/') {
        $dirPath .= '/';
    }

    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }

    rmdir($dirPath);
}

function zipper_repertoire_recursif($nom_archive, $adr_dossier, $dossier_destination = '', $zip=null, $dossier_base = '') {
	if($zip===null) {
		// Si l'archive n'existe toujours pas (1er passage dans la fonction, on la crée)
		$zip = new ZipArchive();
		if($zip->open($nom_archive, ZipArchive::CREATE) !== TRUE) {
			// La création de l'archive a échouée
			return false;
		}
	}
	
	if(substr($adr_dossier, -1)!='/') {
		// Si l'adresse du dossier ne se termine pas par '/', on le rajoute
		$adr_dossier .= '/';
	}
	
	if($dossier_base=="") {
		// Si $dossier_base est vide ça veut dire que l'on rentre
		// dans la fonction pour la première fois. Donc on retient 
		// le tout premier dossier (le dossier racine) dans $dossier_base
		$dossier_base=$adr_dossier;
	}
	
	if(file_exists($adr_dossier)) {
		if(@$dossier = opendir($adr_dossier)) {
			while(false !== ($fichier = readdir($dossier))) {
				if($fichier != '.' && $fichier != '..') {
					if(is_dir($adr_dossier.$fichier)) {
						$zip->addEmptyDir($adr_dossier.$fichier);
						zipper_repertoire_recursif($nom_archive, $adr_dossier.$fichier, $dossier_destination, $zip, $dossier_base);
					}
					else {
						$zip->addFile($adr_dossier.$fichier);
					}
				}
			}
		}
	}
	
	if($dossier_base==$adr_dossier) {
		// On ferme la zip
		$zip->close();
		
		if($dossier_destination!='') {
			if(substr($dossier_destination, -1)!='/') {
				// Si l'adresse du dossier ne se termine pas par '/', on le rajoute
				$dossier_destination .= '/';
			}
			
			// On déplace l'archive dans le dossier voulu
			if(rename($nom_archive, $dossier_destination.$nom_archive)) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return true;
		}
	}
}