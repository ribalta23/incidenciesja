<?php
if (!defined('ACCESS_ALLOWED')) {
    header('Location: ../404.php');
    exit;
}
?>
<div class="contenedor">
    <div class="menu_top">
        <div>
            <a class="btn_tornar" href="../public/index.php?action=dashboardAdmin"><i class="fa-solid fa-angle-left"></i></a>
            <h2>Calendari</h2>
        </div>
    </div>
    <div class="calendari">
        <div class="calendar-nav">
            <button id="prevMonth" class="btn_mes"><i class="fa-solid fa-angle-left"></i></button>
            <span id="currentMonth"></span>
            <button id="nextMonth" class="btn_mes"><i class="fa-solid fa-angle-right"></i></button>
        </div>
        <div id="calendar"></div>
    </div>
    <div class="event-list">
        <h3>Incidencies</h3>
        <div id="eventList"></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/dayjs"></script>

