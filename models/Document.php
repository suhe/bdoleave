<?php
namespace app\models;
use Yii;
use TCPDF;
use yii\phpexcel\PHPExcel;

class Document extends \yii\base\Model{
    
    public static function getPdfLeaveForm($id){
        $model = new \app\models\Leaves();
        $value = $model->getLeaveSingleData($id);
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->AddPage();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $x=10;$y=3;
        $pdf->SetXY($x,$y);
        $pdf->SetFont('Helvetica','B',12,'','false');
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(180,10,"BDO TANUBRATA",0,0,'L',false,'',0,10,'T','M');
        
         
        $x=15;$y=13;
        $pdf->SetXY($x,$y);
        $pdf->SetFont('Helvetica','B',12,'','false');
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(180,10,"PERMOHONAN CUTI-ELEKTRONIK",0,0,'C',false,'',0,10,'T','M');
        
        //SIGNATURE 
        $pdf->SetFont('Helvetica','',10,'','false');
        $x=$x;$y=$y+20;
        $pdf->setXY($x,$y);
        $pdf->Cell(180,10,"Yang bertanda tangan di bawah ini  :",0,0,'L',false,'',0,10,'T','M');
        
        //Employee NIK
        $x=$x;$y=$y+10;
        $pdf->setXY($x,$y);
        $pdf->Cell(32,10,"NIK",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+32,$y);
        $pdf->Cell(10,10,":",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+37,$y);
        $pdf->Cell(10,10,$value->employeeid,0,0,'L',false,'',0,10,'T','M');
        
        //Employee Name
        $x=$x;$y=$y+5;
        $pdf->setXY($x,$y);
        $pdf->Cell(32,10,"Nama",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+32,$y);
        $pdf->Cell(10,10,":",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+37,$y);
        $pdf->Cell(10,10,$value->employee_name,0,0,'L',false,'',0,10,'T','M');
        
        //Department
        $x=$x;$y=$y+5;
        $pdf->setXY($x,$y);
        $pdf->Cell(32,10,"Bagian",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+32,$y);
        $pdf->Cell(10,10,":",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+37,$y);
        $pdf->Cell(10,10,$value->department,0,0,'L',false,'',0,10,'T','M');
        
        //pengajuan
        $x=$x;$y=$y+10;
        $pdf->setXY($x,$y);
        $pdf->Cell(180,10,"Dengan ini mengajukan cuti dengan perincian sebagai berikut : ",0,0,'L',false,'',0,10,'T','M');
        
        /** Jumlah Cuti **/
        $x=$x;$y=$y+10;
        $pdf->setXY($x,$y);
        $pdf->Cell(32,10,"Pengajuan ",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+32,$y);
        $pdf->Cell(10,10,":",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+37,$y);
        $pdf->Cell(10,10,$value->leave_total.' Hari Kerja',0,0,'L',false,'',0,10,'T','M');
        
        /** Jumlah Cuti **/
        $x=$x;$y=$y+5;
        $pdf->setXY($x,$y);
        $pdf->Cell(32,10,"Tanggal ",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+32,$y);
        $pdf->Cell(10,10,":",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+37,$y);
        $pdf->Cell(10,10,\app\components\Common::dateToString($value->leave_date_from).' s/d '.\app\components\Common::dateToString($value->leave_date_to),0,0,'L',false,'',0,10,'T','M');
        
         /** Keperluan **/
        $x=$x;$y=$y+5;
        $pdf->setXY($x,$y);
        $pdf->Cell(32,10,"Keperluan ",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+32,$y);
        $pdf->Cell(10,10,":",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+37,$y);
        $pdf->Cell(10,10,$value->leave_description,0,0,'L',false,'',0,10,'T','M');
        
        /** Alamat Cuti **/
        $x=$x;$y=$y+5;
        $pdf->setXY($x,$y);
        $pdf->Cell(32,10,"Alamat selama Cuti ",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+32,$y);
        $pdf->Cell(10,10,":",0,0,'L',false,'',0,10,'T','M');
        $pdf->setXY($x+37,$y);
        $pdf->Cell(10,10,$value->leave_address,0,0,'L',false,'',0,10,'T','M');
        
        //tanda tangan pemohon
        $x=$x;$y=$y+10;
        $pdf->setXY($x,$y);
        $pdf->Cell(180,10,"Demikianlah permohonan cuti saya ini, terima kasih ",0,0,'L',false,'',0,10,'T','M');
        $x=$x;$y=$y;
        $pdf->setXY($x+130,$y+10);
        $pdf->Cell(30,10,"Jakarta ,".\app\components\Common::dateToString($value->leave_date),0,0,'L',false,'',0,10,'T','M');
        
        $x=$x;$y=$y;
        $pdf->setXY($x+130,$y+35);
        $pdf->Cell(30,10,$value->employee_name,0,0,'C',false,'',0,10,'T','M');
        
        $x=$x;$y=$y;
        $pdf->setXY($x+130,$y+40);
        $pdf->Cell(30,10,"(".$value->EmployeeEmail.")",0,0,'C',false,'',0,10,'T','M');
        
        /**img-applicant**/
        $image_file = K_PATH_IMAGES.'applicant.png';
        $pdf->Image($image_file,$x+130,$y+15,40,'','PNG', '', 'T', true, 300,'', false, false, 0, false, false, false);
        
        /*/Informasi Sisa Cuti /**/
        $x=$x;$y=$y+15;
        $pdf->SetLineStyle(array('width'=>0.3,'color'=>array(0,0,0)));
        $pdf->Line($x,$y,125,$y); //top , kiri, atas , lebar , kanan
        $pdf->Line($x,$y,$x,$y+30); //right
        $pdf->Line($x+110,$y,$x+110,$y+30); //left
        $pdf->Line($x,$y+30,125,$y+30); //bottom , kiri, atas , lebar , kanan
        
        $x=$x;$y=$y;
        $pdf->setXY($x,$y);
        $pdf->SetFillColor(227,228,229);
        $pdf->Cell(110,5,"Informasi Sisa Cuti",1,0,'L',true,'',0,5,'T','M');
        
        
        /**
         * total pengajuan cuti sebelum tanggal pengajuan
        **/
        $leave = \app\models\Leaves::sumLastLeave($value->employee_id,$value->sysdate);
        $leave_total = 0;
        if($leave)
        {
            $leave_total+=$leave->total;
        }
        
        $x=$x;$y=$y+5;
        $pdf->MultiCell(80,5,'Total Cuti diambil sebelum Pengajuan ini',1,'',false,1,$x,$y,true,0,false,true,5,'T',false);
        $pdf->MultiCell(10,5,$leave_total,1,'R',false,1,$x+80,$y,true,0,false,true,5,'T',false);
        $pdf->MultiCell(20,5,'Hari kerja',1,'',false,1,$x+90,$y,true,0,false,true,5,'T',false);
        
        /**
         * sisa cuti sebelum tanggal pengajuan
        **/
        $leave_balance = \app\models\LeaveBalance::sumLastLeaveBalance($value->employee_id,$value->sysdate);
        $balance_total = 0;
        if($leave_balance)
        {
            $balance_total+=$leave_balance->total;
        }
        
        $x=$x;$y=$y+5;
        $pdf->MultiCell(80,5,'Sisa Cuti sebelum pengajuan ini',1,'',false,1,$x,$y,true,0,false,true,5,'T',false);
        $pdf->MultiCell(10,5,$balance_total,1,'R',false,1,$x+80,$y,true,0,false,true,5,'T',false);
        $pdf->MultiCell(20,5,'Hari kerja',1,'',false,1,$x+90,$y,true,0,false,true,5,'T',false);
        
        
        /*$x=$x;$y=$y+5;
        $pdf->MultiCell(80,5,'Sisa Cuti',1,'',false,1,$x,$y,true,0,false,true,5,'T',false);
        $pdf->MultiCell(10,5,$value->leave_over,1,'R',false,1,$x+80,$y,true,0,false,true,5,'T',false);
        $pdf->MultiCell(20,5,'Hari kerja',1,'',false,1,$x+90,$y,true,0,false,true,5,'T',false);
        */
        
        $x=$x;$y=$y+5;
        $pdf->MultiCell(80,5,'Pengajuan Cuti sesuai form',1,'',false,1,$x,$y,true,0,false,true,5,'T',false);
        $pdf->MultiCell(10,5,$value->leave_total,1,'R',false,1,$x+80,$y,true,0,false,true,5,'T',false);
        $pdf->MultiCell(20,5,'Hari kerja',1,'',false,1,$x+90,$y,true,0,false,true,5,'T',false);
        
        /**
         * sisa cuti leave balance - total cuti
        **/
        $leave_over = $balance_total - $value->leave_total;
        
        $x=$x;$y=$y+5;
        $pdf->MultiCell(80,5,'Sisa Cuti apabila form ini disetujui',1,'',false,1,$x,$y,true,0,false,true,5,'T',false);
        $pdf->MultiCell(10,5,($leave_over),1,'R',false,1,$x+80,$y,true,0,false,true,5,'T',false);
        $pdf->MultiCell(20,5,'Hari kerja',1,'',false,1,$x+90,$y,true,0,false,true,5,'T',false);
        
        $x=$x;$y=$y+15;
        $pdf->SetXY($x+10,$y);
        $pdf->Cell(32,10,"Atasan Langsung",0,0,'C',false,'',0,10,'T','M');
        
        if($value->leave_app_user1){
            $user_name   = $value->user1_name;
            $user_status = $value->leave_app_user1_status;
            $user_email  = $value->user1_email;
            $user_date   = \app\components\Common::dateToString($value->leave_app_user1_date);
        }
        elseif($value->leave_app_user2){
            $user_name =  $value->user2_name;
            $user_status = $value->leave_app_user2_status;
            $user_email  = $value->user2_email;
            $user_date   = \app\components\Common::dateToString($value->leave_app_user2_date);
        }
        else{
            $user_name =  '';
            $user_email = '';
            $user_status = 0;
            $user_date   = '';
        }
        
        
        //approval atasan langsung
        $pdf->SetXY($x+10,$y+25);
        $pdf->Cell(32,10,$user_name,0,0,'C',false,'',0,10,'T','M');
        
        if(($user_status==1)){
            $image_file = K_PATH_IMAGES.'recommended.png';
            $pdf->Image($image_file,$x+10,$y+6,40,'','PNG', '', 'T', true, 300,'', false, false, 0, false, false, false);
        }
        elseif(($user_status==3)){
            $image_file = K_PATH_IMAGES.'recommendedno.png';
            $pdf->Image($image_file,$x+10,$y+6,40,'','PNG', '', 'T', true, 300,'', false, false, 0, false, false, false);
        }
        
        $pdf->SetXY($x+10,$y+30);
        $pdf->Cell(30,10,"(".$user_email.")",0,0,'L',false,'',0,10,'T','M');
        
        $pdf->SetXY($x+10,$y+35);
        $pdf->Cell(32,10,"Tgl.".$user_date,0,0,'L',false,'',0,10,'T','M');
        
        //atasan 2 tingkat , apabila ada user 1 & user 2
        if(($value->leave_app_user2) && ($value->leave_app_user1) ){
            $user_name =  $value->user2_name;
            $user_email  = $value->user2_email;
            $user_status = $value->leave_app_user2_status;
            $user_date   = \app\components\Common::dateToString($value->leave_app_user2_date);
        }
        else {
            $user_name =  "";
            $user_email  = "";
            $user_status = 0;
            $user_date   = "";
        }
        
        $pdf->SetXY($x+130,$y);
        $pdf->Cell(32,10,"Atasan 2 Tingkat",0,0,'C',false,'',0,10,'T','M');
        
        $pdf->SetXY($x+130,$y+25);
        $pdf->Cell(32,10,$user_name,0,0,'C',false,'',0,10,'T','M');
        
        $pdf->SetXY($x+130,$y+30);
        $pdf->Cell(30,10,"(".$user_email.")",0,0,'L',false,'',0,10,'T','M');
        
        $pdf->SetXY($x+130,$y+35);
        $pdf->Cell(32,10,"Tgl. ".$user_date,0,0,'L',false,'',0,10,'T','M');
            
        /**img user 2**/
        if(($user_status==1)  ){
                $image_file = K_PATH_IMAGES.'recommended.png';
                $pdf->Image($image_file,$x+130,$y+6,40,'','PNG', '', 'T', true, 300,'', false, false, 0, false, false, false);
        }
        elseif(($user_status==3)  ){
                $image_file = K_PATH_IMAGES.'recommendedno.png';
                $pdf->Image($image_file,$x+130,$y+6,40,'','PNG', '', 'T', true, 300,'', false, false, 0, false, false, false);
        }
            
        
        $y=$y+45;
        $pdf->SetXY($x+10,$y);
        $pdf->Cell(32,10,"HR-Head",0,0,'C',false,'',0,10,'T','M');
        $pdf->SetXY($x+10,$y+5);
        $pdf->Cell(32,10,"(Atas adm & syarat cuti)",0,0,'C',false,'',0,10,'T','M');
        $pdf->SetXY($x+10,$y+15);
        $pdf->Cell(32,10,"Tgl. ".\app\components\Common::dateToString($value->leave_app_hrd_date),0,0,'C',false,'',0,10,'T','M');
        
        $pdf->SetXY($x+52,$y);
        $pdf->Cell(32,10,"Setuju/Tidak Setuju *",0,0,'C',false,'',0,10,'T','M');
        
        $pdf->SetXY($x+52,$y+30);
        $pdf->Cell(32,10,$value->hrd_name,0,0,'C',false,'',0,10,'T','M');
        
        $pdf->SetXY($x+52,$y+35);
        $pdf->Cell(32,10,"(".$value->hrd_email.")",0,0,'C',false,'',0,10,'T','M');
        
        
        /**img hrd**/
        if($value->leave_app_hrd_status==1){
            $image_file = K_PATH_IMAGES.'recommended.png';
            $pdf->Image($image_file,$x+48,$y+10,40,'','PNG', '', 'T', true, 300,'', false, false, 0, false, false, false);
        }
        elseif($value->leave_app_hrd_status==3){
            $image_file = K_PATH_IMAGES.'recommendedno.png';
            $pdf->Image($image_file,$x+48,$y+10,40,'','PNG', '', 'T', true, 300,'', false, false, 0, false, false, false);
        }
        
        $pdf->SetXY($x+112,$y);
        $pdf->Cell(32,10,"KEPUTUSAN atas Permohonan Cuti",0,0,'L',false,'',0,10,'T','M');
        $pdf->SetXY($x+112,$y+5);
        $pdf->Cell(32,10,"(Disetujui/Ditolak/Ditunda *)",0,0,'L',false,'',0,10,'T','M');
        $pdf->SetXY($x+112,$y+10);
        $pdf->Cell(32,10,"Tgl. ".date('d/m/Y'),0,0,'L',false,'',0,10,'T','M');
        
        $pdf->SetXY($x+112,$y+35);
        $pdf->Cell(32,10,$value->pic_name,0,0,'C',false,'',0,10,'T','M');
        
        /**img pic**/
        if($value->leave_app_pic_status==1){
            $image_file = K_PATH_IMAGES.'app.png';
            $pdf->Image($image_file,$x+112,$y+10,40,'','PNG', '', 'T', true, 300,'', false, false, 0, false, false, false);
        }
        
        $x=$x;$y=$y+40;
        $pdf->setXY($x,$y);
        $pdf->SetFont('Helvetica','BI',8,'','false');
        $pdf->Cell(180,10,"Komentar :",0,0,'L',false,'',0,10,'T','M');
        
        $x=$x;$y=$y;
        $pdf->setXY($x+115,$y);
        $pdf->SetFont('Helvetica','BI',8,'','false');
        $pdf->Cell(180,10,"PIC / FMH / DIR *)",0,0,'L',false,'',0,10,'T','M');
        
        $x=$x;$y=$y+5;
        $pdf->setXY($x,$y);
        $pdf->Cell(180,10,"Jika ada pertimbangan/informasi lainnya dari permohonan cuti diatas,harap memberikan catatan dibawah ini",0,0,'L',false,'',0,10,'T','M');
        
        $desc='';
        if($value->leave_app_user1)
            $desc .=  $value->user1_name.' : '.$value->leave_app_user1_note."\n";
        
        if($value->leave_app_user2)
            $desc .=  $value->user2_name.' : '.$value->leave_app_user2_note."\n";
        
        if($value->leave_app_hrd)
            $desc .=  $value->hrd_name.' : '.$value->leave_app_hrd_note."\n";
        
        if($value->leave_app_pic)
            $desc .=  $value->pic_name.' : '.$value->leave_app_pic_note."\n";
            
        //note 
        $pdf->SetFont('Helvetica','B',6,'','false');
        $x=$x;$y=$y+10;
        $pdf->MultiCell(180,15,$desc,1,'',false,1,$x,$y,true,0,false,true,20,'T',false);
        
        $y=$y+15;
        $pdf->SetFont('Helvetica','I',6,'','false');
        $pdf->MultiCell(180,5,'*) Coret yang tidak perlu',0,'',false,1,$x,$y,true,0,false,true,25,'T',false);
        
        //set output
        $pdf->Output('Formulir-Cuti','D');
    }
    
