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
                <th>женщинам</th>
                <th>мужчинам</th>
                <th>детям</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
