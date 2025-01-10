<?php
include('include/database.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leap Task</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

</head>

<body>
    <div class="container mx-auto max-w-[1920px] w-full">
        <header>

            <?php include('components/header.php'); ?>
        </header>
        <main>
            <?php include('components/events.php'); ?>
        </main>
        <category>
            <?php include('components/category.php'); ?>

        </category>
        <footer>
            <?php include('components/footer.php'); ?>
        </footer>

    </div>
</body>
<script>
let executive_summit = document.querySelectorAll("#event");

executive_summit.forEach((item) => {
    item.addEventListener("click", (e) => {
        e.preventDefault();
        executive_summit.forEach((btn) => {
            if (btn !== item) {
                item.classList.add("border-b-4");
                item.classList.add("py-2");
                item.classList.add("border-[#c0ff00]");
                item.classList.add("");

                btn.classList.remove("border-b-4");
                btn.classList.remove("py-2");
                btn.classList.remove("border-[#c0ff00]");
                btn.classList.remove("");
            }

        })



    });
});
</script>

</html>