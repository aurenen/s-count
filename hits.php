<?php 
/************************************************************
 *
 *  (c) s-count
 *  https://github.com/aurenen/s-count
 *  
 *  script: s-count
 *  file: hits.php
 *  
 *  Displays, by project id, visit hits and info.
 * 
 ************************************************************/

require_once 'includes/connect.php';
require_once 'includes/functions.php';

$page_title = "Hits";

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
} 

$site_id = is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

$site = getSiteInfo($site_id);

$page_title .= " for " . $site['site_name'];
$offset = (is_numeric($_GET['p']) ? intval($_GET['p']) : 1) - 1;
if ($offset < 0)
    $offset = 0;
$limit = 15;

$hits = getHits($site_id, $offset, $limit);

$page_count = ceil(intval($site['count']) / $limit);
$page_num = $offset + 1;

require_once 'includes/admin_header.inc'; 
?>

        <h2>Dashboard</h2>

        <p>Hits for <a href="<?php echo $site['site_url']; ?>"><?php echo $site['site_name']; ?></a>, total of 
            <?php echo $site['count']; ?> hits.</p>

        <table class="table">
            <thead>
                <tr>
                    <th>Date / Time</th>
                    <th>IP / Browser</th>
                    <th>Referrer / Page</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hits as $h) { //`referrer`, `page`, `ip_address`, `browser`, `time`
                    echo '<tr>' .
                         '<td>' . date('M j, Y', strtotime($h['time'])) . 
                         '<br>' . date('h:i a', strtotime($h['time'])) . '</td>' .
                         '<td>' . $h['ip_address'] . 
                         '<br>' . $h['browser'] . '</td>' .
                         '<td><a href="' . $h['referrer'] . '">' . $h['referrer'] . '</a><br>' .
                         '<a href="' . $h['page'] . '">' . $h['page'] . '</a></td>' .
                         '</tr>' . "\n";
                } ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php 
            if ($page_num != 1)
                echo '<a href="hits.php?id='. $site_id .'&p='. ($page_num - 1).'" class="bt">Prev</a> ';
            for ($i=1; $i <= $page_count; $i++) { 

                echo '<a href="hits.php?id='. $site_id .'&p='. $i .'" class="bt'.
                ($page_num == $i ? ' disabled' : '')
                .'">'. $i .'</a> ';

            } 
            if ($page_num != $page_count)
                echo '<a href="hits.php?id='. $site_id .'&p='. ($page_num + 1) .'" class="bt">Next</a> ';
            ?>
        </div>

<?php 
require_once 'includes/admin_footer.inc'; 
?>