<?php
// app/controllers/ApiLikes.php
namespace Api;

class ApiLikes {
    private $db;
    private $f3;
    
    function __construct() {
        $this->f3 = \Base::instance();
        $this->db = $this->f3->get('DB');
    }
    
    /**
     * Toggle like/unlike
     * Endpoint: POST /api/like/toggle
     * Body: { "idMedia": 123 }
     */
    function toggle() {
        // Configurar respuesta JSON
        header('Content-Type: application/json');
        
        // Verificar que el usuario está logueado
        $idUsuario = $this->f3->get('SESSION.usuario.id');
        if (!$idUsuario) {
            echo json_encode([
                'success' => false,
                'error' => 'Usuario no autenticado',
                'code' => 'AUTH_REQUIRED'
            ]);
            return;
        }
        
        // Obtener datos del POST
        $data = json_decode($this->f3->get('BODY'), true);
        $idMedia = $data['idMedia'] ?? 0;
        
        // Validar datos
        if (!$idMedia) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de medio no proporcionado',
                'code' => 'INVALID_DATA'
            ]);
            return;
        }
        
        try {
            // Verificar que el medio existe
            $media = new \mMedia();
            $media->load(['id = ?', $idMedia]);
            
            if ($media->dry()) {
                echo json_encode([
                    'success' => false,
                    'error' => 'El medio no existe',
                    'code' => 'MEDIA_NOT_FOUND'
                ]);
                return;
            }
            
            // Verificar si ya existe el like
            $like = new \mMeGusta();
            $like->load([
                'idUser_origen = ? AND idMedia = ?', 
                $idUsuario, 
                $idMedia
            ]);
            
            $accion = '';
            
            if ($like->dry()) {
                // No existe like -> CREAR
                $like->idUser_origen = $idUsuario;
                $like->idMedia = $idMedia;
                $like->fecha = time(); // Si tienes campo fecha
                $like->save();
                $accion = 'like';
                
                // Opcional: Notificar al dueño del medio
                $this->notificarNuevoLike($idMedia, $idUsuario);
            } else {
                // Ya existe like -> ELIMINAR
                $like->erase();
                $accion = 'unlike';
            }
            
            // Obtener el nuevo total de likes
            $totalLikes = $this->db->exec(
                'SELECT COUNT(*) as total FROM me_gusta WHERE idMedia = ?',
                $idMedia
            )[0]['total'];
            
            // Respuesta exitosa
            echo json_encode([
                'success' => true,
                'accion' => $accion,
                'total_likes' => (int)$totalLikes,
                'message' => $accion === 'like' ? 'Like agregado' : 'Like eliminado'
            ]);
            
        } catch (\Exception $e) {
            // Log del error
            error_log('Error en toggle like: ' . $e->getMessage());
            
            echo json_encode([
                'success' => false,
                'error' => 'Error interno del servidor',
                'code' => 'SERVER_ERROR'
            ]);
        }
    }
    
    /**
     * Obtener estado del like para un usuario específico
     * Endpoint: GET /api/like/estado/@idMedia
     */
    function estado($f3) {
        header('Content-Type: application/json');
        
        $idMedia = $f3->get('PARAMS.idMedia');
        $idUsuario = $f3->get('SESSION.usuario.id') ?? 0;
        
        if (!$idUsuario) {
            echo json_encode([
                'success' => true,
                'liked' => false,
                'usuario_autenticado' => false
            ]);
            return;
        }
        
        $like = new \mMeGusta();
        $existe = $like->count([
            'idUser_origen = ? AND idMedia = ?', 
            $idUsuario, 
            $idMedia
        ]) > 0;
        
        echo json_encode([
            'success' => true,
            'liked' => $existe,
            'usuario_autenticado' => true
        ]);
    }
    
    /**
     * Obtener total de likes de un medio
     * Endpoint: GET /api/like/total/@idMedia
     */
    function total($f3) {
        header('Content-Type: application/json');
        
        $idMedia = $f3->get('PARAMS.idMedia');
        
        $total = $this->db->exec(
            'SELECT COUNT(*) as total FROM me_gusta WHERE idMedia = ?',
            $idMedia
        )[0]['total'];
        
        echo json_encode([
            'success' => true,
            'total_likes' => (int)$total
        ]);
    }
    
    /**
     * Obtener todos los likes de un medio (para depuración o admin)
     */
    function listar($f3) {
        header('Content-Type: application/json');
        
        // Solo admins o propietarios pueden ver esto
        if (!$this->esAdminOPropietario()) {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        $idMedia = $f3->get('PARAMS.idMedia');
        
        $likes = $this->db->exec(
            'SELECT mg.*, u.nombre as usuario_nombre 
             FROM me_gusta mg
             LEFT JOIN usuarios u ON mg.idUser_origen = u.id
             WHERE mg.idMedia = ?
             ORDER BY mg.fecha DESC',
            $idMedia
        );
        
        echo json_encode([
            'success' => true,
            'likes' => $likes
        ]);
    }
    
    /**
     * Función privada para notificaciones
     */
    private function notificarNuevoLike($idMedia, $idUsuarioOrigen) {
        // Aquí puedes implementar notificaciones
        // Por ejemplo: guardar en tabla notificaciones, enviar email, etc.
        
        $media = new \mMedia();
        $media->load(['id = ?', $idMedia]);
        
        if (!$media->dry()) {
            // El dueño del medio es $media->idUser
            $notificacion = new \mNotificacion();
            $notificacion->idUser_destino = $media->idUser;
            $notificacion->idUser_origen = $idUsuarioOrigen;
            $notificacion->tipo = 'like';
            $notificacion->idMedia = $idMedia;
            $notificacion->fecha = time();
            $notificacion->leida = 0;
            $notificacion->save();
        }
    }
    
    /**
     * Verificar si es admin o propietario
     */
    private function esAdminOPropietario() {
        $f3 = \Base::instance();
        $usuario = $f3->get('SESSION.usuario');
        
        if (!$usuario) return false;
        
        // Verificar si es admin
        if ($usuario['rol'] === 'admin') return true;
        
        // Verificar si es propietario del medio (requiere idMedia en PARAMS)
        $idMedia = $f3->get('PARAMS.idMedia');
        if ($idMedia) {
            $media = new \mMedia();
            $media->load(['id = ?', $idMedia]);
            return !$media->dry() && $media->idUser == $usuario['id'];
        }
        
        return false;
    }
}
?>