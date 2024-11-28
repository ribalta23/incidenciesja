$(document).ready(function () {
    $('#info_prioritat').click(function () {
        $('#modal_info_prioritat').fadeIn();
    });
    $('#close_modal_info').click(function () {
        $('#modal_info_prioritat').fadeOut();
    });
});

function mostrarElementos() {
    const tipusLocalitzacio = document.getElementById('tipus_localitzacio').value;
    const selectsAules = document.getElementById('selects_aules');
    const inputAltres = document.getElementById('input_altres');

    if (tipusLocalitzacio === 'aules') {
        selectsAules.style.display = 'flex';
        inputAltres.style.display = 'none';
        select_pis();
    } else if (tipusLocalitzacio === 'altres') {
        selectsAules.style.display = 'none';
        inputAltres.style.display = 'flex';
        select_altres();
    } else {
        selectsAules.style.display = 'none';
        inputAltres.style.display = 'none';
    }
}
function select_pis() {
    const tipusLocalitzacio = document.getElementById('tipus_localitzacio').value;

    if (tipusLocalitzacio === 'aules') {
        fetch('../controllers/IncidenciaController.php?action=select_pis')
            .then(response => response.json())
            .then(data => {
                const pisSelect = document.getElementById('pis');
                pisSelect.innerHTML = '<option value="" selected>Selecciona un pis</option>';
                data.forEach(pis => {
                    pisSelect.innerHTML += `<option value="${pis.pis}">${pis.pis}</option>`;
                });
            })
            .catch(error => console.error('Error:', error));
    }
}

function select_aula() {
    const pisId = document.getElementById('pis').value;
    if (pisId) {
        fetch(`../controllers/IncidenciaController.php?action=obtenir_aules&pisId=${pisId}`)
            .then(response => response.json())
            .then(data => {
                const aulaSelect = document.getElementById('aula');
                aulaSelect.innerHTML = '<option value="" selected>Selecciona una aula</option>';
                data.forEach(aula => {
                    aulaSelect.innerHTML += `<option value="${aula.id}">${aula.espai}</option>`;
                });
            })
            .catch(error => console.error('Error:', error));
    } else {
        document.getElementById('aula').innerHTML = '<option value="" selected>Selecciona una aula</option>';
    }
}

function select_altres() {
    fetch('../controllers/IncidenciaController.php?action=obtenir_altres')
        .then(response => response.json())
        .then(data => {
            const altresSelect = document.getElementById('altres');
            altresSelect.innerHTML = '<option value="" selected>Selecciona una localitzacio</option>';
            data.forEach(altres => {
                altresSelect.innerHTML += `<option value="${altres.id}">${altres.espai}</option>`;
            });
        })
        .catch(error => console.error('Error:', error));
}

function select_tecnics() {
    const idTipusIncidencia = document.getElementById('id_tipo_incidencia').value;
    if (idTipusIncidencia) {
        fetch(`../controllers/IncidenciaController.php?action=obtenir_tecnics&id_tipus_incidencia=${idTipusIncidencia}`)
            .then(response => response.json())
            .then(data => {
                const tecnicsSelect = document.querySelector('select[name="id_usuari"]');
                tecnicsSelect.innerHTML = '';
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Selecciona un tècnic';
                tecnicsSelect.appendChild(defaultOption);
                data.forEach(tecnic => {
                    const option = document.createElement('option');
                    option.value = tecnic.id_usuari;
                    option.textContent = tecnic.nom + (tecnic.rol === 'administrador' ? ' - Administrador' : '');

                    tecnicsSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    } else {
        const tecnicsSelect = document.querySelector('select[name="id_usuari"]');
        tecnicsSelect.innerHTML = '<option value="">Selecciona un tècnic</option>';
    }
}