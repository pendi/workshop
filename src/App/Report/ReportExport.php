<?php

namespace App\Report;

use Norm\Norm;
use \Bono\Helper\URL;
use PHPExcel;
use PHPExcel_Settings;
use PHPExcel_Worksheet_PageSetup;
use Bono\App;
use TCPDF;
use App\Report\CustomTCPDF;
use \ROH\Util\Inflector;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use \Norm\Schema\String;
use \Norm\Schema\Reference;
use \App\Schema\SysParamReference;
use \App\Schema\HumanTime;
use App\Library\THT;
use App\Library\Month;
use App\Library\Terbilang;
use PHPExcel_Calculation;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Style_Font;



class ReportExport{

    protected $startrow = 1;
    protected $startcolumn = 'A';
    protected $templatehtml;
    protected $templateexcel;
    protected $template;
    protected $app;
    protected $signature = array();
    protected $filterhead = array();
    protected $style = false;

    public $globalconfig;


    public static function create($app){
        return new ReportExport($app);
    }


    public function __construct($app,$template = false){
        $this->app = $app;
        $this->template = $template;
        $this->globalconfig  = $this->app->config('report');

    }

    public function setFilterHead($filterhead){
            $this->filterhead = $filterhead;
            return $this;
    }


    public function setStyle($style){
        $this->style = $style;
        return $this;
    }

    public function setTemplateExcel($template,$startrow = 1,$startcolumn = 'A'){
        $this->templateexcel = $template;
        $this->startrow = $startrow;
        $this->startcolumn = $startcolumn;
        return $this;

    }

    public function setTemplateHTML($template){
        $this->templatehtml = $template;
        return $this;
    }

    public function usingTemplate($using = false){
        $this->template = $using;
        return $this;
    }

    public function setSignature($signature){
        $this->signature = $signature;

        return $this;

    }


    private function headerReport(&$schema,$config = null){
        $header = array();
        $newschema = array();

        if(!empty($config['columns'])){
            $iterator = $config['columns'];
            foreach ($iterator as $key => $obj){
                if($schema[$key]){
                    $header[] = $schema[$key]['label'];
                    $newschema[$key] = $schema[$key];
                }
            }
            $schema = $newschema;

        }else{
            $header = array_keys($schema);
        }


        return $header;
    }


