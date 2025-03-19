<?php
class RedirectService {
    public function redirect(string $url, int $delay): void {
        $javascriptRedirect = "
            <script>
                setTimeout(() => {
                    window.location.href = '{$url}';
                }, {$delay});
            </script>
        ";
    
        echo $javascriptRedirect;
    }
    
}
?>