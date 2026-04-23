<?php

class ApiComentarios {
    private $db;
    
    function __construct() {
        $f3 = \Base::instance();
        $this->db = $f3->get('DB');
    }
    
    function crear($f3) {
        header('Content-Type: application/json');
        
        if (!$f3->get('SESSION.usuario.id')) {
            echo json_encode(['success' => false, 'error' => 'No autenticado']);
            return;
        }
        
        $data = json_decode($f3->get('BODY'), true);
        
        $comentario = new \mComentarios();
        $comentario->idUserOrigen = $f3->get('SESSION.usuario.id');
        $comentario->idUserDestino = $data['idUserDestino'];
        $comentario->comentario = $data['comentario'];
        $comentario->fecha = time();
        
        if ($comentario->save()) {
            echo json_encode([
                'success' => true,
                'comentario' => $comentario->cast()
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al guardar']);
        }
    }
    
    function actualizar($f3) {
        header('Content-Type: application/json');
        
        $id = $f3->get('PARAMS.id');
        $data = json_decode($f3->get('BODY'), true);
        
        $comentario = new \mComentarios();
        $comentario->load(['id = ? AND idUserOrigen = ?', $id, $f3->get('SESSION.usuario.id')]);
        
        if ($comentario->dry()) {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        $comentario->comentario = $data['comentario'];
        
        if ($comentario->save()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar']);
        }
    }
    
    function eliminar($f3) {
        header('Content-Type: application/json');
        
        $id = $f3->get('PARAMS.id');
        
        $comentario = new \mComentarios();
        $comentario->load(['id = ? AND idUserOrigen = ?', $id, $f3->get('SESSION.usuario.id')]);
        
        if ($comentario->dry()) {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        if ($comentario->erase()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar']);
        }
    }
}

?>