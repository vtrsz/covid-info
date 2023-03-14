<link href="{{ asset('/css/navbar.css') }}" rel="stylesheet">

<nav class="navbar">
    <?php
        $server = (!app()->runningInConsole()) ? request()->server('SERVER_NAME') : gethostname();
        $protocol = (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) ? 'https://' : 'http://';
        echo "<a href=\"". $protocol . $server . ":" . $_SERVER['SERVER_PORT'] ."/\">";
    ?>
    <p>COVID INFO</p></a>
</nav>
