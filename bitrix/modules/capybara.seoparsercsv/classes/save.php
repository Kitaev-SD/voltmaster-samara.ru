<?php

class seoparsercsvSave {
    static function updateDescr($name, $descr) {
        $arFilter = Array("IBLOCK_ID" => COption::GetOptionString('capybara.seoparsercsv', 'capybara_parser_IBLOCK'), "NAME" => $name);
        $res = CIBlockSection::GetList(Array(), $arFilter);

        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $ID = $arFields["ID"];

            $bs = new CIBlockSection;
            $arFields2 = Array(
                "UF_SECTADDDESCR" => $descr
            );

            $result = $bs->Update($ID, $arFields2);

        }
    }
}