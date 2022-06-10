<?php
function foot() {
    global $inline_css, $inline_script,$load_script,$plugins;
    $css = "";
    $script = "";
    if(file_exists("dashboard.js"))
        $load_script[] = "dashboard.js";
    foreach($inline_css as $value) {
        $css .= "<style>".$value."</style>";
    }
    foreach($inline_script as $value) {
        $script .= "<script>".$value."</script>";
    }
    foreach($load_script as $value) {
        $script .= "<script src='".$value."'></script>";
    }
    if(!is_null($plugins->hook_footer)) {
        foreach ($plugins->hook_footer as $value) {
            $script .= $value;
        }
    }
    return '    </main>
  </div>
</div>

    <script src="'.BASEDIR.'assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>    
    <script src="'.BASEDIR.'assets/js/array2excel.js"></script>
    '.$css.''.$script.'
  </body>
</html>
';
}