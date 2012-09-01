<?
if (!isset($_GET['torrentid']) || !is_number($_GET['torrentid'])) {
    error(404);
}
$TorrentID = $_GET['torrentid'];

if (!empty($_GET['page']) && is_number($_GET['page'])) {
    $Page = $_GET['page'];
    $Limit = (string) (($Page - 1) * 100) . ', 100';
} else {
    $Page = 1;
    $Limit = 100;
}

$Result = $DB->query("SELECT SQL_CALC_FOUND_ROWS
	xu.uid,
	t.Size,
	um.Username,
	xu.active,
	xu.connectable,
	xu.uploaded,
	xu.remaining,
	xu.useragent,
      IF(xu.remaining=0,1,0) AS IsSeeder,
	xu.timespent
	FROM xbt_files_users AS xu
	LEFT JOIN users_main AS um ON um.ID=xu.uid
	JOIN torrents AS t ON t.ID=xu.fid
	WHERE xu.fid='$TorrentID'
	AND um.Visible='1'
	ORDER BY IsSeeder DESC, xu.uploaded DESC
	LIMIT $Limit");
$DB->query("SELECT FOUND_ROWS()");
list($NumResults) = $DB->next_record();
$DB->set_query_id($Result);
?>

<? if ($NumResults > 100) { ?>
    <div class="linkbox"><?= js_pages('show_peers', $_GET['torrentid'], $NumResults, $Page) ?></div>
    <? } ?>
<table> 
    <?
    if ($NumResults==0){
            ?> 
            <tr class="smallhead">
                <td colspan="8">There are no peers for this torrent</td>
            </tr> 
            <?
    }
    $LastIsSeeder = -1;
    while (list($PeerUserID, $Size, $Username, $Active, $Connectable, $Uploaded, $Remaining, $UserAgent, $IsSeeder, $Timespent) = $DB->next_record()) {

        if ($IsSeeder != $LastIsSeeder) {
            ?>

            <tr class="smallhead">
                <td colspan="8"><?= ($IsSeeder ? 'Seeders' : 'Leechers') ?></td>
            </tr>
            <tr class="rowa" style="font-weight: bold;">
                <td>User</td>
                <td>Active</td>

                <td>Connectable</td>

                <td>Up</td>
                <td>Down</td>
                <td>%</td>
                <td>Ratio</td>
                <td>Client</td>
            </tr>
            <?
            $LastIsSeeder = $IsSeeder;
        }
        ?>
        <tr>
            <td><?= format_username($PeerUserID, $Username) ?></td>
            <td><?= ($Active) ? '<span style="color:green">Yes</span>' : '<span style="color:red">No</span>' ?></td>

            <td><?= ($Connectable) ? '<span style="color:green">Yes</span>' : '<span style="color:red">No</span>' ?></td>

            <td><?= get_size($Uploaded) ?></td>
            <td><?= get_size($Size - $Remaining, 2) ?></td>
            <td><?= number_format(($Size - $Remaining) / $Size * 100, 2) ?></td>
            <td><?= number_format($Uploaded / ($Size - $Remaining), 3) ?></td>
            <td><?= display_str($UserAgent) ?></td>
        </tr>
        <?
    }
    ?>
</table>
<? if ($NumResults > 100) { ?>
    <div class="linkbox"><?= js_pages('show_peers', $_GET['torrentid'], $NumResults, $Page) ?></div>
<? } ?>
