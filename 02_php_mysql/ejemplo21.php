<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Curso de PHP | mayo de 2016 | ejemplo20.php</title>
  <link rel="stylesheet" href="../css/normalize.css">
  <link rel="stylesheet" href="../css/colors.css">
  <link rel="stylesheet" href="../css/ejemplos.css">
</head>
<body>
  <h1>Borrar una fila de una tabla de MySQL</h1>
  <p>Se conecta a una base de datos llamada "blog" en la máquina "localhost" con el usuario "root" y contraseña
    "root".</p>
  <p>Borra la entrada cuyo ID nos llegue en el parámetro id de la petición GET de HTTP.</p>
  <p>No hace comprobación de errores.</p>


  <?php
  // date_default_timezone... es obligatorio si usais PHP 5.3 o superior
  date_default_timezone_set('Europe/Madrid');
  $fecha_actual = date("Y-m-d H:i:s");
  ?>

  <form action="ejemplo21.php" method="get">
    <div>
      <label for="id">ID:</label>
      <input type="text" id="id" name="id" value=""/>
    </div>
    <div>
      <label for="titulo">Título:</label>
      <input type="text" id="titulo" name="titulo" value=""/>
    </div>
    <div>
      <label for="texto">Texto:</label>
      <textarea id="texto" name="texto" rows="4" cols="40"></textarea>
    </div>
    <div>
      <label for="fecha">Fecha:</label>
      <input type="text" id="fecha" name="fecha" value="<?php echo $fecha_actual; ?>"/>
    </div>
    <div>
      <label for="activo">Activo:</label>
      <input type="checkbox" id="activo" name="activo" checked="checked"/>
    </div>
    <div>
      <input type="reset" id="limpiar" name="limpiar" value="Limpiar"/>
      <input type="submit" id="actualizar" name="actualizar" value="actualizar"/>
      <input type="submit" id="borrar" name="borrar" value="borrar"/>
    </div>
  </form>

  <?php if (isset($_GET['actualizar'])) {



  $id = "";
         if (isset($_GET['id'])) {
             $id = $_GET['id'];
              $titulo = $_GET['titulo'];
           $texto = $_GET['texto'];
              $activo = $_GET['activo'];


             // Abrir la conexión
             $conexion = mysqli_connect("localhost", "root", "root", "blog");

             $q = "update entrada set  titulo = '".$titulo."',texto = '".$texto."' ,  activo = ".$activo."  WHERE id = ".$id;
             echo $q;


             // Ejecutar la consulta en la conexión abierta. No hay "resultset"
              mysqli_query($conexion, $q) or die(mysqli_error($conexion));


              // Comprobar si hemos afectado a alguna fila
              echo "<p class='red'>";

                 if (mysqli_affected_rows($conexion) > 0)
                     echo "Se ha modificado la entrada on ID " . $_GET['id'] . ".";
                 else
 "No se ha modificado ninguna entrada.";

                 echo "</p>";
              // Cerrar la conexión
             mysqli_close($conexion);
         }
     } ?>

  <?php if (isset($_GET['borrar'])) { ?>

  <?php
    // Abrir la conexión
    $conexion = mysqli_connect("localhost", "root", "root", "blog");

    // Borrado, si es que nos pasan un id

      // Borramos los comentarios correspondientes a la entrada
      $q = "delete from comentario where entrada_id='" . $_GET['id'] . "'";
      // Ejecutar la consulta en la conexión abierta. No hay "resultset"
      mysqli_query($conexion, $q) or die(mysqli_error($conexion));

      // Formar la consulta (borrado de una fila)
      $q = "delete from entrada where id='" . $_GET['id'] . "'";

      // Ejecutar la consulta en la conexión abierta. No hay "resultset"
      mysqli_query($conexion, $q) or die(mysqli_error($conexion));

      // Comprobar si hemos afectado a alguna fila
      echo "<p class='red'>";

      if (mysqli_affected_rows($conexion) > 0)
        echo "Se ha borrado la entrada on ID " . $_GET['id'] . ".";
      else
        echo "No se ha borrado ninguna entrada.";

      echo "</p>";


    // Formar la consulta (seleccionando todas las filas)
    $q = "select * from entrada";

    // Ejecutar la consulta en la conexión abierta y obtener el "resultset" o abortar y mostrar el error
    $r = mysqli_query($conexion, $q) or die(mysqli_error($conexion));

    // Calcular el número de filas
    $total = mysqli_num_rows($r);

    // Mostrar el contenido de las filas, creando una tabla XHTML
    if ($total > 0) {
      echo '<table border="1">';
      echo '<tr><th>ID</th><th>Título</th><th>Texto</th><th>Fecha</th><th>Activo</th></tr>';

      while ($fila = mysqli_fetch_assoc($r)) {
        echo "<tr>";
        echo "<td>" . $fila['id'] . "</td>";
        echo "<td>" . $fila['titulo'] . "</td>";
        echo "<td>" . $fila['texto'] . "</td>";
        echo "<td>" . $fila['fecha'] . "</td>";
        echo "<td>" . $fila['activo'] . "</td>";
        echo "</tr>";
      }

      echo '</table>';
    }

    // Cerrar la conexión
    mysqli_close($conexion);
  ?>
  <?php } ?>



  <p><a class="blue" href="ejemplo21.php">Recargar la página</a></p>
</body>
</html>
