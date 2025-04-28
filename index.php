<?php
require 'vendor/autoload.php';

use eftec\bladeone\BladeOne;

// Configuración de Blade
$viewsPath = __DIR__ . '/templates'; // Ruta a la carpeta "templates"
$cachePath = __DIR__ . '/cache';     // Ruta a la carpeta "cache"


// Favicon.
$iconContent = file_get_contents('public/assets/images/favicon.svg');
$iconEncoded = 'data:image/svg+xml,' . rawurlencode($iconContent);

// Read assets/css/style.css and put into $assets object.
$assets = (object) [
    'css' => (object) [
        'styles' => file_get_contents('public/assets/css/styles.css'),  
    ],
    'images' => (object) [
        'favicon' => $iconEncoded,
    ]
];

// Asegúrate de que las carpetas existan
if (!is_dir($cachePath)) {
    mkdir($cachePath, 0755, true); // Crea la carpeta "cache" si no existe
}

// if GET variable "date" is set, use it as the date, otherwise use today
if (isset($_GET['date'])) {
    $today = new DateTime($_GET['date']);
} else {
    $today = new DateTime();
}

// Clonar la fecha de referencia para no modificar la original
$startOfWeek = clone $today;
$startOfWeek->modify('Monday this week');

$endOfWeek = clone $today;
$endOfWeek->modify('Sunday this week');

// Calcular año
$year = $today->format('Y');

// Calcular mes
$month = $today->format('F');

// Calcular semana del año
$week = $today->format('W');

// Calcular trimestre del año
$quarter = ceil($today->format('n') / 3);

// Calcular semana del mes
$firstDayOfMonth = new DateTime($today->format('Y-m-01'));
$weekMount = ceil(($today->format('j') + $firstDayOfMonth->format('N') - 1) / 7);

// Array para almacenar las fechas de cada día de la semana
$weekDates = [];
// Iterar desde el inicio hasta el fin de la semana
for ($date = clone $startOfWeek; $date <= $endOfWeek; $date->modify('+1 day')) {
    $weekDates[$date->format('l')] = (object) [
        'date'      => (string) $date->format('d/m/Y'),
        'dayOfYear' => (string) $date->format('z') + 1 // +1 porque 'z' es 0-indexado
    ];
}

$data = [
    'today'       => $today->format('Y-m-d'),
    'week'        => $week,
    'quarter'     => $quarter,
    'year'        => $year,
    'month'       => $month,
    'weekMount'   => $weekMount,
    'startOfWeek' => $startOfWeek->format('d/m/Y'),
    'endOfWeek'   => $endOfWeek->format('d/m/Y'),
	'working' => (object) [ 
        'hours'=> (object) [
            'step' => 1,
            'start' => 6,
            'end'   => 22
        ]
    ],
    'days' => [
        'Monday'    => (object) [
            'date'      => $weekDates['Monday']->date,
            'dayOfYear' => $weekDates['Monday']->dayOfYear,
            'working'   => true    
        ],
        'Tuesday'   => (object) [
            'date'      => $weekDates['Tuesday']->date,
            'dayOfYear' => $weekDates['Tuesday']->dayOfYear,
            'working'   => true
        ],
        'Wednesday' =>  (object) [
            'date'      => $weekDates['Wednesday']->date,
            'dayOfYear' => $weekDates['Wednesday']->dayOfYear,
            'working'   => true
        ],
        'Thursday'  =>  (object) [
            'date'      => $weekDates['Thursday']->date,
            'dayOfYear' => $weekDates['Thursday']->dayOfYear,
            'working'   => true    
        ],
        'Friday'    =>  (object) [
            'date'      => $weekDates['Friday']->date,
            'dayOfYear' => $weekDates['Friday']->dayOfYear,
            'working'   => true    
        ],
        'Saturday'  =>  (object) [
            'date'      => $weekDates['Saturday']->date,
            'dayOfYear' => $weekDates['Saturday']->dayOfYear,
            'working'   => false    
        ],
        'Sunday'    =>  (object) [
            'date'      => $weekDates['Sunday']->date,
            'dayOfYear' => $weekDates['Sunday']->dayOfYear,
            'working'   => false
        ],
    ]
];


$blade = new BladeOne($viewsPath, $cachePath, BladeOne::MODE_AUTO);
$html = $blade->run('a4-l-week-001', array_merge($data, array('assets' => $assets))); 
echo $html;

// Configurar y generar PDF con mPDF
// $mpdf = new \Mpdf\Mpdf([
//     'mode'          => 'utf-8',
//     'format'        => 'A4-L', // 'A4' en orientación landscape (297mm x 210mm)
//     'margin_left'   => 15,
//     'margin_right'  => 15,
//     'margin_top'    => 16,
//     'margin_bottom' => 16,
// ]);

// $mpdf->WriteHTML($html);
// 'I': Muestra el archivo PDF en el navegador (inline).
// 'D': Fuerza la descarga del archivo PDF.
// 'F': Guarda el archivo PDF en el servidor (filesystem).
// 'S': Devuelve el archivo PDF como una cadena en la salida (string).
// 'E': Devuelve el archivo PDF como base64 codificado.
// $mpdf->Output('reporte_blade.pdf', 'I');
