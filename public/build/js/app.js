let pasoActual = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
})

function iniciarApp() {
    mostrarSeccion();
    tabs();
    paginador();
    paginaAnterior();
    paginaSiguiente();
    consultarAPI();
    extraerDatosCita();
    mostrarResumen();
}

function mostrarSeccion() {
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }

    const seccion = document.querySelector(`#paso-${pasoActual}`);
    seccion.classList.add('mostrar');

    resaltarTab();
}

function resaltarTab() {
    const tabAnterior = document.querySelector('.tabs .actual');
    if(tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    const seccion = document.querySelector(`[data-paso="${pasoActual}"]`);
    seccion.classList.add('actual');
}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach( boton => boton.addEventListener('click', function(e) {
        pasoActual = parseInt(e.target.dataset.paso);
        mostrarSeccion();
        paginador();
    }))
}

function paginador() {
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    switch (pasoActual) {
        case 1:
            paginaAnterior.classList.add('ocultar');
            paginaSiguiente.classList.remove('ocultar');
            break;
        case 2:
            paginaSiguiente.classList.remove('ocultar');
            paginaAnterior.classList.remove('ocultar');
            break;
        case 3:
            paginaAnterior.classList.remove('ocultar');
            paginaSiguiente.classList.add('ocultar');
            mostrarResumen();
            break;
        default:
            break;
    }
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function() {
        if(pasoActual < pasoInicial) return;
        pasoActual--;
        paginador();
        mostrarSeccion();
    })
}

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function() {
        if(pasoActual > pasoFinal) return;
        pasoActual++;
        paginador();
        mostrarSeccion();
    })
}

async function consultarAPI() {
    try {
        const url = 'http://localhost:3000/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach( servicio => {
        const {id, nombre, precio} = servicio;
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        servicioDiv.addEventListener('click', function() {
            seleccionarServicio(servicio);
        })

        document.querySelector('#servicios').appendChild(servicioDiv);
    } );
}

function seleccionarServicio(servicio) {
    const { id } = servicio;
    const { servicios } = cita;

    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    const servicioExistente = servicios.some(agregado => agregado.id === id);
    if (servicioExistente) {
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    } else {
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }
}

function extraerDatosCita() {
    cita.nombre = document.querySelector('#nombre').value;
    seleccionarFecha();
    seleccionarHora();
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {
        const dia = new Date(e.target.value).getUTCDay();
        if ([6,0].includes(dia)) {
            e.target.value = '';
            mostrarAlerta('error', 'Fines de semana no permitidos', '.formulario');
        } else {
            cita.fecha = e.target.value;
        }
    })
}

function mostrarAlerta(tipo, mensaje, elemento, desaparece = true) {
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia) {
        alertaPrevia.remove();
    }

    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    
    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if (desaparece) {
        setTimeout(function() {
            alerta.remove();
        }, 3000);
    }
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if (hora < 9 || hora > 17) {
            e.target.value = '';
            mostrarAlerta('error', 'Hora no válida', '.formulario');
        } else {
            cita.hora = horaCita;
        }
    })
}


function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');
    limpiarContenedor(resumen);
    if(!validarDatosCita()) return;

    const { nombre, fecha, hora, servicios } = cita;

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span>${nombre}`;

    const fechaCliente = document.createElement('P');
    fechaCliente.innerHTML = `<span>Fecha: </span>${formatearFecha(fecha)}`;

    const horaCliente = document.createElement('P');
    horaCliente.innerHTML = `<span>Hora: </span>${hora} Horas`;

    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    headingServicios.classList.add('heading-servicios');
    resumen.appendChild(headingServicios);

    servicios.forEach(servicio => {
        const { nombre, precio } = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span>$${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    //Boton para reservar cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCliente);
    resumen.appendChild(horaCliente);
    resumen.appendChild(botonReservar);

}

function validarDatosCita() {
    if ( Object.values(cita).includes('') || cita.servicios.length === 0 ) {
        mostrarAlerta('error', 'Faltan datos de servicios, fecha u hora', '.contenido-resumen', false);
        return false;
    } 
    return true;
}

function limpiarContenedor(contenedor) {
    while (contenedor.firstChild) {
        contenedor.removeChild(contenedor.firstChild);
    }
}

function formatearFecha(fecha) {
    const fechaObj = new Date(fecha);
    const fechaUTC = new Date(Date.UTC(fechaObj.getFullYear(), fechaObj.getMonth(), fechaObj.getDate() + 2))
    const opciones = {weekday:'long', year:'numeric', month:'long', day:'numeric'};
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones)
    return fechaFormateada;
}

function reservarCita() {
    console.log('Reservando...');
}