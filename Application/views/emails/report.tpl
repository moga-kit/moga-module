<html>
<head>
    <title>MOGA Customization Report</title>
</head>
<body>
<h2>Hello mein Führer!</h2>
<p>Oberst [{$oUser->oxuser__oxfname}] [{$oUser->oxuser__oxlname}] hat soeben die CSS an der Ostfront geändert:</p>
<table width="100%" cellpadding="5" cellspacing="10" border="0">

    <tr>
        <td width="20%">Variable</td>
        <td align="center" width="40%">alt</td>
        <td align="center" width="40%">neu</td>
    </tr>

    <tr><td><h4>Farben</h4></td><td colspan="2"><hr/></td></tr>
    [{foreach from=$aOldScssColors key="index" item="var" }]
        [{assign var="_old" value=$var.value|default:$var.default}]
        [{assign var="_new" value=$aNewScssColors[$index].value|default:$aNewScssColors[$index].default}]
        <tr>
            <td>[{$var.name}]</td>
            <td align="center" style="background:[{$_old}]">[{$_old}]</td>
            <td align="center" style="background:[{$_new}]">[{$_new}]</td>
        </tr>
    [{/foreach}]

    <tr><td><h4>Schriftgrößen</h4></td><td colspan="2"><hr/></td></tr>
    [{foreach from=$aNewScssFontsizes key="index" item="var" }]
        <tr>
            <td>[{$var.name}]</td>
            <td align="center">
                [{if $oOldScssFontsizes}]
                    [{$oOldScssFontsizes[$index].value|default:$oOldScssFontsizes[$index].default}]
                [{else}]
                    [{$var.default}]
                [{/if}]
            </td>
            <td align="center" [{if ($oOldScssFontsizes && $oOldScssFontsizes[$index].value && $oOldScssFontsizes[$index].value != $var.value) || (!$oOldScssFontsizes && $var.value && $var.value != $var.default)}]style="color: green;font-weight: bold;"[{/if}]>
                [{$var.value|default:$var.default}]
            </td>
        </tr>
    [{/foreach}]

</table>

</body>
</html>