    private function filterHeaderExcel(&$objexcel){
        $rowindex = $this->startrow;

        $filter = $this->filterhead;

        if(empty($filter)){
            $filter = array();
        }

        foreach ($filter as $key => $value) {
            $objexcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowindex, Inflector::classify($key) .': ' .$value);
            $objexcel->getActiveSheet()->mergeCells("A".$rowindex.":C".$rowindex);
            $objexcel->getActiveSheet()->getStyle('A'.$rowindex)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'ffff00'))));
            $rowindex++;
        }
        if(!empty($filter)){
            $this->startrow = $rowindex+1;
        }

    }


    private function generateExcelObject($data,$schema,$config,$reportname){
        /* Initial PHPExcel */
        $objexcel  = new PHPExcel();

        /* Get Config */
        $defaultConfig = $config['default'];

        /* Get Header */
        $schema = $schema->toArray();
        $header = $this->headerReport($schema,$config[$reportname]);

        /* Load Default Template */
        if($this->template){
            if(!empty($this->templateexcel)){
                $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                $objexcel = $objReader->load($this->templateexcel);
            }
        }

        /* Set Report Title */
        $objexcel->getProperties()
                 ->setTitle("Report")
                 ->setSubject("Report");

        /* Report Date */
        $objexcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'Report Date : '.date('d-m-Y'));


        /************************************* Cleansing Data *************************************/
        $newdata= array();


        foreach ($data as $key => $row) {
            $newrow = array();

            foreach ($schema as $field => $obj) {
                $d='';
                if(!empty($row[$field])){
                    $d= $row[$field];
                }

                // if(($obj instanceof \Norm\Schema\Reference || $obj instanceof \App\Schema\Separator || $obj instanceof \Norm\Schema\DateTime)  && !empty($d)){
                //  $d = $obj->cell($d);
                // }

                if($d instanceof \Norm\Type\DateTime){
                    // $d = $d->__toString();
                    $d = date('Y-m-d H:i:s', strtotime($d));
                }
                $newrow[] = $d;
            }
            $newdata[] = $newrow;
        }
        unset($newrow);
        /************************************* Cleansing Data *************************************/

        $startcolumn = $this->startcolumn;

        /********************* CREATE HEADER *********************/
        $objexcel->getDefaultStyle()->getFont()
                    ->setName('Arial')
                    ->setSize(9);

        $startrow = $objexcel->getActiveSheet()->getHighestRow()+2;
        $objexcel->getActiveSheet()->fromArray($header,'',$startcolumn.$startrow);
        if($this->style){
            $objexcel->getActiveSheet()->getStyle($this->numberToColumnExcel(1,$startcolumn).$startrow.':'.$this->numberToColumnExcel(count($header),$startcolumn).$startrow)->applyFromArray(array('borders' => $defaultConfig['excel']['borders']));
            $objexcel->getActiveSheet()->getStyle($this->numberToColumnExcel(1,$startcolumn).$startrow.':'.$this->numberToColumnExcel(count($header),$startcolumn).$startrow)->applyFromArray(array('fill' => $defaultConfig['excel']['fill']));
            $objexcel->getActiveSheet()->getStyle($this->numberToColumnExcel(1,$startcolumn).$startrow.':'.$this->numberToColumnExcel(count($header),$startcolumn).$startrow)->applyFromArray(array('font' => $defaultConfig['excel']['font']));
            $objexcel->getActiveSheet()->getStyle($this->numberToColumnExcel(1,$startcolumn).$startrow.':'.$this->numberToColumnExcel(count($header),$startcolumn).$startrow)->applyFromArray(
                array(
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    )
                )
            );
        }
        /********************* CREATE HEADER *********************/

        foreach ($newdata as $k => $new) {
            $i = ord($startcolumn) - 65;

            /********************* CREATE DATA ROW *********************/
            $no = 0;
            $col = 0;
            $row = $objexcel->getActiveSheet()->getHighestRow()+1;
            foreach ($new as $num => $assets) {

                $objexcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $assets);

                if($this->style){
                    $objexcel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->applyFromArray(array('borders' => $defaultConfig['excel']['borders']));
                    $objexcel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->applyFromArray(
                        array(
                            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                            )
                        )
                    );
                }
                $col++;

            }
            /********************* CREATE DATA ROW *********************/
        }
        return $objexcel;
    }

    public function reportWorkshop($dataEvents, $dataUsers, $category)
    {
        $objexcel = new PHPExcel();

        // var_dump($category['name']);exit();

        $titleStyle = array(
            'font' => array(
                'name'  => 'Arial',
                'size' => 15,
                'bold' => true
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => 'FF000000')
                )
            ),
            'alignment' => array(
                'horizontal' => 'center'
            ),
        );

        $theadStyle = array(
            'font' => array(
                'name'  => 'Arial',
                'size' => 13
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'CCCCCC')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => 'FF000000')
                )
            ),
            'alignment' => array(
                'horizontal' => 'center'
            )
        );

        $tbodyStyle = array(
            'font' => array(
                'name'  => 'Arial',
                'size' => 12
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => 'FF000000')
                )
            )
        );

        $boldStyle = array(
            'font' => array(
                'bold' => true
            )
        );

        $objexcel->getActiveSheet()->SetCellValue('A3', 'Event');

        $cell = 'B';
        foreach ($dataUsers as $key => $user) {
            $objexcel->getActiveSheet()->SetCellValue($cell.'3', $user['first_name'].' '.$user['last_name']);

            $cell++;
        }

        $cellPrevious = chr(ord($cell)-1);
        $objexcel->getActiveSheet()->mergeCells('A2:'.$cellPrevious.'2');
        $objexcel->getActiveSheet()->getStyle('A2')->applyFromArray($titleStyle);
        $objexcel->getActiveSheet()->SetCellValue('A2', 'Report '.$category['name']);

        $objexcel->getActiveSheet()->getStyle('A3:'.$cellPrevious.'3')->applyFromArray($theadStyle);

        foreach (range('A',$cellPrevious) as $i) {
            $objexcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
        }

        // set data to excel
        $noCell = 4;

        foreach ($dataEvents as $de => $event) {
            $objexcel->getActiveSheet()->getStyle('A'.$noCell.':'.$cellPrevious.$noCell)->applyFromArray($tbodyStyle);
            $objexcel->getActiveSheet()->getStyle('A'.$noCell)->applyFromArray($boldStyle);

            $objexcel->getActiveSheet()->SetCellValue('A'.$noCell, '('.date("d M Y", strtotime($event['date'])).') '.$event['name']);
            
            $cellAtt = 'B';
            foreach ($event['attendance'] as $att => $attendance) {
                $backgrounsStyle = array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => substr($attendance['status_color'], 1))
                    )
                );

                $objexcel->getActiveSheet()->getStyle($cellAtt.$noCell)->applyFromArray($backgrounsStyle);
                if (!empty($attendance['description'])) {
                    $objexcel->getActiveSheet()->SetCellValue($cellAtt.$noCell, $attendance['time'].' ('.$attendance['description'].')');
                } else {
                    $objexcel->getActiveSheet()->SetCellValue($cellAtt.$noCell, $attendance['time']);
                }

                $cellAtt++;
            }

            $noCell++;
        }

        $objWriter = \PHPExcel_IOFactory::createWriter($objexcel, 'Excel2007');

        $dataSave = str_replace(" ", "_", $category['name']);

        $objWriter->save('./data/report/Report_'.$dataSave.'.xlsx');
        header('Location:'.URL::base('data/report/Report_'.$dataSave.'.xlsx'));
        exit();
    }

    private function numberToColumnExcel($column = 0 ,$start = 'A'){
         $endofcol = '';
         $asciichar = ord($start) - 65;
         $length = $column + $asciichar;

         if($length > 26){
            $first = ($column / 26);
            $second = $column % 26;
            $first = $asciichar + $first - 1;
            $second = $asciichar + $second - 1;
            $endofcol = chr($first + 65) . chr($second + 65);
         }else{
            $endofcol = chr($column + $asciichar + 65 - 1);

         }

         return $endofcol;
    }


}


