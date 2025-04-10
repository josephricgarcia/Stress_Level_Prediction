<?php
include 'session.php';
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense: Tips And Resources</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="images/stresssense_logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
</head>
<body>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/stresssense_logo.png" alt="Logo"> STRESS SENSE
        </div>
        <nav>
            <a href="Home.php">HOME</a>
            <a href="Assessment.php">ASSESSMENT</a>
            <a href="History.php">HISTORY</a>
            <a href="Tips And Resources.php" class="active">TIPS AND RESOURCES</a>
            <a href="Settings.php">SETTINGS</a>
        </nav>
    </header>

    <div class = "tips">
        <h1>Stress Management Tips</h1>
        <h4>Here are some tips and resources to help you manage your stress.</h4>
    </div>

    <div class="tips-container">
        <div class="low">
            <h2>Low Stress</h2>
            <ul>
                <li>Exercise regularly</li>
                <li>Get enough sleep</li>
                <li>Practice relaxation techniques</li>
                <li>Take breaks</li>
                <li>Stay organized</li>
            </ul>
        </div>

        <div class="moderate">
            <h2>Moderate Stress</h2>
            <ul>
                <li>Practice mindfulness</li>
                <li>Set boundaries</li>
                <li>Practice self-care</li>
                <li>Seek support</li>
                <li>Take time for yourself</li>
            </ul>
        </div>

        <div class="high">
            <h2>High Stress</h2>
            <ul>
                <li>Seek professional help</li>
                <li>Practice stress management techniques</li>
                <li>Set realistic goals</li>
                <li>Practice self-compassion</li>
                <li>Take care of your physical health</li>
            </ul>
        </div>
    </div>

    <div class="tips">
        <h1>Stress Management Videos</h1>
        <h4>Here are some videos to help you manage your stress.</h4>

        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/grfXR6FAsI8" frameborder="0" allowfullscreen></iframe>
                </div>

                <div class="swiper-slide">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/L9zwduYp9G0" frameborder="0" allowfullscreen></iframe>
                </div>

                <div class="swiper-slide">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/_2BFj-k__s0" frameborder="0" allowfullscreen></iframe>
                </div>

                <div class="swiper-slide">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/CZTc8_FwHGM" frameborder="0" allowfullscreen></iframe>
                </div>

                <div class="swiper-slide">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/Bk2-dKH2Ta4" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>

            <br>
            <br>

            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>

        </div>


    </div>

    



    <footer>
        &copy; 2025 StresSense. All Rights Reserved. |
        <a href="About Us.php">About Us</a> | <a href="Privacy Policy.php">Privacy Policy</a> | <a href="Terms Of Service.php">Terms of Service</a> | <a href="Contact.php">Contact Us</a>
     </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 5,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    </script>

</body>
</html>