<?php

include '../include/database.php';

// select the category from the database

$sql = "SELECT * FROM categories";
$result = $pdo->prepare($sql);
$result->execute();
$all_cat = $result->fetchAll(PDO::FETCH_ASSOC);
// select the Days from the database

$days_sql = "SELECT * FROM days";
$all_days = $pdo->prepare($days_sql);
$all_days->execute();
$days = $all_days->fetchAll(PDO::FETCH_ASSOC);
// print_r($days);
// select the Speakers from the database

$speakers_sql = "SELECT * FROM speakers";
$speakers = $pdo->prepare($speakers_sql);
$speakers->execute();
$speakers = $speakers->fetchAll(PDO::FETCH_ASSOC);
// print_r($speakers);



    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $event_title = htmlspecialchars($_POST['event_title']);
        $event_desc = htmlspecialchars($_POST['event_desc']);
        $event_start_time = $_POST['event_start_time'];
        $event_end_time = $_POST['event_end_time'];
        $event_date = $_POST['event_date'];
        $category_id = $_POST['event_category'];
        $event_day = $_POST['event_day'];
        $speaker_id = $_POST['speakers'];

        // Prepare and execute the query
        $sql = "INSERT INTO events (event_title, event_desc, event_start_time, event_end_time, event_date) 
                VALUES (:event_title, :event_desc, :event_start_time, :event_end_time, :event_date)";
        
        $stmt = $pdo->prepare($sql);
        $params = [
            ':event_title' => $event_title,
            ':event_desc' => $event_desc,
            ':event_start_time' => $event_start_time,
            ':event_end_time' => $event_end_time,
            ':event_date' => $event_date
        ];

        print "<pre>";
        print_r($params);
        print "</pre>";
         if ($stmt->execute($params)) {
            echo "Event added successfully!";
        } else {
            echo "Failed to add the event.";
        }
        $event_id = $pdo->lastInsertId();
            print $event_id;
        $insert_DataToJunc = "INSERT INTO `events_cat_days_junc`(`event_id`, `event_cat_id`, `event_day_id`) VALUES (:event_id,:event_cat_id,:event_day_id)";
        $stmt = $pdo->prepare($insert_DataToJunc);
        $paramsToJunc = [
            ':event_id' => $event_id,
            ':event_cat_id' => $category_id,
            ':event_day_id' => $event_day
        ];

        print "<pre>";
        print_r($paramsToJunc);
        print "</pre>";

        $stmt->execute($paramsToJunc);
          if ($stmt->execute($paramsToJunc)) {
            echo "Event category and day added successfully!";
        } else {
            echo "Failed to add the event.";
        }

            $eventAndSpeakers = 'INSERT INTO `event_sp_junc` (`event_id`, `sp_id`) VALUES (:event_id, :sp_id)';
            $stmt = $pdo->prepare($eventAndSpeakers);
            
            foreach ($_POST['speakers'] as $speaker_id) {
                $paramsEventAndSpeakers = [
                    ':event_id' => $event_id,  // Make sure $event_id is defined and set correctly
                    ':sp_id' => $speaker_id   
                ];
                print "<pre>";
                print_r($paramsEventAndSpeakers);
                print "</pre>";
                if ($stmt->execute($paramsEventAndSpeakers)) {
                    echo "Event Speakers added successfully!";
                } else {
                    echo "Failed to add the event.";
                }
            }
        
    }


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-gray-400">
    <div class="container mx-auto p-8">
        <h2 class="text-white text-2xl mb-4">Add New Event</h2>
        <form action="" method="POST" class="space-y-4">
            <div>
                <label for="title" class="block text-sm">Event Title</label>
                <input type="text" id="title" name="event_title" required
                    class="w-full p-2 bg-gray-800 text-white rounded">
            </div>
            <div>
                <label for="desc" class="block text-sm">Event Description</label>
                <textarea id="desc" name="event_desc" rows="4" required
                    class="w-full p-2 bg-gray-800 text-white rounded"></textarea>
            </div>
            <div>
                <label for="start_time" class="block text-sm">Event Start Time</label>
                <input type="time" id="start_time" name="event_start_time" required
                    class="w-full p-2 bg-gray-800 text-white rounded">
            </div>
            <div>
                <label for="end_time" class="block text-sm">Event End Time</label>
                <input type="time" id="end_time" name="event_end_time" required
                    class="w-full p-2 bg-gray-800 text-white rounded">
            </div>
            <div>
                <label for="date" class="block text-sm">Event Date</label>
                <input type="date" id="date" name="event_date" required
                    class="w-full p-2 bg-gray-800 text-white rounded">
            </div>

            <!-- Category Select Dropdown -->
            <div>
                <label for="category" class="block text-sm">Event Category</label>
                <select id="category" name="event_category" required class="w-full p-2 bg-gray-800 text-white rounded">
                    <option value="" disabled selected>Select Category</option>
                    <?php foreach ($all_cat as $category): ?>
                    <option value="<?= htmlspecialchars($category['cat_id']); ?>">
                        <?= htmlspecialchars($category['cat_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="days" class="block text-sm">Select Day</label>
                <select id="days" name="event_day" required class="w-full p-2 bg-gray-800 text-white rounded">
                    <option value="" disabled selected>Select Day</option>
                    <?php foreach ($days as $day): ?>
                    <option value="<?= htmlspecialchars($day['day_id']); ?>">
                        <?= htmlspecialchars($day['day_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="speakers" class="block text-sm">Select Speakers</label>
                <select id="speakers" name="speakers[]" multiple required
                    class="w-full p-2 bg-gray-800 text-white rounded">
                    <option value="" disabled>Select Speakers</option>
                    <?php foreach ($speakers as $speaker): ?>
                    <option value="<?= htmlspecialchars($speaker['id']); ?>">
                        <?= htmlspecialchars($speaker['sp_name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white py-2 px-4 rounded">Add
                Event</button>
        </form>
    </div>
</body>

</html>