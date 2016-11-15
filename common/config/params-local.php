<?php

include "c:/xampp/htdocs/intranet/config.php";

return [
 'logout_url'   => '../../login/web/index.php?r=site/logout',

 'local_path' => [
    'path_sitio'   => $path_sitio.'/',
    'path_firmas'      => $path_sitio.'/'.$path_firmas.'/',
    'path_hc'     => $path_sitio.'/'.$path_hc.'/',
    'path_img_adm'    => $path_sitio.'/'.$path_img_adm.'/',
    'path_snd'     => $path_sitio.'/'.$path_snd.'/',
    'path_imagenesDxI'   => $path_sitio.'/'.$path_imagenesDxI.'/',
    'path_etiq_poblac'   => $path_sitio.'/'.$path_etiq_poblac.'/',
    'path_farmacia'   => $path_sitio.'/'.$path_farmacia.'/',
    'path_txt_kairos'  => $path_sitio.'/'.$path_txt_kairos.'/',
    'path_deposito_central' => $path_sitio.'/'.$path_deposito_central.'/'
 ],

 'public_path' => [
    'path_firmas_url'     => '/'.$path_firmas.'/',
    'path_hc_url'    => '/'.$path_hc.'/',
    'path_img_adm_url'   => '/'.$path_img_adm.'/',
    'path_snd_url'    => '/'.$path_snd.'/',
    'path_imagenesDxI_url' => '/'.$path_imagenesDxI.'/',
    'path_etiq_poblac_url'  => '/'.$path_etiq_poblac.'/'
 ]
];
