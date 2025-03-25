<h1 class="nombre-pagina">Crear Nueva Cita</h1>
<p class="descripcion-pagina">Elije tus servicios y coloca tus datos</p>
<div id="app">

    <nav class="tabs">
        <button type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Información cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>

    <div id="paso-1" class="seccion">
            <h2>Servicios</h2>
            <p>Elije tus servicios a continuación</p>
            <div id="servicios" class="listado-servicios">

            </div>
    </div>

    <div id="paso-2" class="seccion">
            <h2>Tus datos y Cita</h2>
            <p>Coloca tus datos y la fecha de tu cita</p>

            <form class="formulario">
                <div class="campo">
                    <label for="nombre">Nombre</label>
                    <input 
                        type="text"
                        id="nombre"
                        placeholder="Tu nombre"
                        value="<?php echo $nombre ?>"
                        disabled
                    >
                </div>

                <div class="campo">
                    <label for="fecha">Fecha</label>
                    <input 
                        type="date"
                        id="fecha"
                        min="<?php echo date('Y-m-d', strtotime('+1 day')) ?>"
                        max="<?php echo date('Y-m-d', strtotime('+1 month')) ?>"
                        required
                    >
                </div>

                <div class="campo">
                    <label for="hora">Hora</label>
                    <input
                        type="time"
                        id="hora"
                        required
                    >
                </div>
            </form>
    </div>

    <div id="paso-3" class="seccion contenido-resumen">
            <h2>Resumen</h2>
            <p>Verifica que la información sea correcta</p>
    </div>

    <div class="paginacion">
        <button id="anterior" class="boton">&laquo; Anterior</button>
        <button id="siguiente" class="boton">Siguiente &raquo;</button>
    </div>
</div>

<?php
    $script = "
        <script src='/build/js/app.js'></script>
    ";
?>