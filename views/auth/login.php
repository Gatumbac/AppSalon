<h1 class="nombre-pagina">Log In</h1>
<p class="descripcion-pagina">Inicia sesión con tus credenciales</p>

<form class="formulario" method="POST" action="/">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            placeholder="Tu email"
            name="email"
        >
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            placeholder="Tu contraseña"
            name="password"
        >
    </div>

    <input type="submit" class="boton" value="Iniciar Sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? <span>Crear una</span></a></p>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>