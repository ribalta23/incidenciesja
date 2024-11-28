document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const currentMonthEl = document.getElementById('currentMonth');
    const eventListEl = document.getElementById('eventList');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');

    let currentDate = dayjs();
    let events = [];
    async function fetchEvents() {
        const response = await fetch('../controllers/IncidenciaController.php?action=obtenirEsdeveniments');
        events = await response.json();
        renderCalendar();
        renderEventsForDay(currentDate);
    }
    function renderCalendar() {
        const monthStart = currentDate.startOf('month');
        const monthEnd = currentDate.endOf('month');
        const daysInMonth = monthEnd.date();
        const startDayOfWeek = monthStart.day();

        currentMonthEl.textContent = currentDate.format('MMMM YYYY');

        let calendarHTML = '<div class="calendar-grid">';
        calendarHTML += '<div class="day-header">Dill</div>';
        calendarHTML += '<div class="day-header">Dim</div>';
        calendarHTML += '<div class="day-header">Dmc</div>';
        calendarHTML += '<div class="day-header">Dij</div>';
        calendarHTML += '<div class="day-header">Div</div>';
        calendarHTML += '<div class="day-header">Dis</div>';
        calendarHTML += '<div class="day-header">Diu</div>';
        for (let i = 0; i < (startDayOfWeek === 0 ? 6 : startDayOfWeek - 1); i++) {
            calendarHTML += `<div class="day-cell empty"></div>`;
        }

        for (let i = 1; i <= daysInMonth; i++) {
            const date = monthStart.date(i);
            const event = events.find(e => dayjs(e.start).isSame(date, 'day'));
            calendarHTML += `
                <div class="day-cell" data-date="${date}">
                    <div class="date">${i}</div>
                    ${event ? `<div class="event event_${event.prioritat}" title="${event.title}">${event.title}</div>` : ''}
                </div>
            `;
        }
        calendarHTML += '</div>';

        calendarEl.innerHTML = calendarHTML;
        document.querySelectorAll('.day-cell').forEach(cell => {
            cell.addEventListener('click', function () {
                const date = dayjs(this.getAttribute('data-date'));
                renderEventsForDay(date);
            });
        });
    }
    function renderEventsForDay(date) {
        const dayEvents = events.filter(e => dayjs(e.start).isSame(date, 'day'));
        eventListEl.innerHTML = '';
        if (dayEvents.length > 0) {
            dayEvents.forEach(event => {
                eventListEl.innerHTML += `<div class="incidencia"><a href="../public/index.php?action=veureIncidencia&idIncidencia=${event.id}">${event.title}</a></div>`;
            });
        } else {
            eventListEl.innerHTML = '<p>No hi han incidencies</p>';
        }
    }
    prevMonthBtn.addEventListener('click', function () {
        currentDate = currentDate.subtract(1, 'month');
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', function () {
        currentDate = currentDate.add(1, 'month');
        renderCalendar();
    });
    fetchEvents();
});