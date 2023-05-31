<?php

use phpDocumentor\Reflection\Types\This;

class AccountModel extends dbo
{
    public $id_types, $name_type, $date_add_type, $id_user_type,
        $id_user, $full_name, $email, $phone, $pswd, $profil, $pays, $ville, $id_entreprise, $name_entreprise,
        $description_entre, $idnat, $rccm, $status, $id_engag, $id_user_engag, $id_permission, $name, $id_entrep,
        $id_role, $id_entrep_role, $name_role, $id_aff, $id_role_aff, $id_permis_aff, $token, $id_af_use, $id_user_mod;

    // Create user Only
    public function CreateUserOnly()
    {
        try {

            $email_test = $this->getValue1("SELECT email as x FROM t_users WHERE email=?", $this->email);
            $phone_test = $this->getValue1("SELECT phone as x FROM t_users WHERE phone=?", $this->phone);

            if (!$email_test == "") {
                $this->setResponse("Cette adresse e-mail existe déjà!", 201, null);
            } else {
                if (!$phone_test == null) {
                    $this->setResponse("Numero de téléphone existe!", 201, null);
                } else {
                    if (($this->getValue1("SELECT name_type as x FROM t_types WHERE name_type=?", $this->name_type)) == null) {
                        $this->name_type = "customer";
                        $this->id_types = $this->getValue1("SELECT id_types as x FROM t_types WHERE name_type=?", $this->name_type);

                        $this->id_user = $this->generateId();
                        $this->id_af_use = $this->generateId();
                        $this->status = 1;
                        $this->token = $this->generateId();

                        if (preg_match(REGLEX_PASSWORD, $this->pswd)) {
                            $this->pswd = md5($this->pswd);

                            $rqt = $this->get("INSERT INTO t_users SET `id_user`=?,`full_name`=?,`email`=?,`phone`=?,
                                `pswd`=?,`profil`=?,`pays`=?,`ville`=?,`status_user`=?,`token`=?");
                            $rqt->bindParam(1, $this->id_user);
                            $rqt->bindParam(2, $this->full_name);
                            $rqt->bindParam(3, $this->email);
                            $rqt->bindParam(4, $this->phone);
                            $rqt->bindParam(5, $this->pswd);
                            $rqt->bindParam(6, $this->profil);
                            $rqt->bindParam(7, $this->pays);
                            $rqt->bindParam(8, $this->ville);
                            $rqt->bindParam(9, $this->status);
                            $rqt->bindParam(10, $this->token);
                            $rqt->execute();

                            $rqt = $this->get("INSERT INTO t_affect_type_user SET `id_user_type`=?,`id_types_`=?, `id_af_use`=?");
                            $rqt->bindParam(1, $this->id_user);
                            $rqt->bindParam(2, $this->id_types);
                            $rqt->bindParam(3, $this->id_af_use);
                            $rqt->execute();

                            $this->setResponse(
                                "success",
                                200,
                                $this->getAll('SELECT * FROM t_users tu 
                                INNER JOIN t_affect_type_user tyu on tyu.id_user_type=tu.id_user
                                INNER JOIN t_types ty on ty.id_types=tyu.id_types_;')
                            );
                        } else {
                            $this->setResponse("Mot de passe n'est pas valide", 201, null);
                        }
                    } else {
                        $this->id_user = $this->generateId();
                        $this->id_af_use = $this->generateId();
                        $this->status = 1;
                        $this->token = $this->generateId();
                        $this->id_types = $this->getValue1("SELECT id_types as x FROM t_types WHERE name_type=?", $this->name_type);

                        if (preg_match(REGLEX_PASSWORD, $this->pswd)) {
                            $this->pswd = md5($this->pswd);

                            $rqt = $this->get("INSERT INTO t_users SET `id_user`=?,`full_name`=?,`email`=?,`phone`=?,
                                `pswd`=?,`profil`=?,`pays`=?,`ville`=?,`status_user`=?,`token`=?");
                            $rqt->bindParam(1, $this->id_user);
                            $rqt->bindParam(2, $this->full_name);
                            $rqt->bindParam(3, $this->email);
                            $rqt->bindParam(4, $this->phone);
                            $rqt->bindParam(5, $this->pswd);
                            $rqt->bindParam(6, $this->profil);
                            $rqt->bindParam(7, $this->pays);
                            $rqt->bindParam(8, $this->ville);
                            $rqt->bindParam(9, $this->status);
                            $rqt->bindParam(10, $this->token);
                            $rqt->execute();


                            $rqt = $this->get("INSERT INTO t_affect_type_user SET `id_user_type`=?,`id_types_`=?, `id_af_use`=?");
                            $rqt->bindParam(1, $this->id_user);
                            $rqt->bindParam(2, $this->id_types);
                            $rqt->bindParam(3, $this->id_af_use);
                            $rqt->execute();

                            $this->setResponse(
                                "success",
                                200,
                                $this->getAll1('SELECT * FROM t_users tu 
                                INNER JOIN t_affect_type_user tyu on tyu.id_user_type=tu.id_user
                                INNER JOIN t_types ty on ty.id_types=tyu.id_types_ WHERE id_user=?', $this->id_user)
                            );
                        } else {
                            $this->setResponse("Mot de passe n'est pas valide", 201, null);
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            writeError('CREATE_ACCOUNT', $th);
            $this->setResponse("Erreur dans la requête, veuillez contacter l'admin!", 202, null);
        }
    }

    public function CreateUser_Entreprise()
    {
        $email_test = $this->getValue1("SELECT email as x FROM t_users WHERE email=?", $this->email);
        $phone_test = $this->getValue1("SELECT phone as x FROM t_users WHERE phone=?", $this->phone);

        if (!$email_test == "") {
            $this->setResponse("Cette adresse e-mail existe déjà!", 201, null);
        } else {
            if (!$phone_test == null) {
                $this->setResponse("Numero de téléphone existe!", 201, null);
            } else {
                if (($this->getValue1("SELECT name_type as x FROM t_types WHERE name_type=?", $this->name_type)) == null) {
                    $this->name_type = "Vendor";
                    $this->id_types = $this->getValue1("SELECT id_types as x FROM t_types WHERE name_type=?", $this->name_type);

                    $this->id_user = $this->generateId();
                    $this->id_engag = $this->generateId();
                    $this->id_entreprise = $this->generateId();
                    $this->id_af_use = $this->generateId();
                    $this->status = 1;

                    if (preg_match(REGLEX_PASSWORD, $this->pswd)) {
                        $this->pswd = md5($this->pswd);

                        $rqt = $this->get("INSERT INTO t_users SET `id_user`=?,`full_name`=?,`email`=?,`phone`=?,
                                        `pswd`=?,`profil`=?,`pays`=?,`ville`=?,`status_user`=?,`token`=?");
                        $rqt->bindParam(1, $this->id_user);
                        $rqt->bindParam(2, $this->full_name);
                        $rqt->bindParam(3, $this->email);
                        $rqt->bindParam(4, $this->phone);
                        $rqt->bindParam(5, $this->pswd);
                        $rqt->bindParam(6, $this->profil);
                        $rqt->bindParam(7, $this->pays);
                        $rqt->bindParam(8, $this->ville);
                        $rqt->bindParam(9, $this->status);
                        $rqt->bindParam(10, $this->token);
                        $rqt->execute();

                        $rqt = $this->get("INSERT INTO t_affect_type_user SET `id_user_type`=?,`id_types_`=?, `id_af_use`=?");
                        $rqt->bindParam(1, $this->id_user);
                        $rqt->bindParam(2, $this->id_types);
                        $rqt->bindParam(3, $this->id_af_use);
                        $rqt->execute();

                        $rqt = $this->get("INSERT INTO t_entreprise SET `id_entreprise`=?,
                                    `name_entreprise`=?,`description_entre`=? ,`idnat`=? ,`rccm`=? ,status=?");
                        $rqt->bindParam(1, $this->id_entreprise);
                        $rqt->bindParam(2, $this->name_entreprise);
                        $rqt->bindParam(3, $this->description_entre);
                        $rqt->bindParam(4, $this->idnat);
                        $rqt->bindParam(5, $this->rccm);
                        $rqt->bindParam(6, $this->status);
                        $rqt->execute();

                        $rqt = $this->get("INSERT INTO t_engagement SET `id_engag`=?,
                                    `id_user_engag`=?, `id_entrep`=?");
                        $rqt->bindParam(1, $this->id_engag);
                        $rqt->bindParam(2, $this->id_user);
                        $rqt->bindParam(3, $this->id_entreprise);
                        $rqt->execute();

                        $this->setResponse(
                            "success",
                            200,
                            $this->getAll1('SELECT * FROM t_users tu 
                                        INNER JOIN t_engagement te on te.id_user_engag=tu.id_user
                                        INNER JOIN t_entreprise t on t.id_entreprise=te.id_entrep 
                                        WHERE id_user=?', $this->id_user)
                        );
                    }
                } else {
                    $this->id_user = $this->generateId();
                    $this->id_engag = $this->generateId();
                    $this->id_entreprise = $this->generateId();
                    $this->id_af_use = $this->generateId();
                    $this->status = 1;

                    if (preg_match(REGLEX_PASSWORD, $this->pswd)) {
                        $this->pswd = md5($this->pswd);

                        $rqt = $this->get("INSERT INTO t_users SET `id_user`=?,`full_name`=?,`email`=?,`phone`=?,
                                        `pswd`=?,`profil`=?,`pays`=?,`ville`=?,`status_user`=?,`token`=?");
                        $rqt->bindParam(1, $this->id_user);
                        $rqt->bindParam(2, $this->full_name);
                        $rqt->bindParam(3, $this->email);
                        $rqt->bindParam(4, $this->phone);
                        $rqt->bindParam(5, $this->pswd);
                        $rqt->bindParam(6, $this->profil);
                        $rqt->bindParam(7, $this->pays);
                        $rqt->bindParam(8, $this->ville);
                        $rqt->bindParam(9, $this->status);
                        $rqt->bindParam(10, $this->token);
                        $rqt->execute();

                        $rqt = $this->get("INSERT INTO t_affect_type_user SET `id_user_type`=?,`id_types_`=?, `id_af_use`=?");
                        $rqt->bindParam(1, $this->id_user);
                        $rqt->bindParam(2, $this->id_types);
                        $rqt->bindParam(3, $this->id_af_use);
                        $rqt->execute();

                        $rqt = $this->get("INSERT INTO t_entreprise SET `id_entreprise`=?,
                                    `name_entreprise`=?,`description_entre`=? ,`idnat`=? ,`rccm`=? ,status=?");
                        $rqt->bindParam(1, $this->id_entreprise);
                        $rqt->bindParam(2, $this->name_entreprise);
                        $rqt->bindParam(3, $this->description_entre);
                        $rqt->bindParam(4, $this->idnat);
                        $rqt->bindParam(5, $this->rccm);
                        $rqt->bindParam(6, $this->status);
                        $rqt->execute();

                        $rqt = $this->get("INSERT INTO t_engagement SET `id_engag`=?,
                                    `id_user_engag`=?, `id_entrep`=?");
                        $rqt->bindParam(1, $this->id_engag);
                        $rqt->bindParam(2, $this->id_user);
                        $rqt->bindParam(3, $this->id_entreprise);
                        $rqt->execute();

                        $this->setResponse(
                            "success",
                            200,
                            $this->getAll1('SELECT * FROM t_users tu 
                                        INNER JOIN t_engagement te on te.id_user_engag=tu.id_user
                                        INNER JOIN t_entreprise t on t.id_entreprise=te.id_entrep 
                                        WHERE id_user=?', $this->id_user)
                        );
                    }
                }
            }
        }
    }
    public function Permission()
    {
        if (!$this->getValue1("SELECT id_user_engag as x FROM t_engagement WHERE id_user_engag=?", $this->id_user) == null) {

            $this->id_engag = $this->getValue1("SELECT id_engag as x FROM t_engagement WHERE id_user_engag=?", $this->id_user);
            $this->id_entrep = $this->getValue1("SELECT id_entrep as x FROM t_engagement WHERE id_user_engag=?", $this->id_user);
            $this->id_role = $this->getValue2("SELECT id_role as x FROM t_roles WHERE id_entrep_role=? and name_role=?", $this->id_entrep, $this->name_role);
            $this->id_permission = $this->getValue1("SELECT id_permission as x FROM t_permissions WHERE name=?", $this->name);

            if ($this->getValue2("SELECT id_aff as x FROM t_affectation WHERE id_eng_aff=? and id_permis_aff=?", $this->id_engag, $this->id_permission) == null) {
                $rqt = $this->get("INSERT INTO t_affectation SET `id_eng_aff`=?, `id_role_aff`=?, `id_permis_aff`=?, `status`=1");
                $rqt->bindParam(1, $this->id_engag);
                $rqt->bindParam(2, $this->id_role);
                $rqt->bindParam(3, $this->id_permission);
                $rqt->execute();

                $this->setResponse(
                    "success",
                    200,
                    $this->getAll1('SELECT * FROM t_users tu 
                    INNER JOIN t_engagement te on te.id_user_engag=tu.id_user
                    INNER JOIN t_entreprise t on t.id_entreprise=te.id_entrep 
                    INNER JOIN t_roles tr on tr.id_entrep_role=t.id_entreprise
                    INNER JOIN t_affectation taf on taf.id_eng_aff=te.id_engag
                    INNER JOIN t_permissions tp on tp.id_permission=taf.id_permis_aff
                    WHERE tu.id_user=?', $this->id_user)
                );
            } else {
                $this->setResponse(
                    "Permission exist!",
                    202,
                    $this->getAll1('SELECT * FROM t_users tu 
                    INNER JOIN t_engagement te on te.id_user_engag=tu.id_user
                    INNER JOIN t_entreprise t on t.id_entreprise=te.id_entrep 
                    INNER JOIN t_roles tr on tr.id_entrep_role=t.id_entreprise
                    INNER JOIN t_affectation taf on taf.id_eng_aff=te.id_engag
                    INNER JOIN t_permissions tp on tp.id_permission=taf.id_permis_aff
                    WHERE tu.id_user=?', $this->id_user)
                );
               
            }
        }else{

        }
    }
    public function CreateTypeUser()
    {
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function UpdateUserOnly()
    {
        try {
            if ((!$this->getValue1("SELECT id_user as x  FROM t_users WHERE id_user = ?", $this->id_user)) == null) {
                $rqt = $this->get("UPDATE  t_users SET `full_name`=?,`phone`=?, 
                     `profil`=?,`pays`=?,`ville`=? WHERE id_user=?");

                $rqt->bindParam(1, $this->full_name);
                $rqt->bindParam(2, $this->phone);
                $rqt->bindParam(3, $this->profil);
                $rqt->bindParam(4, $this->pays);
                $rqt->bindParam(5, $this->ville);
                $rqt->bindParam(6, $this->id_user);
                $rqt->execute();

                $rqt = $this->get("INSERT INTO t_modification_user SET `id_user_mod`=?");
                $rqt->bindParam(1, $this->id_user);
                $rqt->execute();

                $this->setResponse(
                    "success",
                    200,
                    $this->getAll1('SELECT * FROM t_users tu 
                            INNER JOIN t_affect_type_user tyu on tyu.id_user_type=tu.id_user
                            INNER JOIN t_types ty on ty.id_types=tyu.id_types_ WHERE id_user=?', $this->id_user)
                );
            } else {

                $this->setResponse("C'est identifiant n'existe pas!", 201, null);
            }
        } catch (\Throwable $th) {
            writeError('UPDATE_ACCOUNT', $th);
            $this->setResponse("Erreur dans la requête, veuillez contacter l'admin!", 202, null);
        }
    }

    public function UpdateUser_Entreprise()
    {
    }

    public function DeleteUser_Entreprise()
    {
    }

    public function DeleteUserOnly()
    {
    }

    public function LoginUseOnly()
    {
        try {
            $vals = $this->getAll2("SELECT * FROM t_users tu 
               INNER JOIN t_affect_type_user tyu on tyu.id_user_type=tu.id_user
               INNER JOIN t_types ty on ty.id_types=tyu.id_types_ WHERE email=? AND pswd=md5(?)", $this->email, $this->pswd);

            $data = [];
            if (count($vals) > 0) {
                foreach ($vals as $key => $value) {
                    $data["id_user"] = $value["id_user"];
                    $data["full_name"] = $value["full_name"];
                    $data["email"] = $value["email"];
                    $data["phone"] = $value["phone"];
                    $data["profil"] = $value["profil"];
                    $data["pays"] = $value["pays"];
                    $data["ville"] = $value["ville"];
                    $data["name_type"] = $value["name_type"];

                    $rqt = $this->get("INSERT INTO t_historiquelog SET `id_user_histo`=?");
                    $rqt->bindParam(1, $value["id_user"]);
                    $rqt->execute();
                }
                $this->setResponse('success', 200, $data);
            } else {
                $this->setResponse('Email ou mot de passe incorecte', 201, $data);
            }
        } catch (\Throwable $th) {
            writeError('LOGIN', $th);
            $this->setResponse("Erreur dans la requête, veuillez contacter l'admin!", 202, null);
        }
    }
}
