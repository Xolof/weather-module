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


?><article <?= classList($classes) ?>>
    <h2>Dokumentation för API</h2>
    <p>Skicka en POST-request till rutten "/weather" med nycklarna "location", "format" och "when"
        i bodyn.
    </p>
    <ul>
        <li>Nyckeln "location" ska innehålla en position enligt lat,lon eller en IP-adress.</li>
        <li>"format" ska vara "json".</li>
        <li>"when" ska vara "history" för att visa tidigare väder eller "forecast" för att visa kommande väder.</li>
    </ul>
</article>
