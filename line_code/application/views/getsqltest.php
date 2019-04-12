<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>讀取資料庫範例</title>
</head>
<body>
    <table style="text-align: left; width: 100px;" border="1"
        cellpadding="2" cellspacing="2">
        <tbody>
            <tr>
                <td style="vertical-align: top;">帳號</td>
                <td style="vertical-align: top;">密碼</td>
            </tr>
            <?php
            foreach ( $data as $l1 ) {
                echo "<tr>";
                foreach ( $l1 as $l2 ) {
                    echo "<td>" . $l2 . "</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>