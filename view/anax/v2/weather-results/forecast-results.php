<?php

namespace Anax\View;

/**
 * Render content within an article.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

// Prepare classes
$classes[] = "article";
if (isset($class)) {
    $classes[] = $class;
}
?>
<article <?= classList($classes) ?>>

<?php
if (array_key_exists("error", $content["location"])) {
    echo "<h2>Positionen kunde ej geokodas.</h2>";
} else {
    echo "<h2>" . $content["location"]["display_name"] . "</h2>";
    echo "<div id='mapid'></div>";
    echo "<h2>Kommande väder</h2>";
}

foreach ($content["weather"] as $day => $arr) {
    echo "<div class='weather_day_div'>";
    echo "<h3>$day</h3>";

    foreach ($arr as $item) {
        echo <<<EOD
        <div class="weather_time_div">
            <h5>{$item["time"]}</h5>
            <p>Temperature: {$item["temp"]} °C</p>
            <p>Feels like: {$item["feels_like"]} °C</p>
            <p>Wind: {$item["wind"]} m/s</p>
            <p>Weather: {$item["description"]}</p>
        </div>
EOD;
    }
    echo "</div>";
};
?>

</article>

<?php if (!array_key_exists("error", $content["location"])) : ?>
<script>
    var lat = <?= $content["location"]["lat"] ?>;
    var lon = <?= $content["location"]["lon"] ?>;
    var locationName = "<?= $content["location"]["name"] ?>";
</script>
<script src ="js/leaflet.js"></script>
<script src ="js/myMap.js"></script>
<?php endif; ?>
