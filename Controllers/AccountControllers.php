<?php

use phpDocumentor\Reflection\Types\This;
use Slim\Http\Request;
use Slim\Http\Response;

class AccountControllers extends AccountModel{

    public function CreateCompteOnly(Request $request, Response $response){
    
        if(!$this->EmptyApi(["pays", "email", "pswd","ville"], $request, $response)){
              $this->pays = htmlspecialchars($request->getParsedBody()["pays"]);
              $this->email = htmlspecialchars($request->getParsedBody()["email"]);
              $this->pswd = htmlspecialchars($request->getParsedBody()["pswd"]);
              $this->ville = htmlspecialchars($request->getParsedBody()["ville"]);
              if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->CreateUserOnly();
            } else {
                $this->setResponse("Cette adresse e-mail est incorrecte!", 201, null);
            }
        }
        return $this->data;
    }
    public function UpdateCompteOnly(Request $request, Response $response){
        if(!$this->EmptyApi(["id_user"], $request, $response)){
            $this->full_name = htmlspecialchars($request->getParsedBody()["full_name"]);
            $this->phone = htmlspecialchars($request->getParsedBody()["phone"]);
            $this->profil = saveFile("profil", $request);
            $this->pays = htmlspecialchars($request->getParsedBody()["pays"]);
            $this->ville = htmlspecialchars($request->getParsedBody()["ville"]);
            $this->id_user = htmlspecialchars($request->getParsedBody()["id_user"]);
            $this->UpdateUserOnly();
            
        }
        return $this->data;
    }
    public function LoginCompteOnly(Request $request, Response $response){
        if(!$this->EmptyApi(["email","pswd"], $request, $response)){
            $this->email = htmlspecialchars($request->getParsedBody()["email"]);
            $this->pswd = htmlspecialchars($request->getParsedBody()["pswd"]);
            $this->LoginUseOnly();
        }
        return $this->data;
    }

    public function CreateCompteEntreprise(Request $request, Response $response){
    
        if(!$this->EmptyApi(["pays", "email", "pswd","ville","nameEntreprise","descriptionEntreprise"], $request, $response)){
              $this->pays = htmlspecialchars($request->getParsedBody()["pays"]);
              $this->email = htmlspecialchars($request->getParsedBody()["email"]);
              $this->pswd = htmlspecialchars($request->getParsedBody()["pswd"]);
              $this->ville = htmlspecialchars($request->getParsedBody()["ville"]);
              $this->name_entreprise = htmlspecialchars($request->getParsedBody()["nameEntreprise"]);
              $this->description_entre = htmlspecialchars($request->getParsedBody()["descriptionEntreprise"]);
              if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->CreateUser_Entreprise();
            } else {
                $this->setResponse("Cette adresse e-mail est incorrecte!", 201, null);
            }
        }
        return $this->data;
    }
    public function CreatePermission(Request $request, Response $response){
        if(!$this->EmptyApi(["id_user", "name_role", "permission"], $request, $response))
        {
            $this->id_user = htmlspecialchars($request->getParsedBody()["id_user"]);
            $this->name_role = htmlspecialchars($request->getParsedBody()["name_role"]);
            $this->name = htmlspecialchars($request->getParsedBody()["permission"]);
            $this->Permission();
        }
        return $this->data;
    }
}