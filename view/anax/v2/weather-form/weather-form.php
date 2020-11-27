<?php

namespace Anax\View;

/**
 * A form for the weather page and API.
 */

// Prepare classes
$classes[] = "article";
if (isset($class)) {
    $classes[] = $class;
}

?><article <?= classList($classes) ?>>
    <h2>Väder</h2>

    <p>Dokumentation för API:t finns <a href="weather/api-info">här</a>.</p>

    <p>Visa väder för en plats genom att använda formuläret nedan.</p>

    <p>Fyll i en IP-adress eller koordinater enligt latitud, longitud. Exempelvis 56.04,13.18.</p>

    <form action="weather" method="post">
        <input
            value="56.04,13.18"
            type="text"
            name="location"
            placeholder="ip-address eller koordinater"
            required
        >
        </input>

        <br>
        <br>

        <input type="radio" id="html"
         name="format" value="html"
         checked>
        <label for="html">HTML</label>

        <input type="radio" id="json"
         name="format" value="json">
        <label for="json">JSON</label>

        <br>
        <br>

        <input type="radio" id="forecast"
         name="when" value="forecast" checked>
        <label for="forecast">Prognos</label>

        <input type="radio" id="history"
         name="when" value="history">
        <label for="history">Tidigare</label>

        <br>
        <br>

        <input type="submit" value="Hämta">
    </form>
</article>
