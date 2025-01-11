<?php
include('include/database.php');

// Fetching Days
$days_sql = "SELECT * FROM days";
$all_days = $pdo->prepare($days_sql);
$all_days->execute();
$days = $all_days->fetchAll(PDO::FETCH_ASSOC);

// Fetching Events, Categories, and Days Relationships
$query = "SELECT * FROM `events_cat_days_junc` 
          LEFT JOIN days ON events_cat_days_junc.event_day_id = days.day_id 
          LEFT JOIN categories ON events_cat_days_junc.event_cat_id = categories.cat_id";
$events = $pdo->prepare($query);
$events->execute();
$events = $events->fetchAll(PDO::FETCH_ASSOC);

// Organizing Data by Day
$data = [];
foreach ($events as $row) {
    $event_id = $row['day_id'];
    if (!isset($data[$event_id])) {
        $data[$event_id] = [
            'day_id' => $row['day_id'],
            'day_name' => $row['day_name'],
            'cats' => []
        ];
    }
    $data[$event_id]['cats'][] = [
        'cat_id' => $row['cat_id'],
        'cat_name' => $row['cat_name']
    ];
}
?>

<main class="bg-gray-900 text-white p-8 lg:px-16">
    <!-- Days Tab Buttons -->
    <div class="mb-8">
        <?php include './components/days.php'; ?>
    </div>

    <!-- Events by Day -->

    <div class=" data mb-4 ">
        <?php foreach ($data as $value) { ?>
        <div class="border-b-[1px] mb-4 border-gray-500">
            <div class="bg-purple-700 w-fit text-white font-semibold py-2 px-4  shadow">
                <?= ucwords($value['day_name']) ?>
            </div>
        </div>
        <div class="bg-gray-800  ">
            <section class="mb-4">
                <!-- Day Header -->

                <!-- Categories -->
                <div class="  gap-4 flex flex-wrap py-2   px-4">
                    <span
                        class="bg-gray-700 text-white   px-3 flex items-center justify-center bg-                           gray-900 text-center text-sm">14:44</span>
                    <?php foreach ($value['cats'] as $cat) { ?>
                    <a id="event" href="index.php?cat_id=<?= $cat['cat_id'] ?>"
                        class="bg-gray-900 catBtn    text-white py-2 px-4  shadow text-sm font-medium">
                        <?= ucfirst($cat['cat_name']) ?>
                    </a>
                    <?php } ?>
                </div>

                <!-- Event Details (Conditional Include) -->
            </section>

            <div class="allEvents_Data ">
                <?php include 'components/allData.php'; ?>
            </div>


        </div>
        <?php } ?>
    </div>

</main>

<script>
let allLinks = document.querySelectorAll('#day');


allLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        let day_id = link.dataset.day_id;
        let xhr = new XMLHttpRequest();
        xhr.open('GET', 'components/EventsByDays.php?day_id=' + day_id);
        xhr.onload = () => {
            if (xhr.status === 200 && xhr.readyState === 4) {
                let realObj = JSON.parse(xhr.responseText);
                console.log(realObj);
                let html = '';

                let dayName = realObj.days.day_name;

                let categoryLinks = realObj.categories.map(cat => `
                    <a id="event" href="index.php?cat_id=${cat.cat_id}" class=" catBtn bg-gray-900 
                    text-white py-2 px-4 shadow text-sm font-medium">
                        ${cat.cat_name}
                    </a>
                `).join('');

                html += `
                    <div class="bg-gray-900">
                        <div class="border-b-[1px] mb-4 border-gray-500">
                            <div class="bg-purple-700 w-fit text-white font-semibold
                             py-2 px-4 shadow">
                                ${dayName}
                            </div>
                        </div>
                        <div class="bg-gray-800 gap-4 flex flex-wrap py-2 px-4">
                         <span
                        class="bg-gray-700 text-white px-3 flex items-center justify-center bg-                           gray-900 text-center text-sm">14:44</span>
                            ${categoryLinks}
                        </div>
                    </div>
                `;

                Object.values(realObj.events).forEach(event => {
                    let speakersHtml = event.speakers.map(speaker => `
                        <div class="flex items-center border-2 border-[#c0ff00] bg-gray-800 p-4">
                            <div class="w-16 h-16 flex items-center justify-center 
                            bg-gray-600  rounded mr-4">
                                <img src="${speaker.profile_image}" alt="${speaker.sp_name}">
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">
                                    <a class="hover:text-[#c0ff00]" href="#">${speaker.sp_name}</a>
                                </h3>
                                <p class="text-sm text-gray-300">
                                    ${speaker.role} <br>
                                    <span class="font-semibold">${speaker.company}</span>
                                </p>
                            </div>
                        </div>
                    `).join('');

                    html += `
                        <div class="all_devs border-[1px] border-[#c0ff00]">
                            <div class="border-b-[1px] border-gray-500 p-8">
                                <h2 class="text-2xl font-bold mt-4">
                                    <a class="hover:text-[#c0ff00]" href="#">${event.event_title}</a>
                                </h2>
                                <div class="flex items-center space-x-6 mt-4 text-sm">
                                    <div class="flex items-center">
                                        <i class="opacity-30 fa fa-map-marker-alt mr-2"></i>
                                        <span>Location not provided</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="opacity-30 fa fa-calendar-alt mr-2"></i>
                                        <span>${event.event_date}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="opacity-30 fa fa-clock mr-2"></i>
                                        <span>${event.event_start_time} To ${event.event_end_time}</span>
                                    </div>
                                </div>
                                <p class="text-gray-400 mt-2">${event.event_desc}</p>
                            </div>
                            <div class="grid grid-cols-1 p-6 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                ${speakersHtml}
                            </div>
                        </div>
                    `;
                });

                document.querySelector('.data').innerHTML = html;
            }
        };
        xhr.send();
    });
});

let allBtns = document.querySelectorAll('.catBtn');
allBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
    })
})
let event = document.querySelectorAll('#event');
event.forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
    })
})
</script>