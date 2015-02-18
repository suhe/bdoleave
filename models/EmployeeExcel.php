<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\BpjsEmployee;
use app\models\Bpjs;
use yii\phpexcel\PHPExcel;

/**
 * LoginForm is the model behind the login form.
 */
class EmployeeExcel extends Model{
    
    public function getEmployeeAll(){
        $objPHPExcel = new \PHPExcel();
	$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel,"Excel2007");
        //$objPHPExcel = $PHPExcel->create();
        $objPHPExcel->setActiveSheetIndex(0);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        /** Page Setup **/
        $objWorksheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objWorksheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5);
        $objWorksheet->getPageSetup()->setScale(93);
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Trebuchet MS')
                                                  ->setSize(8);
        /** Page Border **/
        $border = array( 'borders' => array( 'allborders' => array('style' => \PHPExcel_Style_Border::BORDER_THIN )));
        $fill = array(
                        'type'       => \PHPExcel_Style_Fill::FILL_SOLID,
                        'rotation'   => 0,
                        'startcolor' => array(
                        'rgb'        => 'CCCCCC'),
                        'endcolor'   => array(
                        'argb'       => 'CCCCCC'));
        $col=0;$row=1;
        //HTTP Header untuk download
        
        $val = Bpjs::findOne(1); 
        
        //$objWorksheet->setCellValueByColumnAndRow($col,$row,'Laporan Absen');
        $x=0;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,Yii::t('app','bpjs type'));
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+$x,$col+$x+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($col+$x+3,$row,$val->bpjs_type);
       
        $row++;
        $x=0;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,Yii::t('app','company'));
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+$x,$col+$x+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($col+$x+3,$row,$val->bpjs_company);
        
        $row++;
        $x=0;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,Yii::t('app','bpjs vc'));
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+$x,$col+$x+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($col+$x+3,$row,"'".$val->bpjs_vc);
        
        $row++;
        $x=0;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,Yii::t('app','bank'));
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+$x,$col+$x+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($col+$x+3,$row,"'".$val->bpjs_bank);
        
        $row++;
        $x=0;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,Yii::t('app','registration date'));
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+$x,$col+$x+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($col+$x+3,$row,$val->bpjs_registration_date);
        
        $row++;
        $x=0;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,Yii::t('app','pks no'));
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+$x,$col+$x+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($col+$x+3,$row,"'".$val->bpjs_pks);
        
        $row++;
        $x=0;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,Yii::t('app','pks code'));
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+$x,$col+$x+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($col+$x+3,$row,"'".$val->bpjs_pks_code);
        
        $row++;
        $x=0;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,Yii::t('app','expired date'));
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+$x,$col+$x+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($col+$x+3,$row,$val->bpjs_expired_date);
        
        $row++;
        $x=0;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,Yii::t('app','dependent code'));
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+$x,$col+$x+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($col+$x+3,$row,"'".$val->bpjs_dependent_code);
        
        $row++;
        $x=0;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,Yii::t('app','kc code'));
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+$x,$col+$x+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($col+$x+3,$row,"'".$val->bpjs_kc_code);
                
        $row++;
        $x=0;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,Yii::t('app','dati2 code'));
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+$x,$col+$x+2,$row);
        $objWorksheet->setCellValueByColumnAndRow($col+$x+3,$row,"'".$val->bpjs_dati2);
                
        $row=$row+2;
        
        $x=0;
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','no'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(5);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $x++;                                                                  
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','no kk'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(25);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $x++;  
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','nik'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(10);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                                     
        
        $x++; 
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','full name'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(25);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                                                          
        
        $x++; 
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','pisat'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                                     

        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','birth place'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                                     
        
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','birth date'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(12);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                                     
        
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','gender'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(12);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                                     

        
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','status'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(10);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                                     
        
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','address'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(35);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                           ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);   
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','address_rt'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(5);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);  
                                                                          
        
        
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','address_rw'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(5);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                                                          
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','address_zip'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(10);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                           ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);  
                                                                          
                                                                          
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','address_kec'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(20);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);  
                                                                          
                                                                          
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','address_kel'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(20);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                           
                                                                          
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','faskes'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(20);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                                                          
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','faskes_dr_gigi'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(12);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                                                          
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','faskes_name_dr_gigi'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(25);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                                  
        
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','mobile'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                                                          
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','email'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(20);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                                                          
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','npp'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(20);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                                                          
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','position'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                                    
        
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','status'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','care_class'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','start_work'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(12);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','salary'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                                                          
                                                                          
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','nationality'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                                                          
                                                                          
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','polis no'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                                  
        
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','assurance name'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(20);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                                                          
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','npwp'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                                                          
                                                                          
        $x++;                                                                    
        $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,Yii::t('app','passport no'));
        $objWorksheet->getColumnDimensionByColumn($col+$x)->setWidth(15);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                                  
        
        $employee = new BpjsEmployee();
        $records = $employee
        ->find()
        ->select(["*","DATE_FORMAT(employee_birth_date,'%d/%m/%Y') as employee_birth_date","DATE_FORMAT(employee_active_date,'%d/%m/%Y') as employee_active_date"])
        ->orderBy('employee_id,employee_relation')
        ->all();
        
        $row++;
        $i=1;
        
        foreach($records as $v){
            $x=0;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$i);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,"'".$v->employee_identity_kk);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_identity_id);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            //$objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_name);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_pisat);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_birth_place);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_birth_date);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_gender);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_personal_status);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_address." ".$v->employee_address_alt);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_address_rt);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_address_rw);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_address_zip);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_address_kec);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_address_kel);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
             
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_faskes);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_faskes_dr);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_faskes_dr_name);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_mobile);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_email);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_npp);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_position);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_status);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_care_class);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_active_date);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_salary);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->getNumberFormat()->setFormatCode('#,##0');
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_nationality);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_assurance_polis_no);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_assurance_name);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_npwp);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $x++;
            $objWorksheet->setCellValueByColumnAndRow($col+$x,$row,$v->employee_passport_no);
            $objWorksheet->getStyleByColumnAndRow($col+$x,$row)->applyFromArray($border);
            
            $row++;
        }
        $folder = "assets/upload/excel/";
        $filename = "employee_bpjs.xlsx";
        //chmod('web/'.$folder,775);
        $objWriter->save($folder.$filename);
        return $folder.$filename;
    }
    
}
