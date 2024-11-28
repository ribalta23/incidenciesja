<?php

class ControladorPermisos
{
    public function tePermisAdmin()
    {
        $rol = $_SESSION['usuari']['rol'];
        if ($rol == 'administrador') {
            return true;
        }
    }

    public function tePermisTecnic()
    {
        $rol = $_SESSION['usuari']['rol'];
        if ($rol == 'tecnic') {
            return true;
        }
    }

    public function tePermisUsuari()
    {
        $rol = $_SESSION['usuari']['rol'];
        if ($rol == 'usuari') {
            return true;
        }
    }
}