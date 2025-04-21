<?php

namespace FlowFinder\Controllers;

class SondageController extends TunnelController
{

    public function index()
    {
        if (!$this->verif_authentification(false)) {
            header('Location: /' . APP_LANG . '/');
            exit;
        }
    }

    public function loadtunnelt($parametres)
    {
        return $this->loadtunnel($parametres);
    }
}
