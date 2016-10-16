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

$page_title = "Stats";

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
} 

$site_id = is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
$type = ($_GET['type'] == "browser") ? "browser" : "referrer";

$site = getSiteInfo($site_id);
$page_title .= " for " . $site['site_name'];

if ($type == "browser") {
    $stats = getBrowserStats($site_id);
    $stat_size = count($stats);

    for ($i = 0; $i < $stat_size; $i++) {
        if ($i != ($stat_size - 1)) {
            $browser_name .= '"' . $stats[$i]['browser'] . '", ';
            $browser_count .= $stats[$i]['cnt'] . ', ';
        }
        else {
            $browser_name .= '"' . $stats[$i]['browser'] . '"';
            $browser_count .= $stats[$i]['cnt'];
        }
    }
}
elseif ($type == "referrer") {
    $stats = getReferrerStats($site_id);
}

require_once 'includes/admin_header.inc'; 
?>

        <h2>Stats by <?php echo $type; ?></h2>

        <p>
            Site: <a href="<?php echo $site['site_url']; ?>"><?php echo $site['site_name']; ?></a>
            //
            Total: <a href="hits.php?id=<?php echo $site_id; ?>"><?php echo $site['count']; ?> hits</a>
            //
            <a href="stats.php?type=referrer&id=<?php echo $site_id; ?>">Top referrers</a>
            //
            <a href="stats.php?type=browser&id=<?php echo $site_id; ?>">Browser stats</a>
        </p>

        <?php if ($type == "browser"): ?>
        <canvas id="stat_chart" width="250" height="125"></canvas>
        <?php elseif ($type == "referrer"): ?>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Referer</th>
                    <th>Hits</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats as $s) {
                    echo '<tr>' .
                         '<td><a href="'. $s['referrer'] .'">' . $s['referrer'] . '</a></td>' .
                         '<td>' . $s['cnt'] . '</td>' .
                         '</tr>' . "\n";
                } ?>
            </tbody>
        </table>
        <?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
<script>
    var ctx = document.getElementById("stat_chart").getContext("2d");
    <?php if ($type == "browser"): ?>
    var data = {
        labels: [
            <?php echo $browser_name; ?>
        ],
        datasets: [
            {
                data: [<?php echo $browser_count; ?>],
                backgroundColor: [
                    "#FF6384",
                    "#36A2EB",
                    "#FFCE56"
                ],
                hoverBackgroundColor: [
                    "#FF6384",
                    "#36A2EB",
                    "#FFCE56"
                ]
            }]
    };
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
            title: {
                display: true,
                text: 'Browser breakdown'
            }
        }
    });
    <?php elseif ($type == "referrer"): ?>

    <?php endif; ?>
</script>
<?php 
require_once 'includes/admin_footer.inc'; 
?>