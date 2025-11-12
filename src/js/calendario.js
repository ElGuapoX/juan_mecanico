document.addEventListener('DOMContentLoaded', function () {
    const calendar = document.getElementById('calendar');
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    function generateCalendar(month, year) {
        const daysOfWeek = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        // Crear el encabezado del calendario
        let calendarHTML = '<div class="calendar-header">';
        calendarHTML += `<button id="prev-month">&lt;</button>`;
        calendarHTML += `<h2>${new Date(year, month).toLocaleString('es-ES', { month: 'long', year: 'numeric' })}</h2>`;
        calendarHTML += `<button id="next-month">&gt;</button>`;
        calendarHTML += '</div>';

        // Crear tabla del calendario
        calendarHTML += '<table class="calendar-table">';
        calendarHTML += '<thead><tr>';
        daysOfWeek.forEach(day => {
            calendarHTML += `<th>${day}</th>`;
        });
        calendarHTML += '</tr></thead>';
        calendarHTML += '<tbody>';

        // Obtener el primer día del mes y el número de días en el mes
        const firstDay = new Date(year, month, 1).getDay();
        const numberOfDays = new Date(year, month + 1, 0).getDate();

        let day = 1;
        for (let i = 0; i < 6; i++) {
            calendarHTML += '<tr>';
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDay) {
                    calendarHTML += '<td></td>';
                } else if (day > numberOfDays) {
                    calendarHTML += '<td></td>';
                } else {
                    const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    calendarHTML += `<td data-date="${dateString}">${day}`;
                    
                    // Añadir citas para este día
                    const appointmentsForDay = window.citas.filter(cita => cita.date === dateString);
                    if (appointmentsForDay.length > 0) {
                        appointmentsForDay.forEach(appointment => {
                            calendarHTML += `<div class="appointment">
                                <span>${appointment.time}</span> - <span>${appointment.client}</span>: <span>${appointment.service}</span>
                            </div>`;
                        });
                    }

                    calendarHTML += '</td>';
                    day++;
                }
            }
            calendarHTML += '</tr>';
            if (day > numberOfDays) {
                break;
            }
        }

        calendarHTML += '</tbody></table>';
        calendar.innerHTML = calendarHTML;

        // Aplicar estilos dinámicos al encabezado del calendario
        document.querySelectorAll('.calendar-table thead th').forEach(th => {
            th.style.backgroundColor = '#3d3d3d'; // Fondo oscuro
            th.style.color = 'white'; // Texto blanco
            th.style.fontWeight = 'bold'; // Negrita
        });

        // Añadir event listeners para los botones de navegación
        document.getElementById('prev-month').addEventListener('click', function () {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            generateCalendar(currentMonth, currentYear);
        });

        document.getElementById('next-month').addEventListener('click', function () {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            generateCalendar(currentMonth, currentYear);
        });
    }

    generateCalendar(currentMonth, currentYear);
});

function cargarCitas(citas) {
    // La función cargarCitas simplemente actualiza la variable global y regenera el calendario
    window.citas = citas;
    const calendar = document.getElementById('calendar');
    calendar.innerHTML = '';
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    generateCalendar(currentMonth, currentYear);
}
