<?php

use Firebase\JWT\JWT;

class dbo
{
    public $message = null;
    public $error = false;
    public $data = ["message" => "success", "code" => 200];
    public $status = 200;
    public $token = null;
    public $codeUser = null;


    public function encriptToken($id)
    {
        $now = new DateTime();
        $future = new DateTime('now +12 hours');
        $payload = [
            'iat' => $now->getTimeStamp(),
            'exp' => $future->getTimeStamp(),
            'id' => $id
        ];
    
        $secret = JWT_TOKEN_KEY;
        $token = JWT::encode($payload, $secret, 'HS512');
        return $token;
    }
    public function setResponse($message, $code, $data, $token = null)
    {

        if ($token == null) {
            $this->data  = [
                "message" => $message,
                "code" => $code,
                "data" => $data,
            ];
        } else {
            $this->data  = [
                "message" => $message,
                "code" => $code,
                "data" => $data,
                "token" => $token
            ];
        }
    }

    public function notification($codeClient, $texte, $url, $etat, $type)
    {
        $querry = $this->get("INSERT INTO `clients_notifications`(`codeClient`, `texte`, `url`, `etat`,`type`) VALUES (?,?,?,?,?)");
        $querry->bindParam(1, $codeClient);
        $querry->bindParam(2, $texte);
        $querry->bindParam(3, $url);
        $querry->bindParam(4, $etat);
        $querry->bindParam(5, $type);
        $querry->execute();
    }

    public function get($rqt)
    {
        $req = $this->con()->prepare($rqt);
        return $req;
    }

