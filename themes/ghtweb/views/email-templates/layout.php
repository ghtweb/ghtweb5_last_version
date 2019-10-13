<meta charset="UTF-8">
<table align="center" bgcolor="#3c241b" cellpading="0" cellspacing="0" border="0" width="800">
    <tr>
        <td align="center"></td>
    </tr>
    <tr>
        <td height="40"></td>
    </tr>
    <tr>
        <td>
            <table align="center" cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="20"></td>
                    <td align="center">
                        <font color="#ffffff"><?php echo $content ?></font>
                    </td>
                    <td width="20"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height="80"></td>
    </tr>
    <tr>
        <td bgcolor="#2f1c15" height="20"></td>
    </tr>
    <tr>
        <td align="center" bgcolor="#2f1c15">
            <font color="#ead255" face="Arial" style="font-size: 11px; line-height : 14px;">
                С уважением!
                <br/>
                Администрация
                сервера <?php echo CHtml::link($_SERVER['HTTP_HOST'], $this->createAbsoluteUrl('/index/default/index')) ?>
            </font>
        </td>
    </tr>
    <tr>
        <td bgcolor="#2f1c15" height="20"></td>
    </tr>
</table>