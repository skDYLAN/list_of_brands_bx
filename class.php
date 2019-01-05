<?php

class CBitrixListOfBrands extends CBitrixComponent{
    public function handlerArParams(){
        $this->arParams['IBLOCK_COUNT'] = (int)$this->arParams['IBLOCK_COUNT'];
        if(!isset($arParams["CACHE_TIME"]))
            $arParams["CACHE_TIME"] = 36000000;
    }
    public function setArResult(){
        $arFilter = ['ACTIVE' => 'Y'];
        $arSelect = ['ID', 'NAME', "PROPERTY_LINK"];
        foreach ($this->arParams["IBLOCK_ID"] as $iblockID) {

            if(!intval($iblockID))
                continue;

            $arFilter["IBLOCK_ID"] = $iblockID;
            $r = CIBlockElement::GetList(["name" => "ASC"], $arFilter, false, false, $arSelect);
            while ($res = $r->Fetch()) {
                $res['LINK'] = !empty($res['PROPERTY_LINK_VALUE']) ? $res['PROPERTY_LINK_VALUE'] : "";
                $this->arResult["ELEMENTS"][$iblockID][] = $res;
            }

            $db_iblock = CIBlock::GetList(array("SORT"=>"ASC"),
                [
                    "IBLOCK_ID" => $this->arParams['IBLOCK_ID'],
                    "ID" => $iblockID
                ])->Fetch();
            $this->arResult["SECTIONS"][$iblockID] = ["ID" => $iblockID, "NAME" => $db_iblock["NAME"]];
        }
        $elementsGroup = [];
        $this->arResult["SYMBOLS"] = [];
        foreach ($this->arResult["ELEMENTS"] as $id => $section) {
            foreach ($section as $element) {
                if (array_search($element["NAME"][0], $this->arResult["SYMBOLS"]) === false) {
                    array_push($this->arResult["SYMBOLS"], $element["NAME"][0]);
                }
                $elementsGroup[$id][$this->arResult["SYMBOLS"][array_search($element["NAME"][0], $this->arResult["SYMBOLS"])]][] = $element;
            }
        }
        asort($this->arResult["SYMBOLS"]);
        $this->arResult["ELEMENTS"] = $elementsGroup;
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