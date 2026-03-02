<?php
// app/controllers/ApiLikesSimple.php
namespace Api;

class ApiLikesSimple {
    function toggle($f3) {

        // Log para ver qué método está llegando
        error_log('Método recibido: ' . $f3->get('VERB'));
        error_log('URL: ' . $f3->get('PATH'));
        
        if ($f3->get('VERB') !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Se requiere POST']);
            return;
        }


        header('Content-Type: application/json');

        // Verificar autenticación
        $idUsuario = $f3->get('SESSION.usuario.id');
        if (!$idUsuario) {
            echo json_encode(['success' => false, 'error' => 'No autenticado']);
            return;
        }
        
        // Obtener datos
        $data = json_decode($f3->get('BODY'), true);
        $idMedia = $data['idMedia'] ?? 0;
        $accion = $data['accion'] ?? 'like'; // 'like' o 'unlike'
        
        try {
            
            if ($accion === 'like') {
                // Insertar like
                $f3->get('BD')->exec(
                    'INSERT INTO me_gusta (idUser_origen, idMedia, fecha) VALUES (?, ?, ?)',
                    [$idUsuario, $idMedia, time()]
                );
            } else {
                // Quitar like
                $f3->get('BD')->exec(
                    'DELETE FROM me_gusta WHERE idUser_origen = ? AND idMedia = ?',
                    [$idUsuario, $idMedia]
                );
            }
            
            // Obtener nuevo total
            $total = $f3->get('BD')->exec(
                'SELECT COUNT(*) as total FROM me_gusta WHERE idMedia = ?',
                [$idMedia]
            )[0]['total'];
            
            echo json_encode([
                'success' => true,
                'total_likes' => (int)$total
            ]);
            
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Error en la base de datos'
            ]);
        }
    }
}