<?php
// LogMailer.php
class LogMailer {
    public function send($from, $to, $subject, $body) {
        $logMessage = sprintf(
            "[%s] Correo simulado:\nFrom: %s\nTo: %s\nSubject: %s\nBody:\n%s\n\n",
            date('Y-m-d H:i:s'),
            $from,
            $to,
            $subject,
            $body
        );

        // Guardar en un archivo de log (ej. /tmp/mail.log)
        file_put_contents('logs/mail.log', $logMessage, FILE_APPEND);

        return true; // Simular éxito
    }
}

?>