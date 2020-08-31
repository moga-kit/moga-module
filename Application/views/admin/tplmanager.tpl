hello
<table width="100%">
    <tr>
        <td>
            <ul>
                [{foreach from=$oView->getTemplates() key="dir" item="entries"}]
                    <li>[{$dir}]</li>
                [{/foreach}]
            </ul>


        </td>
        <td>
            <pre>[{$oView->getTemplateOptions("layout/header/*")|@var_dump}]</pre>
        </td>
    </tr>
</table>