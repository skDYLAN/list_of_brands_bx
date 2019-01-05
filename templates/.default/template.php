<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
?>
<div class="brends__box">
    <div class="brends__box-alphabet">
        <ul>
            <?foreach ($arResult["SYMBOLS"] as $symbol):?>
            <li><a href=""><?=$symbol?></a></li>
            <?endforeach;?>
        </ul>
    </div>
    <div class="brends__box-table">
        <table>
            <thead>
            <tr>
                <th></th>
                <?foreach ($arResult["SECTIONS"] as $item):?>
                <th><?=$item["NAME"]?></th>
                <?endforeach;?>
            </tr>
            </thead>
            <?foreach ($arResult["SYMBOLS"] as $symbol):?>
            <tr>
                <td><?=$symbol?></td>
                <?foreach ($arResult["SECTIONS"] as $section):?>
                <td>
                    <ul>
                        <?foreach ($arResult["ELEMENTS"][$section["ID"]][$symbol] as $item):?>
                        <li><a href="<?=$item["LINK"]?>"><?=$item["NAME"]?></a></li>
                        <?endforeach;?>
                    </ul>
                </td>
                <?endforeach;?>
            </tr>
            <?endforeach;?>
        </table>
    </div>
</div>