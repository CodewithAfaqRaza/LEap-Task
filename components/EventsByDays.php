<?php  
include "../include/database.php";  

if (isset($_GET['day_id'])) {  
    $days = [];  
    $day_id = $_GET['day_id'];  
    $sql = "SELECT * FROM days WHERE day_id = :day_id";  
    $selectDataByDay = $pdo->prepare($sql);  
    $selectDataByDay->execute([':day_id' => $day_id]);  
    $days = $selectDataByDay->fetch();  

    $dataCatByDay = [];  
    $catDataByDay = "SELECT categories.cat_id, categories.cat_name  
        FROM `events_cat_days_junc`  
        LEFT JOIN categories ON categories.cat_id = events_cat_days_junc.event_cat_id  
        WHERE events_cat_days_junc.event_day_id = :day_id";  
    $catDataByDayStmt = $pdo->prepare($catDataByDay);  
    $catDataByDayStmt->execute([':day_id' => $day_id]);  
    $dataCatByDay = $catDataByDayStmt->fetchAll(PDO::FETCH_ASSOC);  

    $organized_events = [];  
    $events_sql = "SELECT  
        e.event_id, e.event_title, e.event_desc, e.event_start_time, e.event_end_time,  
        e.event_date, s.id AS speaker_id, s.sp_name, s.role, s.company, s.profile_image,  
        days.day_id, days.day_name, categories.cat_id, categories.cat_name  
        FROM `events_cat_days_junc`  
        LEFT JOIN event_sp_junc ON event_sp_junc.event_id = events_cat_days_junc.event_id  
        LEFT JOIN events e ON e.event_id = event_sp_junc.event_id  
        LEFT JOIN speakers s ON s.id = event_sp_junc.sp_id  
        LEFT JOIN days ON days.day_id = events_cat_days_junc.event_day_id  
        LEFT JOIN categories ON categories.cat_id = events_cat_days_junc.event_cat_id  
        WHERE events_cat_days_junc.event_day_id = :day_id";  

    $statement = $pdo->prepare($events_sql);  
    $statement->execute([':day_id' => $day_id]);  
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
                'categories' => $row['cat_name'],
                'speakers' => []  
            ];  
        }  

        // Add speaker information  
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

    $allData = [  
        'days' => $days,  
        'categories' => $dataCatByDay,  
        'events' => array_values($organized_events)  
    ];  

    echo json_encode($allData);  
}  