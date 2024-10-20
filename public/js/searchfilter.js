document.getElementById('search').addEventListener('keyup', function() {
    const searching = this.value.toLowerCase();
    const rows = document.querySelectorAll('#pharmacyTab tbody tr');

    rows.forEach(row => {
        const nombre = row.cells[1].textContent.toLowerCase();
        if (nombre.includes(searching)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});