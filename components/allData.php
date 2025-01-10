<?php
$organized_events = [];

foreach ($value['cats'] as $key => $cat) { 
    // print_r($cat);
    if ($key === 0) {
        $day_id = $value['day_id'];
        $cat_id = $cat['cat_id'];

        $events_sql = "SELECT
            e.event_id,
            e.event_title,
            e.event_desc,
            e.event_start_time,
            e.event_end_time,
            e.event_date,
            s.id AS speaker_id,
            s.sp_name,
            s.role,
            s.company,
            s.profile_image,
            days.day_id,
            days.day_name,
            categories.cat_id,
            categories.cat_name
        FROM `events_cat_days_junc`
        LEFT JOIN event_sp_junc ON event_sp_junc.event_id = events_cat_days_junc.event_id
        LEFT JOIN events e ON e.event_id = event_sp_junc.event_id
        LEFT JOIN speakers s ON s.id = event_sp_junc.sp_id
        LEFT JOIN days ON days.day_id = events_cat_days_junc.event_day_id
        LEFT JOIN categories ON categories.cat_id = events_cat_days_junc.event_cat_id
        WHERE events_cat_days_junc.event_day_id = :day_id AND events_cat_days_junc.event_cat_id = :cat_id";

        $statement = $pdo->prepare($events_sql);
        $params = [':day_id' => $day_id, ':cat_id' => $cat_id];
        $statement->execute($params);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $event_id = $row['event_id'];
            if (!isset($organized_events[$event_id])) {
                $organized_events[$event_id] = [
                    'event_id' => $row['event_id'],
                    'event_title' => $row['event_title'],
                    'event_desc' => $row['event_desc'],
                    'event_start_time' => $row['event_start_time'],
                    'event_end_time' => $row['event_end_time'],
                    'event_date' => $row['event_date'],
                    'day_id' => $row['day_id'],
                    'day_name' => $row['day_name'],
                    // 'cat'=>[],
                    'cat_id' => $row['cat_id'],
                    'cat_name' => $row['cat_name'],
                    'speakers' => []
                ];
                
            }
          
          
            $organized_events[$event_id]['speakers'][] = [
                'speaker_id' => $row['speaker_id'],
                'sp_name' => $row['sp_name'],
                'role' => $row['role'],
                'company' => $row['company'],
                'profile_image' => $row['profile_image']
            ];
          
        }
    }
}
?>

<?php 
// print "<pre>";
// print_r($organized_events);
// print "</pre>";
    foreach ($organized_events as $event) { ?>
<div class="allEvents">
    <div class="bg-gray-900 border border-lime-400 rounded-md shadow-lg">
        <div class="p-6 border-b border-lime-400">
            <h2 class="text-2xl font-bold mb-2">
                <a class="hover:text-lime-400" href="#"><?= htmlspecialchars($event['event_title']) ?></a>
            </h2>
            <div class="flex items-center text-sm text-gray-400 space-x-6">
                <div class="flex items-center">
                    <i class="fas fa-tag opacity-70 mr-2"></i>
                    <span><?= htmlspecialchars($event['cat_name']) ?></span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt opacity-70 mr-2"></i>
                    <span><?= htmlspecialchars($event['event_date']) ?></span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-clock opacity-70 mr-2"></i>
                    <span><?= htmlspecialchars($event['event_start_time']) ?> -
                        <?= htmlspecialchars($event['event_end_time']) ?></span>
                </div>
            </div>
            <p class="mt-4 text-gray-300"><?= nl2br(htmlspecialchars($event['event_desc'])) ?></p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
            <?php foreach ($event['speakers'] as $speaker) { ?>
            <div class="flex items-center border-2 border-lime-400 bg-gray-800 p-4 ">
                <div class="w-16 h-16 overflow-hidden mr-4">
                    <img class="w-full h-full object-cover" src="<?= htmlspecialchars($speaker['profile_image']) ?>"
                        alt="Speaker Image">
                </div>
                <div>
                    <h3 class="text-lg font-bold"><a class="hover:text-lime-400"
                            href="#"><?= htmlspecialchars($speaker['sp_name']) ?></a></h3>
                    <p class="text-sm text-gray-400">
                        <?= htmlspecialchars($speaker['role']) ?><br>
                        <span class="font-semibold"><?= htmlspecialchars($speaker['company']) ?></span>
                    </p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="mt-4 border-t border-gray-700">
        <div class="bg-gray-800 text-white font-semibold py-2 px-4">
            End of <?= htmlspecialchars(ucwords($event['day_name'])) ?>
        </div>
    </div>
</div>
<?php } ?>