    public function con()
    {
        $dbo = null;
        try {
            $now = new DateTime();
            $mins = $now->getOffset() / 60;
            $sgn = ($mins < 0 ? -1 : 1);
            $mins = abs($mins);
            $hrs = floor($mins / 60);
            $mins -= $hrs * 60;
            $offset = sprintf('%+d:%02d', $hrs * $sgn, $mins);
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            $dbo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USER, DB_PASSWORD, $pdo_options);
            $dbo->exec("SET time_zone='$offset';");
            $dbo->query('SET NAMES UTF8');
        } catch (Exception $exception) {
            writeError('CON', $exception->getMessage());
        }
        return $dbo;
    }

    public function getMessage($error, $message)
    {
        if ($error == false && $message != null) {
            return '<div style="padding: 10px; ;background-color:#F6F5FC;border: 2px solid green;border-radius: 25px;">' . $message . '</div>';
        } elseif ($error == true && $message != null) {
            return '<div style="padding: 10px; ;background-color:#F6F5FC;border: 2px solid red;border-radius: 25px;">' . $message . '</div>';
        }
    }

    public function execute($rqt)
    {
        $req = $this->con()->prepare($rqt);
        return $req->execute();
    }

    public function generateId()
    {
        return random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9)  . random_int(0, 9)  ;
    }

    public function generatecode_validation()
    {
        return random_int(0, 6). random_int(0, 6) . random_int(0, 6) . random_int(0, 6) .random_int(0, 6).random_int(0, 6);
    }

    function getAll($rqt)
    {
        try {
            $var = [];
            $req = $this->con()->prepare($rqt);
            $req->execute();
            while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
                $var[] = $data;
            }
            $req->closeCursor();
        } catch (Exception $exception) {
            $this->setResponse("Error", 400, []);
            writeError("GET_ALL->" . $rqt, 400, $exception);
        }
        return $var;
    }
    function getAll1($rqt, $item1)
    {
        $var = [];
        try {
            $req = $this->con()->prepare($rqt);
            $req->bindParam(1, $item1);
            $req->execute();
            while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
                $var[] = $data;
            }
        } catch (Exception $exception) {
            $this->setResponse("Error", 400, []);
            writeError("GET_ALL->" . $rqt, 400, $exception);
        }

        return $var;
        $req->closeCursor();
    }
    function getAll2($rqt, $item1, $item2)
    {
        $var = [];
        try {
            $req = $this->con()->prepare($rqt);
            $req->bindParam(1, $item1);
            $req->bindParam(2, $item2);
            $req->execute();
            while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
                $var[] = $data;
            }
        } catch (Exception $exception) {
            $this->setResponse("Error", 400, []);
            writeError("GET_ALL->" . $rqt, 400, $exception);
        }

        return $var;
        $req->closeCursor();
    }

    function getAll3($rqt, $item1, $item2, $item3)
    {
        $var = [];
        try {
            $req = $this->con()->prepare($rqt);
            $req->bindParam(1, $item1);
            $req->bindParam(2, $item2);
            $req->bindParam(3, $item3);
            $req->execute();
            while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
                $var[] = $data;
            }
        } catch (Exception $exception) {
            $this->setResponse("Error", 400, []);
            writeError("GET_ALL->" . $rqt, 400, $exception);
        }
        return $var;
        $req->closeCursor();
    }

    public function getValue2($rqt, $element1, $element2)
    {
        $var = null;
        try {
            $req = $this->con()->prepare($rqt);
            $req->bindParam(1, $element1);
            $req->bindParam(2, $element2);
            $req->execute();
            while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
                $var = $data['x'];
            }
        } catch (Exception $exception) {
            $this->setResponse("Error", 400, []);
            writeError("GET_ALL->" . $rqt, 400, $exception);
        }
        return $var;
    }

    public function getValue1($rqt, $element1)
    {
        $var = null;
        try {
            $req = $this->con()->prepare($rqt);
            $req->bindParam(1, $element1);
            $req->execute();
            while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
                $var = $data['x'];
            }
        } catch (Exception $exception) {
            $this->setResponse("Error", 400, []);
            writeError("GET_ALL->" . $rqt, 400, $exception);
        }

        return $var;
    }

    public function activeMenue($interface, $menue)
    {

        if (strtoupper($interface) == strtoupper($menue)) {
            echo 'active';
        }
    }

    public function isValue($message, $defaut)
    {
        if ($message == null) {
            echo $defaut;
        } else {
            echo $message;
        }
    }

    public function getImgPost($string)
    {
        if ($string != null && $string != '') {
            return 'img/' . $string;
        } else {
            return 'img/default.png';
        }
    }

    public function getStringDate_diff($date1)
    {
        $date = $this->getValue("select CURRENT_TIMESTAMP as x");
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date);

        $difference = $date1->diff($date2);
        $annee = $difference->y;
        $mois = $difference->m;
        $jour = $difference->d;
        $heur = $difference->h;
        $munite = $difference->i;

        $annee_ = 0;
        $mois_ = 0;
        $jour_ = 0;

        if (intval($annee) == 0) {
            $annee = "";
        } else {
            $annee_ = $annee;
            if ($annee == 1) {
                $annee = "1 Année ";
            } else {
                $annee = $annee . " Ans ";
            }
        }
        if ($mois == 0) {
            $mois = "";
        } else {
            $mois_ = $mois;
            $mois = "1 Mois ";
        }
        if ($jour == 0) {
            $jour = "";
        } else {
            $jour_ = $jour;
            if ($jour == 1) {
                $jour = "1 Jour ";
            } else {
                $jour = $jour . ' Jours ';
            }
        }
        if ($heur == 0) {
            $heur = "";
        } else {
            $heur = $heur . ' h ';
        }
        if ($munite == 0) {
            $munite = "";
        } else {
            $munite = $munite . ' Min';
        }
        if ($annee_ > 1) {
            return $annee . $mois;
        } elseif ($mois > 1) {
            return $mois . $jour . $heur . $munite;
        } elseif ($jour_ > 1) {
            return $jour;
        } else {
            return trim($annee . $mois . $jour . $heur . $munite);
        }
    }

    public function getValue($rqt)
    {
        $var = null;
        $req = $this->con()->prepare($rqt);
        $req->execute();
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
            $var = $data['x'];
        }
        return $var;
    }

    public function getShortName($texte)
    {
        return explode(" ", trim($texte));
    }

    public function saveMessage($tel, $message)
    {
        $codeCLient = $this->getValue("select code as x from clients where tel='$tel'");
        $querry = $this->get("INSERT INTO `messages`(`codeCLient`, `message`) VALUES (?,?)");
        $querry->bindParam(1, $codeCLient);
        $querry->bindParam(2, $message);
        $querry->execute();
    }

    public function getMonth(int $mois)
    {
        $retour = null;
        $short = null;
        switch ($mois) {
            case 1:
                $retour = "Janvier";
                $short = "Janv.";
                break;
            case 2:
                $retour = "février";
                $short = "févr.";
                break;
            case 3:
                $retour = "Mars";
                $short = "Mars.";
                break;
            case 4:
                $retour = "Avril";
                $short = "Avri.";
                break;
            case 5:
                $retour = "Mai";
                $short = "Mai.";
                break;
            case 6:
                $retour = "Juin";
                $short = "Juin.";
                break;
            case 7:
                $retour = "Juillet";
                $short = "Juil.";
                break;
            case 8:
                $retour = "Août";
                $short = "Août.";
                break;
            case 9:
                $retour = "Septembre";
                $short = "Sept.";
                break;
            case 10:
                $retour = "Octobre";
                $short = "Oct.";
                break;
            case 11:
                $retour = "Novembre";
                $short = "Nov.";
                break;
            case 12:
                $retour = "Décembre";
                $short = "Déc.";
                break;
            default:
                $retour = "Invalide !.";
                $short = "null";
                break;
        }
        return [$retour, $short];
    }


    function EmptyApi($required_params, $request)
    {
        $error = false;
        $error_params = '';
        $request_params = $request->getParsedBody();

        foreach ($required_params as $param) {
            if (!isset($request_params[$param]) || strlen($request_params[$param]) <= 0) {
                $error = true;
                $error_params .= $param . ', ';
            }
        }
        if ($error) {
            $this->data = ["message" => "Champ(s) vide(s) " . $error_params . "", "code" => 201, "data" => []];
        }
        return $error;
    }
}
