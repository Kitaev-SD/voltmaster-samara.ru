<?php

include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

abstract class capybaIblockDelete
{
    public static $iblock_id;
    public static $DB;

    public static function constructor($iblock_id, $DB)
    {
        CModule::IncludeModule("iblock");
        self::$iblock_id = $iblock_id;
        self::$DB = $DB;
    }

    public static function counter()
    {
        $rs = CIBlockElement::GetList (
            Array("ID" => "ASC"),
            Array("IBLOCK_ID" => self::$iblock_id),
            Array(),
            false,
            Array("ID")
        );
        echo "Осталось $rs элементов"."<br>";
    }

    public static function delElementOnSteps()
    {
        self::counter();
        $rs = CIBlockElement::GetList (
            Array("ID" => "ASC"),
            Array("IBLOCK_ID" => self::$iblock_id),
            false,
            Array ("nTopCount" => 150),
            Array("ID")
        );

        $check = false;
        while($ar_fields = $rs->GetNext())
        {
            $check = true;
            self::$DB->StartTransaction();
			if(!CIBlockElement::Delete($ar_fields["ID"]))
            {
                $strWarning .= 'Error!';
                self::$DB->Rollback();
			}
            else {
                self::$DB->Commit();
				echo $ar_fields["ID"]." Удален!<br>";
			}
		}
        if($check) {
            self::redirectToNextStep();
        } else {
            return true;
        }
    }

    public static function redirectToNextStep()
    {
        header("refresh: 0; URL=".$_SERVER['REQUEST_URI']."");
        die();
    }

    public static function delIblock($iblock_id, $DB)
    {
        self::constructor($iblock_id, $DB);
        self::delElementOnSteps();
        self::$DB->StartTransaction();
		if(!CIBlock::Delete(self::$iblock_id))
        {
            $strWarning .= GetMessage("IBLOCK_DELETE_ERROR");
            self::$DB->Rollback();
		}
        else {
            self::$DB->Commit();
			return "Delete Success Iblock".self::$iblock_id;
		}
	}
}

echo capybaIblockDelete::delIblock(30, $DB);