    public static function getPdfLeaveUser($employee_id,$params=''){
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->AddPage();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        $employee = \app\models\Employee::findOneEmployee($employee_id);
        
        $model = new \app\models\LeaveBalance();
        $user = $model->getLeaveBalanceByEmployee($employee_id);
        
        $x=10;$y=3;
        $pdf->SetXY($x,$y);
        $pdf->SetFont('Helvetica','B',10,'','false');
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(180,10,"BDO TANUBRATA",0,0,'L',false,'',0,10,'T','M');
        
        $x=15;$y=$y+10;
        $pdf->SetXY($x,$y);
        $pdf->Cell(180,5,"KANTOR AKUNTAN PUBLIK",0,0,'C',false,'',0,5,'T','M');
        
        $x=$x;$y=$y+5;
        $pdf->SetXY($x,$y);
        $pdf->Cell(180,5,"TANUBRATA SUTANTO FAHMI & REKAN",0,0,'C',false,'',0,5,'T','M');
        
        $x=$x;$y=$y+10;
        $pdf->SetXY($x,$y);
        $pdf->Cell(180,5,"DAFTAR CUTI KARYAWAN",0,0,'C',false,'',0,5,'T','M');
        
        if(\Yii::$app->session->get('leave_date_from'))
        {
            $titleDate = ''.\Yii::$app->session->get('leave_date_from').' s/d '.\Yii::$app->session->get('leave_date_to');
        }
        else
        {
            $titleDate = '01/01/2014 s/d Sekarang';
        }
        
        $x=$x;$y=$y+5;
        $pdf->SetXY($x,$y);
        $pdf->Cell(180,5,$titleDate,0,0,'C',false,'',0,5,'T','M');
        
        
        $pdf->SetFont('Helvetica','',9,'','false');
        
        $x=$x;$y=$y+10;
        $pdf->SetXY($x,$y);
        $pdf->Cell(32,5,"Tgl.Masuk",0,0,'L',false,'',0,5,'T','M');
        
        $x=$x;$y=$y;
        $pdf->SetXY($x+32,$y);
        $pdf->Cell(5,5,":",0,0,'C',false,'',0,5,'T','M');
        
        $x=$x;$y=$y;
        $pdf->SetXY($x+32+5,$y);
        $pdf->Cell(32,5,$employee->EmployeeHireDate,0,0,'L',false,'',0,5,'T','M');
        
        $x=$x;$y=$y;
        $pdf->SetXY($x+32+5+60,$y);
        $pdf->Cell(32,5,'No.Karyawan',0,0,'L',false,'',0,5,'T','M');
        
        $x=$x;$y=$y;
        $pdf->SetXY($x+32+5+60+32,$y);
        $pdf->Cell(5,5,":",0,0,'C',false,'',0,5,'T','M');
       
        $x=$x;$y=$y;
        $pdf->SetXY($x+32+5+60+32+5,$y);
        $pdf->Cell(32,5,$employee->EmployeeID,0,0,'L',false,'',0,5,'T','M');
        
        $x=$x;$y=$y+5;
        $pdf->SetXY($x,$y);
        $pdf->Cell(32,5,"Status Pegawai",0,0,'L',false,'',0,5,'T','M');
        
        $x=$x;$y=$y;
        $pdf->SetXY($x+32,$y);
        $pdf->Cell(5,5,":",0,0,'C',false,'',0,5,'T','M');
        
        $x=$x;$y=$y;
        $pdf->SetXY($x+32+5,$y);
        $pdf->Cell(32,5, \app\models\Employee::getStringStatus($employee->EmployeeStatus),0,0,'L',false,'',0,5,'T','M');
        
        $x=$x;$y=$y;
        $pdf->SetXY($x+32+5+60,$y);
        $pdf->Cell(32,5,'Nama.Karyawan',0,0,'L',false,'',0,5,'T','M');
        
        $x=$x;$y=$y;
        $pdf->SetXY($x+32+5+60+32,$y);
        $pdf->Cell(5,5,":",0,0,'C',false,'',0,5,'T','M');
       
        $x=$x;$y=$y;
        $pdf->SetXY($x+32+5+60+32+5,$y);
        $pdf->Cell(32,5,$employee->EmployeeName,0,0,'L',false,'',0,5,'T','M');
        
        $x=$x;$y=$y+5;
        $pdf->SetXY($x,$y);
        $pdf->Cell(32,5,"Dicetak Tanggal",0,0,'L',false,'',0,5,'T','M');
        
        $x=$x;$y=$y;
        $pdf->SetXY($x+32,$y);
        $pdf->Cell(5,5,":",0,0,'C',false,'',0,5,'T','M');
        
        $x=$x;$y=$y;
        $pdf->SetXY($x+32+5,$y);
        $pdf->Cell(32,5, date('d M Y H:i:s'),0,0,'L',false,'',0,5,'T','M');
        
        
        $x=15;$y=$y+10;
        $pdf->MultiCell(10,10,'No',1,'C',false,1,$x,$y,true,0,false,true,10,'M',false);
        
        $x=$x+10;$y=$y;
        $pdf->MultiCell(30,10,'Tanggal',1,'C',false,1,$x,$y,true,0,false,true,10,'M',false);
        
        $x=$x+30;$y=$y;
        $pdf->MultiCell(85,10,'Keterangan',1,'C',false,1,$x,$y,true,0,false,true,10,'M',false);
        
        $x=$x+85;$y=$y;
        $pdf->MultiCell(15,10,'Cuti',1,'C',false,1,$x,$y,true,0,false,true,10,'M',false);
        
        $x=$x+15;$y=$y;
        $pdf->MultiCell(10,10,'Sisa',1,'C',false,1,$x,$y,true,0,false,true,10,'M',false);
        
        $x=$x+10;$y=$y;
        $pdf->MultiCell(30,10,'Sumber',1,'C',false,1,$x,$y,true,0,false,true,10,'M',false);
        
        $x=15;
        $y+=10;
        $no = 1;
        foreach($user as $row){
            $x=$x;$y=$y;
            $pdf->MultiCell(10,5,$no,1,'C',false,1,$x,$y,true,0,false,true,5,'M',false);
            
            $x=$x+10;$y=$y;
            $pdf->MultiCell(30,5,$row['leave_balance_date'],1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
            
            $x=$x+30;$y=$y;
            $pdf->MultiCell(85,5,$row['leave_balance_description'],1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
        
            $x=$x+85;$y=$y;
            $pdf->MultiCell(15,5,$row['leave_balance_total'],1,'R',false,1,$x,$y,true,0,false,true,5,'M',false);
        
            $x=$x+15;$y=$y;  
            $pdf->MultiCell(10,5,$row['balance'],1,'R',false,1,$x,$y,true,0,false,true,5,'M',false);
        
            $x=$x+10;$y=$y;
            $pdf->MultiCell(30,5,$row['source'],1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);   
       
             
            $no++;
            $y+=5;
            $x=15;
        }
        
        
        $pdf->Output('Rekapan-Cuti','D');
    }
    
    public static function getExcelLeaveUser(){
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel,"Excel2007");
        $objPHPExcel->getProperties()->setTitle("Cuti Tahun".date('Y'))
        ->setDescription("Cuti Tahun ".date('Y'));   
        $objPHPExcel->setActiveSheetIndex(0);
        
        $objWorksheet = $objPHPExcel->getActiveSheet();       
        $objWorksheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $objWorksheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $objWorksheet->getPageSetup()->setScale(100);
        
        $border = array( 'borders' => array( 'allborders' => array('style' => \PHPExcel_Style_Border::BORDER_THIN )));
        $fill = array(
                        'type'       => \PHPExcel_Style_Fill::FILL_SOLID,
                        'rotation'   => 0,
                        'startcolor' => array(
                                'rgb' => 'CCCCCC'
                        ),
                        'endcolor'   => array(
                                'argb' => 'CCCCCC'
                        ));     
        /**set up font**/
        $objPHPExcel->getDefaultStyle()->getFont()  ->setName('Trebuchet MS')
                                                    ->setSize(8);

        $row=1;$col=0; //setup row and column
        $objWorksheet->setCellValueByColumnAndRow($col,$row,"REKAPITULASI CUTI");
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+0,$col+9,$row+0);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
        $row++;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,'PERIODE '.date('Y'));
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+0,$col+9,$row+0);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
        $row++;
     
        /**Column Header**/
        
        $row+=2;
        
        /**Colom No**/
        $objWorksheet->setCellValueByColumnAndRow($col,$row,'No');
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getFill()->applyFromArray($fill);                                                        
        $objWorksheet->getColumnDimensionByColumn($col)->setWidth(5);
            
        $objWorksheet->setCellValueByColumnAndRow($col+1,$row,'Nama Karyawan');
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->applyFromArray($border);                                                                
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getColumnDimensionByColumn($col+1)->setWidth(25);
            
        $objWorksheet->setCellValueByColumnAndRow($col+2,$row,'NIK');
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->applyFromArray($border); 
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->getFill()->applyFromArray($fill);                                                               
        $objWorksheet->getColumnDimensionByColumn($col+2)->setWidth(10);
            
        $objWorksheet->setCellValueByColumnAndRow($col+3,$row,'Jabatan');
        $objWorksheet->getStyleByColumnAndRow($col+3,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+3,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+3,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getColumnDimensionByColumn($col+3)->setWidth(20);
			
        $objWorksheet->setCellValueByColumnAndRow($col+4,$row,'Departemen');
        $objWorksheet->getStyleByColumnAndRow($col+4,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+4,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+4,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getColumnDimensionByColumn($col+4)->setWidth(30);
            
        $objWorksheet->setCellValueByColumnAndRow($col+5,$row,'Tgl.Masuk');
        $objWorksheet->getColumnDimensionByColumn($col+5)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+5,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+5,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+5,$row)->getFill()->applyFromArray($fill);
        
        $objWorksheet->setCellValueByColumnAndRow($col+6,$row,'Hak Cuti');
        $objWorksheet->getColumnDimensionByColumn($col+6)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+6,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+6,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+6,$row)->getFill()->applyFromArray($fill);
        
        $objWorksheet->setCellValueByColumnAndRow($col+7,$row,'Rekap Total');
        $objWorksheet->getColumnDimensionByColumn($col+7)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+7,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+7,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+7,$row)->getFill()->applyFromArray($fill);
        
        $objWorksheet->setCellValueByColumnAndRow($col+8,$row,'Diambil');
        $objWorksheet->getColumnDimensionByColumn($col+8)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+8,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+8,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+8,$row)->getFill()->applyFromArray($fill);
        
        $objWorksheet->setCellValueByColumnAndRow($col+9,$row,'Sisa');
        $objWorksheet->getColumnDimensionByColumn($col+9)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+9,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+9,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+9,$row)->getFill()->applyFromArray($fill);
                                                                          
        
        /* Data */
        $col=$col;$row++;$i=1;
    
        $query = \app\models\Employee::find()
		->select(['e.EmployeeFirstName','EmployeeMiddleName','EmployeeLastName','EmployeeID','EmployeeTitle','d.department',"DATE_FORMAT(EmployeeHireDate,'%d/%m/%Y') as EmployeeHireDate",
		"DATE_FORMAT(EmployeeLeaveDate,'%d/%m/%Y') as EmployeeLeaveDate",'EmployeeLeaveTotal','EmployeeLeaveUse'])
        ->from('employee e')
        ->join('inner join','sys_user su','su.employee_id = e.employee_id')
        ->join('left join','department d','d.department_id = e.department_id')
        ->where(['su.user_active' => 1])
        ->orderBy('EmployeeFirstName,EmployeeMiddleName,EmployeeLastName')
        ->all();
        
        $i=1;
        foreach($query as $v){
            $objWorksheet->setCellValueByColumnAndRow($col+0,$row,$i);
            $objWorksheet->getStyleByColumnAndRow($col+0,$row)->applyFromArray($border);
                
            $objWorksheet->setCellValueByColumnAndRow($col+1,$row,$v['EmployeeFirstName'].' '.$v['EmployeeMiddleName'].' '.$v['EmployeeLastName']);
            $objWorksheet->getStyleByColumnAndRow($col+1,$row)->applyFromArray($border);
            
            $objWorksheet->setCellValueByColumnAndRow($col+2,$row,$v['EmployeeID']);
            $objWorksheet->getStyleByColumnAndRow($col+2,$row)->applyFromArray($border);
                
            $objWorksheet->setCellValueByColumnAndRow($col+3,$row,$v['EmployeeTitle']);            
            $objWorksheet->getStyleByColumnAndRow($col+3,$row)->applyFromArray($border);
                
            $objWorksheet->setCellValueByColumnAndRow($col+4,$row,$v['department']);
            $objWorksheet->getStyleByColumnAndRow($col+4,$row)->applyFromArray($border);
                
			$objWorksheet->setCellValueByColumnAndRow($col+5,$row,$v['EmployeeHireDate']);
            $objWorksheet->getStyleByColumnAndRow($col+5,$row)->applyFromArray($border);
            
            $objWorksheet->setCellValueByColumnAndRow($col+6,$row,$v['EmployeeLeaveDate']);
            $objWorksheet->getStyleByColumnAndRow($col+6,$row)->applyFromArray($border);
          
            $objWorksheet->setCellValueByColumnAndRow($col+7,$row,$v['EmployeeLeaveTotal']);
            $objWorksheet->getStyleByColumnAndRow($col+7,$row)->applyFromArray($border);
            
            $objWorksheet->setCellValueByColumnAndRow($col+8,$row,$v['EmployeeLeaveUse']);
            $objWorksheet->getStyleByColumnAndRow($col+8,$row)->applyFromArray($border);
            
	    $objWorksheet->setCellValueByColumnAndRow($col+9,$row,($v['EmployeeLeaveTotal']-$v['EmployeeLeaveUse']));
            $objWorksheet->getStyleByColumnAndRow($col+9,$row)->applyFromArray($border);
            	
            $row++;
            $i++;
        }
        
        $folder = "uploads/";
        $filename = "employee.xlsx";
        $objWriter->save($folder.$filename);
        
        //download data excel
        $data = file_get_contents($folder.$filename); // Read the file's contents
        \app\components\Common::force_download($filename,$data); 
        
    }
    
    
    public static function getExcelLeave()
    {
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel,"Excel2007");
        $objPHPExcel->getProperties()->setTitle("Cuti Tahun".date('Y'))
        ->setDescription("Rekap Cuti");   
        $objPHPExcel->setActiveSheetIndex(0);
        
        $objWorksheet = $objPHPExcel->getActiveSheet();       
        $objWorksheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $objWorksheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $objWorksheet->getPageSetup()->setScale(100);
        
        $border = array( 'borders' => array( 'allborders' => array('style' => \PHPExcel_Style_Border::BORDER_THIN )));
        $fill = array(
                        'type'       => \PHPExcel_Style_Fill::FILL_SOLID,
                        'rotation'   => 0,
                        'startcolor' => array(
                                'rgb' => 'CCCCCC'
                        ),
                        'endcolor'   => array(
                                'argb' => 'CCCCCC'
                        ));     
        /**set up font**/
        $objPHPExcel->getDefaultStyle()->getFont()  ->setName('Trebuchet MS')
                                                    ->setSize(8);

        $row=1;$col=0; //setup row and column
        $objWorksheet->setCellValueByColumnAndRow($col,$row,"REKAPITULASI CUTI");
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+0,$col+10,$row+0);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
        $row++;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,'PER PERIODE');
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+0,$col+10,$row+0);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
        $row++;
     
        /**Column Header**/
        
        $row+=2;
        
        /**Colom No**/
        $objWorksheet->setCellValueByColumnAndRow($col,$row,'No');
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getFill()->applyFromArray($fill);                                                        
        $objWorksheet->getColumnDimensionByColumn($col)->setWidth(5);
            
        $objWorksheet->setCellValueByColumnAndRow($col+1,$row,'Tgl Pengajuan');
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->applyFromArray($border);                                                                
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getColumnDimensionByColumn($col+1)->setWidth(12);
            
        $objWorksheet->setCellValueByColumnAndRow($col+2,$row,'NIK');
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->applyFromArray($border); 
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->getFill()->applyFromArray($fill);                                                               
        $objWorksheet->getColumnDimensionByColumn($col+2)->setWidth(10);
            
        $objWorksheet->setCellValueByColumnAndRow($col+3,$row,'Nama');
        $objWorksheet->getStyleByColumnAndRow($col+3,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+3,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+3,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getColumnDimensionByColumn($col+3)->setWidth(36);
        
         $objWorksheet->setCellValueByColumnAndRow($col+4,$row,'Jabatan');
        $objWorksheet->getStyleByColumnAndRow($col+4,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+4,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+4,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getColumnDimensionByColumn($col+4)->setWidth(21);
			
        $objWorksheet->setCellValueByColumnAndRow($col+5,$row,'Keperluan Cuti');
        $objWorksheet->getStyleByColumnAndRow($col+5,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+5,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+5,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getColumnDimensionByColumn($col+5)->setWidth(34);
        
        $objWorksheet->setCellValueByColumnAndRow($col+6,$row,'Tgl Mulai Cuti');
        $objWorksheet->getColumnDimensionByColumn($col+6)->setWidth(12);
        $objWorksheet->getStyleByColumnAndRow($col+6,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+6,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+6,$row)->getFill()->applyFromArray($fill);
        
        $objWorksheet->setCellValueByColumnAndRow($col+7,$row,'Tgl Akhir Cuti');
        $objWorksheet->getColumnDimensionByColumn($col+7)->setWidth(12);
        $objWorksheet->getStyleByColumnAndRow($col+7,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+7,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+7,$row)->getFill()->applyFromArray($fill);
        
        $objWorksheet->setCellValueByColumnAndRow($col+8,$row,'Tanggal Cuti');
        $objWorksheet->getColumnDimensionByColumn($col+8)->setWidth(25);
        $objWorksheet->getStyleByColumnAndRow($col+8,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                                                                          ->setWrapText(true);
                                                                    
        $objWorksheet->getStyleByColumnAndRow($col+8,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+8,$row)->getFill()->applyFromArray($fill);
        
        $objWorksheet->setCellValueByColumnAndRow($col+9,$row,'Total');
        $objWorksheet->getColumnDimensionByColumn($col+9)->setWidth(10);
        $objWorksheet->getStyleByColumnAndRow($col+9,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+9,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+9,$row)->getFill()->applyFromArray($fill);
        
        
        $objWorksheet->setCellValueByColumnAndRow($col+10,$row,'Status');
        $objWorksheet->getColumnDimensionByColumn($col+10)->setWidth(27);
        $objWorksheet->getStyleByColumnAndRow($col+10,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+10,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+10,$row)->getFill()->applyFromArray($fill);
                                                                          
        
        /* Data */
        $col=$col;
        $row++;
        $i=1;
        
        $models = new \app\models\Leaves(); 
        $query =  $models->getLeaves(Yii::$app->request->queryParams);
        
        $i=1;
        
        foreach($query as $v)
        {
            $objWorksheet->setCellValueByColumnAndRow($col+0,$row,$i);
            $objWorksheet->getStyleByColumnAndRow($col+0,$row)->applyFromArray($border);
                
            $objWorksheet->setCellValueByColumnAndRow($col+1,$row,$v->leave_date);
            $objWorksheet->getStyleByColumnAndRow($col+1,$row)->applyFromArray($border);
            
            $objWorksheet->setCellValueByColumnAndRow($col+2,$row,$v->employeeid);
            $objWorksheet->getStyleByColumnAndRow($col+2,$row)->applyFromArray($border);
                
            $objWorksheet->setCellValueByColumnAndRow($col+3,$row,$v->employee_name);            
            $objWorksheet->getStyleByColumnAndRow($col+3,$row)->applyFromArray($border);
                
            $objWorksheet->setCellValueByColumnAndRow($col+4,$row,$v->employeetitle);
            $objWorksheet->getStyleByColumnAndRow($col+4,$row)->applyFromArray($border);
                
			$objWorksheet->setCellValueByColumnAndRow($col+5,$row,$v->leave_description);
            $objWorksheet->getStyleByColumnAndRow($col+5,$row)->applyFromArray($border);
            
            $objWorksheet->setCellValueByColumnAndRow($col+6,$row,$v->leave_date_from);
            $objWorksheet->getStyleByColumnAndRow($col+6,$row)->applyFromArray($border);
          
            $objWorksheet->setCellValueByColumnAndRow($col+7,$row,$v->leave_date_to);
            $objWorksheet->getStyleByColumnAndRow($col+7,$row)->applyFromArray($border);
            
            $objWorksheet->setCellValueByColumnAndRow($col+8,$row,$v->leave_range);
            $objWorksheet->getStyleByColumnAndRow($col+8,$row)->applyFromArray($border);
            
            $objWorksheet->setCellValueByColumnAndRow($col+9,$row,$v->leave_total);
            $objWorksheet->getStyleByColumnAndRow($col+9,$row)->applyFromArray($border);
            
            $objWorksheet->setCellValueByColumnAndRow($col+10,$row,\app\models\Leaves::getStringStatus($v->leave_status));
            $objWorksheet->getStyleByColumnAndRow($col+10,$row)->applyFromArray($border);
            	
            $row++;
            $i++;
        }
        
        $folder = "uploads/";
        $filename = "Rekap-Cuti.xlsx";
        $objWriter->save($folder.$filename);
        
        //download data excel
        $data = file_get_contents($folder.$filename); // Read the file's contents
        \app\components\Common::force_download($filename,$data); 
        
    }
    
}