<?php
// Lightweight runtime stubs for PhpSpreadsheet types to satisfy static analyzers
// Only define these when the real package is not installed.
if (!class_exists('\\PhpOffice\\PhpSpreadsheet\\Spreadsheet')) {
    class App_Phpspreadsheet_Spreadsheet
    {
        public function getActiveSheet()
        {
            return new App_Phpspreadsheet_Worksheet();
        }
    }

    class App_Phpspreadsheet_Worksheet
    {
        public function fromArray($data, $nullValue = null, $startCell = 'A1')
        {
            // no-op stub
        }

        public function setCellValue($cell, $value)
        {
            // no-op stub
        }

        public function getStyle($range)
        {
            return new App_Phpspreadsheet_Style();
        }

        public function getColumnDimension($col)
        {
            return new class {
                public function setAutoSize($v) {}
            };
        }
    }

    class App_Phpspreadsheet_Style
    {
        public function getFont()
        {
            return new App_Phpspreadsheet_Font();
        }
    }

    class App_Phpspreadsheet_Font
    {
        public function setBold($v)
        {
            // no-op
        }
    }

    class App_Phpspreadsheet_Writer_Xlsx
    {
        public function __construct($spreadsheet) {}

        public function save($path)
        {
            // no-op stub
        }
    }

    class_alias('App_Phpspreadsheet_Spreadsheet', '\\PhpOffice\\PhpSpreadsheet\\Spreadsheet');
    class_alias('App_Phpspreadsheet_Worksheet', '\\PhpOffice\\PhpSpreadsheet\\Worksheet\\Worksheet');
    class_alias('App_Phpspreadsheet_Style', '\\PhpOffice\\PhpSpreadsheet\\Style\\Style');
    class_alias('App_Phpspreadsheet_Font', '\\PhpOffice\\PhpSpreadsheet\\Style\\Font');
    class_alias('App_Phpspreadsheet_Writer_Xlsx', '\\PhpOffice\\PhpSpreadsheet\\Writer\\Xlsx');
}
