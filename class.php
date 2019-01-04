<?php

class CBitrixListOfBrands extends CBitrixComponent{
    public function handlerArParams(){
        $this->arParams['IBLOCK_ID'] = (int)$this->arParams['IBLOCK_ID'];
        $this->arParams['IBLOCK_COUNT'] = (int)$this->arParams['IBLOCK_COUNT'];
        if(!isset($arParams["CACHE_TIME"]))
            $arParams["CACHE_TIME"] = 36000000;
    }
    public function setArResult(){
        $arFilter = ['IBLOCK_ID' => $this->arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'];
        $arSelect = ['ID', 'IBLOCK_ID', 'NAME', "PROPERTY_LINK"];

        foreach ($this->arParams["SECTION_ID"] as $sectionID) {
            if(!intval($sectionID))
                continue;

            $arFilter["SECTION_ID"] = $sectionID;
            $r = CIBlockElement::GetList(["sort" => "ASC"], $arFilter, false, false, $arSelect);
            while ($res = $r->Fetch()) {
                $res['LINK'] = !empty($res['PROPERTY_LINK_VALUE']) ? $res['PROPERTY_LINK_VALUE'] : "";
                $this->arResult["ELEMENTS"][$sectionID][] = $res;
            }

            $db_iblock = CIBlockSection::GetList(array("SORT"=>"ASC"),["IBLOCK_ID" => $this->arParams['IBLOCK_ID']], false)->Fetch();
            $this->arResult["SECTIONS"][$sectionID] = ["ID" => $sectionID, "NAME" => $db_iblock["NAME"]];
        }
        $this->arResult["SYMBOLS"] = [];
        foreach ($this->arResult["ELEMENTS"] as $section) {
            foreach ($section as $element) {
                if (array_search($element["NAME"][0], $this->arResult["SYMBOLS"]) === false) {
                    array_push($this->arResult["SYMBOLS"], $element["NAME"][0]);
                }
            }
        }
        asort($this->arResult["SYMBOLS"]);
    }

    function executeComponent() {
        try {
            $this->handlerArParams();
            if(!Bitrix\Main\Loader::includeModule('iblock'))
                return;
            if($this->StartResultCache()) {
                $this->setArResult();
                $this->includeComponentTemplate();
            }
        } catch (Exception $exc) {
            if ($this->set404){
                @define("ERROR_404","Y");
            } elseif($this->showError) {
                $this->__showError($exc->getMessage());
            }
            $this->AbortResultCache();
        }
    }
}

?>