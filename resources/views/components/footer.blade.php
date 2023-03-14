<link href="{{ asset('/css/footer.css') }}" rel="stylesheet">

<footer>
    <span>
        <?php
            $lastAppointment = DB::table('last_appointment_time')->latest('updated_at')->first();
            $server = (!app()->runningInConsole()) ? request()->server('SERVER_NAME') : gethostname();
            $protocol = (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) ? 'https://' : 'http://';
            $date = $lastAppointment != null ? strtotime($lastAppointment->updated_at) : null;
            //echo $lastAppointment != null ? "Last country searched: <a href=\"". $protocol . $server . ":" . $_SERVER['SERVER_PORT'] ."/result?country=" . $lastAppointment->country . "\"><strong>" . $lastAppointment->country . "</strong></a> on time: <strong>" . $lastAppointment->updated_at . "</strong>" : 'There is no last query';
            echo $lastAppointment != null ? "Last searched country: <a href=\"". $protocol . $server . ":" . $_SERVER['SERVER_PORT'] ."/result?country=" . $lastAppointment->country . "\"><strong>" . $lastAppointment->country . "</strong></a> in day: <strong>". date('d/m/Y', $date) . "</strong> on time: <strong>" . date('H:i:s', $date) ."</strong>" : 'There is no last searched countries';
        ?>
    </span>
    <span>
        <?php
            $lastComparison = DB::table('last_comparison_time')->latest('updated_at')->first();
            $server = (!app()->runningInConsole()) ? request()->server('SERVER_NAME') : gethostname();
            $protocol = (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) ? 'https://' : 'http://';
            $date = $lastComparison != null ? strtotime($lastComparison->updated_at) : null;
            echo $lastComparison != null ? "Last compared countries: <a href=\"". $protocol . $server . ":" . $_SERVER['SERVER_PORT'] ."/comparison?firstCountry=" . $lastComparison->first_country . "&secondCountry=". $lastComparison->second_country ."\"><strong>". $lastComparison->first_country . "</strong> and <strong>" . $lastComparison->second_country . "</strong></a> in day: <strong>". date('d/m/Y', $date) . "</strong> on time: <strong>" . date('H:i:s', $date) ."</strong>" : 'There is no last compared countries';
        ?>
    </span>
    <span>Developed by <a href="https://www.linkedin.com/in/vtrsz/" target="_blank"><strong>Vítor Souza</strong></a></span>
</footer>
