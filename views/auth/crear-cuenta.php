<h1 class="nombre-pagina">Crea tu cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php include_once __DIR__ . '/../templates/alertas.php' ?>

<form class="formulario" method="POST" action="/crear-cuenta">

    <div class="campo">
        <label for="nombre">Nombre</label>
        <input 
            type="text"
            id="nombre"
            placeholder="Tu nombre"
            name="nombre"
            value = "<?php echo s($user->getNombre()) ?>"
            required
        >
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input 
            type="text"
            id="apellido"
            placeholder="Tu apellido"
            name="apellido"
            value = "<?php echo s($user->getApellido()) ?>"
            required
        >
    </div>

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input 
            type="tel"
            id="telefono"
            placeholder="Tu número de teléfono"
            name="telefono"
            value = "<?php echo s($user->getTelefono()) ?>"
            required
        >
    </div>

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

    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            placeholder="Tu contraseña"
            name="password"
            value = "<?php echo s($user->getPassword()) ?>"
            required
        >
    </div>

    <input type="submit" class="boton" value="Crear Cuenta">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? <span>Inicia Sesión</span></a></p>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>