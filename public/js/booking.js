document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('buchung_datum');

    if (dateInput) {
        flatpickr(dateInput, {
            dateFormat: "Y-m-d",  // Datumsformat
            altInput: true,  // Schöneres Eingabefeld
            altFormat: "F j, Y",  // Format für die alternative Anzeige
            enableTime: false,  // Nur Datum (kein Zeitwähler)
        });
    }
});
