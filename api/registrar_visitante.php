<?php

// api/registrar_visitante.php

require_once '../controllers/VisitantesController.php';

$controller = new VisitantesController();
$controller->registrarVisitanteAction();

?>