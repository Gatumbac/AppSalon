<?php include_once __DIR__ . '/../templates/barra-sesion.php' ?>

<h1 class="nombre-pagina">Panel de Administración</h1>

<div class="busqueda">
    <h2>Buscar Citas</h2>
    <form class="formulario" action="">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input 
                type="date"
                id="fecha"
                value="<?php echo $fecha ?>"
                required
            >
        </div>
    </form>
</div>

<div class="citas-admin">
    <ul class="citas">
        <?php
            $citaId = 0;
            foreach ($citas as $key => $cita):
                if ($citaId != $cita->getId()):
                    $total = 0;

        ?>          
                    <li>
                        <p><span>ID: </span><?php echo $cita->getId() ?></p>
                        <p><span>Cliente: </span><?php echo $cita->getCliente() ?></p>
                        <p><span>Teléfono: </span><?php echo $cita->getTelefono() ?></p>
                        <p><span>Email: </span><?php echo $cita->getEmail() ?></p>
                        <h3>Servicios</h3>
        <?php
                    $citaId = $cita->getId();
                endif;
                $total += floatval($cita->getPrecio());
        ?>
                
                    <p class="servicio"><?php echo $cita->getServicio() . ' - $' . $cita->getPrecio() ?></p>

        <?php
                $actual = $cita->getId();
                $siguienteCita = $citas[$key + 1] ?? null;
                $siguiente = $siguienteCita != null ? $siguienteCita->getId() : '0';
                if (esUltimo($actual, $siguiente)):
        ?>
                <p class="total"><span>Total: </span>$<?php echo $total ?></p>
        <?php
                endif;
            endforeach;
        ?>
    </ul>

</div>

<?php
    $script = "
        <script src='/build/js/buscador.js'></script>
    ";
?>