<?php 
require_once __DIR__ . '/vendor/autoload.php';

// $url = "http://localhost/pdf/response.json";
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$html = '
      <!DOCTYPE html>
      <html>
      <head>
          <meta  charset="UTF-8">
      </head>
      <body style="font-family: Arial;">
      <h1>REPORT</h1>
      <table>';
foreach ($data['report'] as $row) 
{
    $html .= '
        <tr>
            <td>
                <p><strong>' . $row['topic'] . '</strong></p>
                <p>' . $row['answer'] . '</p>
            </td>
        </tr>';
}
$html .= '</table><hr style="page-break-after: always;"><h1>RANK</h1><table style="border-collapse: collapse;">';

$k = 0;
for ( $i = 1; $i <= 3; $i++ )
{
    $html .= '<tr>';
    for ( $j = 1; $j <= 4; $j++ )
    {
        $row = $data['rank'][$k];
        $colspan = '';
        if ( $k == 10 )
        {
            // $colspan = '2';
            $j = 7;
        }
        $fontSize = 14;
        $bgColor = "#b0b0b0";
        if ( $row['rank'] <= 3 )
        {
            $fontSize = 20;
            $bgColor = "#e0e0e0";
        }
        $html .= '<td style="border: 1px solid black; padding: 10px; text-align: center; background-color: ' . $bgColor . ';" colspan="' . $colspan . '">
            <p style="font-size: ' . $fontSize . 'px;"><strong>' . $row['rank'] . '</strong></p><p>' . $row['topic'] . '</p>
        </td>';
        $k++;
    }
    $html .= '</tr>';
}

$html .= '</table></body></html>';

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('table.pdf', 'D');

