<?php
namespace app\models;
use yii\phppdf\Pdf;

class Document extends \yii\base\Model{
    
    public static function getPdfLeaveForm($id){
        $pdf=new Pdf();
        $pdf->AddPage();
        $pdf->Output('Formulir-Cuti','D');
        return $pdf;
    }
    
}