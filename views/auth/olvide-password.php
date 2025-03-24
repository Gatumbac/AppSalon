<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Escribe tu email para reestablecer tu password</p>

<?php include_once __DIR__ . '/../templates/alertas.php' ?>

<form class="formulario" method="POST" action="/olvide">

    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            placeholder="Tu email"
            name="email"
            value = "<?php echo s($user->getEmail()) ?>"
            required
        >
    </div>

    <input type="submit" class="boton" value="Enviar Instrucciones">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? <span>Inicia Sesión</span></a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? <span>Crear una</span></a>
</div>