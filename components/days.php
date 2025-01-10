<div class="flex space-x-2 mb-6">
    <a href=""
        class="py-2 px-4 bg-[#c0ff00] text-black hover:bg-[#c0ff00]  hover:text-black font-                 semibold ">All
        Events</a>
    <?php foreach($days as $day) { ?>
    <a data-day_id="<?=$day['day_id']?>" id="day" href="index.php?day_id=<?=$day['day_id']?>"
        class="py-2 px-4 bg-gray-800 text-white font-semibold hover:bg-[#c0ff00] hover:text-black font-               semibold "><?= ucwords($day['day_name']) ?></a>
    <?php } ?>

</div>