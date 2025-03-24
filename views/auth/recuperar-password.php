<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuación</p>

<?php include_once __DIR__ . '/../templates/alertas.php' ?>

<?php if ($error) return; ?>


<form class="formulario" method="POST">

    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            placeholder="Tu nuevo password"
            name="password"
            required
        >
    </div>

    <input type="submit" class="boton" value="Guardar Password">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? <span>Inicia Sesión</span></a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? <span>Crear una</span></a>
</div>