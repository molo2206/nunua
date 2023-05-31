<?php
 
use Firebase\JWT\JWT;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use Courier\CourierClient;

function writeError($origin, $message)
{
    $date = date("Y/m/d h:i:sa");
    try {
        $last = file_get_contents(URL_LOG_FILE);
        file_put_contents(URL_LOG_FILE, "");

        file_put_contents(URL_LOG_FILE, array($last, ($last . '\n' . $origin . ' => ' . $date . "\n" . $message), PHP_EOL), FILE_APPEND);
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}


$error = null;


function saveFile($file, Request $request)
{
    $filename = null;
    try {
        $uploadedFiles = $request->getUploadedFiles();
        // handle single input with single file upload
        $uploadedFile = $uploadedFiles[$file];
        if ($uploadedFile != null) {
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filename = moveUploadedFile($uploadedFile);
            }
        }
    } catch (\Throwable $th) {
        writeError("SAVE_IMAGE_ON_SERVER", $th->getMessage());
    }

    return $filename;
}

function getShortName($name)
{
    return explode(' ', $name);
}

function moveUploadedFile(UploadedFile $uploadedFile)
{
    $directory = './upload/';
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}
function url()
{
    $url = $_SERVER['REQUEST_URI'];
    $tab = explode('/', $url);
    $i = "";
    if (count($tab) > 2) {
        for ($x = 2; $x < count($tab); $x++) {
            $i .= '../';
        }
    }
    echo $i;
}

function isEmpty($required_params, $request)
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

    return $error;
}

function random_strings($length_of_string)
{

    // String of all alphanumeric character
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Shuffle the $str_result and returns substring
    // of specified length
    return substr(
        str_shuffle($str_result),
        0,
        $length_of_string
    );
}

function getMessageApi($data, $code, Response $response)
{

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($code)
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
}

function getPays($telephone)
{

    $infos = [];
    $json = json_decode(file_get_contents('json/pays.json'));
    foreach ($json as $key => $value) {
        $pos = strpos($telephone, $value->Iso);

        if ($pos !== false) {
            $infos["id"] = $value->Iso;
            $infos["nom"] = $value->name;
            $infos["short"] = $value->countryCode;
        }
    }
    return $infos;
}

function encriptToken($id)
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

function getIdFromToken($request)
{
    $token = str_replace("Bearer ", "", (string) $request->getHeaderLine('Authorization'));

    if (!$token) return null;

    return (array)(JWT::decode($token, JWT_TOKEN_KEY, array('HS512')));
}


function sendSMS2($tel, $message)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://rest.messagebird.com/messages',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'recipients' => $tel,
            'originator' => 'Tiva',
            'body' => $message
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: AccessKey yUeZghMyRg0Y72595cKPjeQwG'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
}




