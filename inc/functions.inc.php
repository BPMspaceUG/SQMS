<?php
/**
 * Created by PhpStorm.
 * User: cwalonka
 * Date: 06.10.15
 * Time: 19:11
 */

/**
 * @return array mit übergebenen Parametern (...index.php/x/y -> array('x','y'))
 */
function getRoute()
{
    /* Bsp (http://localhost:4040/EduMS/api/whatami.php/a/j?q=u):
    * SCRIPT_NAME /EduMS/api/whatami.php
    * REQUEST_URI /EduMS/api/whatami.php/a/j?q=u
    
    http://localhost:4040/EduMS-client/index.php/partner1/abc?navdest=topics&contentid=1
    */
    //explode splited URL bsp:'/EduMS/api/whatami.php' und gibt ein Array zurrück
    //array_slice 0,-1 entfernt letzten Eintrag in Array bsp:0 |1 EduMS |2 api |3 whatami.php -> 0 | 1 EduMs |2 api
    //implode '/' concat array to String mit Trenner '/' bsp: 0 | 1 EduMs |2 api -> '/EduMs/api/'
    $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';

    //substr(str,pos) gibt Reststring nach pos zurrück
    //erstellen eines URI mit der Länge von $basepath bsp: '/EduMS/api/whatami.php/a/j?q=u' -> 'whatami.php/a/j?q=u'
    $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));

    //Wenn $uri ein '?' enthält, schneide alles von ? bis stringende bsp: whatami.php/a/j?q=u -> 'whatami.php/a/j'
    if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));

    // erstelle einen uri aus '/'+$uri(ohne '/') bsp: 'whatami.php/a/j' -> '/whatami.php/a/j'
    $uri = '/' . trim($uri, '/');

    $routes = array();
    $routes = explode('/', $uri);//bsp: '/whatami.php/a/j' -> 0 | 1 whatami.php | 2 a | 3 j

    $result = array();
    //bsp:  0 | 1 whatami.php | 2 a | 3 j -> 0 a | 1 j
    foreach($routes as $route)
    {
        //Wenn nicht leer UND '.php' nicht enthalten ist
        if(trim($route) != '' && strrpos(trim($route),'.php')==0)// (0==false) -> true
            array_push($result, $route);
    }

    return $result; 
}

?>