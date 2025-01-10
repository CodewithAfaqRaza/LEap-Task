<?php
 $events_sql = "SELECT 
 e.event_id, 
 e.event_title, 
 e.event_desc,
 e.event_start_time,
 e.event_end_time,
 e.event_date, 
 s.id as speaker_id, 
 s.sp_name,
 s.role,
 s.company,
 s.profile_image ,
 days.day_id,
 days.day_name,
 categories.cat_id,
 categories.cat_name
FROM `events_cat_days_junc` 
LEFT JOIN event_sp_junc ON event_sp_junc.event_id = events_cat_days_junc.event_id 
LEFT JOIN events e ON event_sp_junc.event_id = e.event_id 

LEFT JOIN speakers s ON event_sp_junc.sp_id = s.id
LEFT JOIN days on events_cat_days_junc.event_day_id = days.day_id
LEFT JOIN categories on events_cat_days_junc.event_cat_id = categories.cat_id;";

$events_stmt = $pdo->prepare($events_sql);
$events_stmt->execute();
$rows = $events_stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize data
$organized_events = [];

foreach ($rows as $row) {
$event_id = $row['event_id'];

// Initialize event if not already present
if (!isset($organized_events[$event_id])) {
$organized_events[$event_id] = [
'event_id' => $row['event_id'],
'event_title' => $row['event_title'],
'event_desc' => $row['event_desc'],
'event_start_time' => $row['event_start_time'],
'event_end_time' => $row['event_end_time'],
'event_date' => $row['event_date'],
'event_day' => $row['day_name'],
'event_day_id' => $row['day_id'],
'event_cat_id' => $row['cat_id'],
'event_cat' => $row['cat_name'],
'speakers' => []
];
}

// Add speaker information to the event
if (!empty($row['speaker_id'])) {
$organized_events[$event_id]['speakers'][] = [
'speaker_id' => $row['speaker_id'],
'sp_name' => $row['sp_name'],
'role' => $row['role'],
'company' => $row['company'],
'profile_image' => $row['profile_image']

];
}
}
?>



<?php foreach($organized_events as $event) { ?>
<div class="border-b-[1px] border-gray-500">

    <!-- Active tab label -->

    <div class="bg-purple-600  mt-4 text-white font-semibold py-2 px-4  w-fit">
        <?= ucwords($event['event_day']) ?>
    </div>
</div>

<!-- Event Details Card -->
<div class=" mt-6  bg-gray-800">
    <div class="px-4 pt-10 py-1">
        <div class="flex flex-wrap items-center gap-4 justify-start">
            <span class="bg-gray-700 text-white py-1 px-3  text-sm">14:44</span>
            <div class="flex space-x-1">
                <?php // foreach($all_cat as $cat) { ?>
                <a href="index.php?cat_id=<?=$event['event_cat_id']?>" id="event"
                    class="px-3 py-1.5 bg-gray-700  text-white   text-sm font-semibold">
                    <?= ucfirst($event['event_cat']) ?>
                </a>
                <?php // } ?>
            </div>
        </div>
    </div>
    <div class="all_devs border-[1px] border-[#c0ff00]">
        <div class="border-b-[1px] border-gray-500 p-8">


            <h2 class="text-2xl font-bold mt-4">
                <a class="hover:text-[#c0ff00]" href="#"><?=  $event['event_title'] ?></a>
            </h2>


            <!-- Metadata row -->
            <div class="flex items-center space-x-6 mt-4 text-sm">
                <div class="flex items-center">
                    <i class="opacity-30  fa fa-map-marker-alt mr-2"></i>
                    <span><?= $event['event_cat'] ?></span>
                </div>
                <div class="flex items-center">
                    <i class="opacity-30 fa fa-calendar-alt mr-2"></i>
                    <span><?= $event['event_date'] ?></span>
                </div>
                <div class="flex items-center">
                    <i class=" opacity-30 fa fa-clock mr-2"></i>
                    <span><?= $event['event_start_time'] ?> To <?= $event['event_end_time'] ?></span>
                </div>
            </div>
            <p class="text-gray-400 mt-2">
                <?php echo $event['event_desc'] ?>
            </p>
        </div>



        <!-- Speaker Cards -->
        <div class="grid grid-cols-1  p-6  sm:grid-cols-2 lg:grid-cols-3 gap-6 ">
            <!-- Speaker Card -->
            <?php foreach($event['speakers'] as $speaker) { ?>
            <div class="flex items-center border-2 border-[#c0ff00]  bg-gray-800 p-4">
                <div class="w-16 h-16 flex items-center justify-center bg-gray-600 rounded- mr-4">
                    <img src="<?= $speaker['profile_image'] ?>" alt="">
                </div>
                <div>
                    <h3 class="text-lg font-bold"><a class="hover:text-[#c0ff00]"
                            href="#"><?= $speaker['sp_name'] ?></a></h3>
                    <p class="text-sm text-gray-300"><?php echo $speaker['role'] ?> <br>

                        <span class="font- 
                               semibold"><?php echo $speaker['company'] ?></span>
                    </p>
                </div>
            </div>
            <?php } ?>

        </div>

    </div>

</div>
<div class="border-b-[1px] border-gray-500">

    <!-- Active tab label -->

    <div class="bg-gray-600  mt-4 text-white font-semibold py-2 px-4  w-fit">
        End OF <?= ucwords($event['event_day']) ?>
    </div>
</div>
<?php } ?>