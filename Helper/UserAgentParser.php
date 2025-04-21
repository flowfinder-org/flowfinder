<?php

namespace FlowFinder\Helper;

require '../vendor/autoload.php'; // DomPDF utilise le systeme autoload de Composer

use FlowFinder\Helper\Helper;

class UserAgentParser
{
    public $os_family;
    public $os_version;
    public $device_type;
    public $browser_family;
    public $browser_version;
    public $device_brand;
    public $device_model;

    public function __construct($userAgent) {
        // Utilisation d'un parser d'user agent comme UAParser
        $this->parseUserAgent($userAgent);
    }

    private function parseUserAgent($userAgent) {
        // Instanciation de l'UAParser (assurez-vous d'avoir installé UAParser)
        $parser = \UAParser\Parser::create();
        $result = $parser->parse($userAgent);

        // Assigner les valeurs aux propriétés
        $this->os_family = $result->os->family;
        $this->os_version = $result->os->toString();
        $this->device_type = $result->device->family;
        $this->browser_family = $result->ua->family;
        $this->browser_version = $result->ua->toString();
        $this->device_brand = $result->device->brand;
        $this->device_model = $result->device->model;
    }
}

?>