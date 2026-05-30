<?php
// Bitácora de Seguridad - index.php
// Descripción: Sistema de registro de actividades usando archivos de texto plano en PHP.
$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion  = trim($_POST['descripcion']  ?? '');
    $responsable  = trim($_POST['responsable']  ?? '');
    $fecha        = trim($_POST['fecha']        ?? '');

    // validar los datos vacios
    if (empty($descripcion) || empty($responsable) || empty($fecha)) {
        $mensaje      = 'Todos los campos son obligatorios. Por favor completa el formulario.';
        $tipo_mensaje = 'error';
    } else {
        // Esto se supone que evita la inyeccion de codigo
        $descripcion = htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8');
        $responsable = htmlspecialchars($responsable, ENT_QUOTES, 'UTF-8');
        $fecha       = htmlspecialchars($fecha,       ENT_QUOTES, 'UTF-8');

        //Formato de entrada
        $entrada  = "Fecha: {$fecha}\n";
        $entrada .= "Actividad: {$descripcion}\n";
        $entrada .= "Responsable: {$responsable}\n";
        $entrada .= "-------------------------------\n";

        $archivo = __DIR__ . '/bitacora.txt';

        // FILE_APPEND abre en modo append, LOCK_EX evita escrituras simultáneas
        if (file_put_contents($archivo, $entrada, FILE_APPEND | LOCK_EX) !== false) {
            $mensaje      = 'Actividad registrada correctamente en la bitácora.';
            $tipo_mensaje = 'exito';
        } else {
            $mensaje      = 'Error al escribir en el archivo. Verifica los permisos del directorio.';
            $tipo_mensaje = 'error';
        }
    }
}

// Lectura de la bitácora para mostrar en el panel
$archivo      = __DIR__ . '/bitacora.txt';
$entradas     = [];
$archivo_vacio = true;

if (file_exists($archivo)) {
    $contenido = file_get_contents($archivo);
    if (!empty(trim($contenido))) {
        $archivo_vacio = false;
        // Dividir por el separador para obtener entradas individuales
        $bloques = explode("-------------------------------\n", $contenido);
        foreach ($bloques as $bloque) {
            $bloque = trim($bloque);
            if (!empty($bloque)) {
                $entradas[] = $bloque;
            }
        }
        //esto es para ordenarlos
        $entradas = array_reverse($entradas);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora de Seguridad</title>
   <style>
        /*  Variables (Esquema Gundam Mk-II Titans) */
        :root {
            --bg:        #0c0f17; /* Azul oscuro noche profundo (Chasis primario) */
            --surface:   #151a26; /* Azul marino militar sutil (Placas de armadura) */
            --border:    #242d3e; /* Azul grisáceo oscuro para juntas y uniones */
            --accent:    #3a5ba0; /* Azul Titanes clásico / Escudo */
            --accent2:   #4d77cc; /* Azul Titanes iluminado */
            --danger:    #e62a34; /* Rojo intenso (Sensores, pies y detalles de advertencia) */
            --warning:   #f7cc16; /* Amarillo vivo (Ventilaciones del pecho y hombros) */
            --text:      #d1d7e3; /* Gris claro azulado para texto legible */
            --text-dim:  #53637c; /* Gris azulado apagado para etiquetas del sistema */
            --mono:      'Share Tech Mono', monospace;
            --sans:      'Rockwell', 'Courier New', courier, serif; 
            --condensed: 'Barlow Condensed', sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--sans);
            font-weight: 400; 
            min-height: 100vh;
            padding: 0;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            background-size: 200px 200px;
            pointer-events: none;
            z-index: 0;
            opacity: .6;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(58,91,160,.4); }
            50%       { opacity: .6; box-shadow: 0 0 0 6px rgba(58,91,160,0); }
        }

        main {
            position: relative;
            z-index: 1;
            max-width: 960px;
            margin: 0 auto;
            padding: 2.5rem 2rem 4rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 700px) {
            main { grid-template-columns: 1fr; }
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            position: relative;
        }

        .panel-label {
            font-family: var(--mono);
            font-size: .65rem;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--text-dim);
            padding: .75rem 1.25rem .6rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .panel-label span.tag {
            background: var(--accent);
            color: #fff;
            font-size: .6rem;
            padding: .1rem .4rem;
            font-weight: 700;
        }

        .panel-body { padding: 1.5rem 1.25rem; }

        .field { margin-bottom: 1.25rem; }

        .field label {
            display: block;
            font-family: var(--mono);
            font-size: .68rem;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--text-dim);
            margin-bottom: .45rem;
        }

        .field input[type="text"],
        .field input[type="date"],
        .field textarea {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text);
            font-family: var(--mono);
            font-size: .85rem;
            padding: .65rem .85rem;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            appearance: none;
            -webkit-appearance: none;
        }

        .field input[type="date"] { color-scheme: dark; }

        .field textarea {
            resize: vertical;
            min-height: 90px;
            line-height: 1.55;
        }

        .field input:focus,
        .field textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(58,91,160,.2);
        }

        .btn-submit {
            width: 100%;
            background: var(--accent);
            color: #fff;
            border: none;
            font-family: var(--condensed);
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: .15em;
            text-transform: uppercase;
            padding: .8rem 1rem;
            cursor: pointer;
            transition: background .15s, transform .1s;
            margin-top: .25rem;
        }

        .btn-submit:hover  { background: var(--accent2); }
        .btn-submit:active { transform: scaleY(.97); }

        .msg {
            padding: .75rem 1rem;
            font-family: var(--mono);
            font-size: .78rem;
            letter-spacing: .05em;
            margin-bottom: 1.25rem;
            border-left: 3px solid;
        }

        .msg.exito { border-color: var(--accent);  background: rgba(58,91,160,.08); color: var(--text); }
        .msg.error { border-color: var(--danger);  background: rgba(230,42,52,.08); color: var(--danger); }

        /* ── Bitácora ── */
        .bitacora-panel { grid-column: 1 / -1; }

        .contador {
            margin-left: auto;
            font-family: var(--mono);
            font-size: .65rem;
            color: var(--accent);
        }

        .bitacora-empty {
            font-family: var(--mono);
            font-size: .8rem;
            color: var(--text-dim);
            text-align: center;
            padding: 2.5rem 0;
            letter-spacing: .1em;
        }

        ol.bitacora-lista {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        ol.bitacora-lista li {
            counter-increment: entrada;
            background: var(--bg);
            border: 1px solid var(--border);
            display: grid;
            grid-template-columns: 3rem 1fr;
            overflow: hidden;
            transition: border-color .2s;
        }

        ol.bitacora-lista li:hover { border-color: var(--accent); }

        .entry-num {
            background: #111520;
            border-right: 1px solid var(--border);
            display: grid;
            place-items: center;
            font-family: var(--mono);
            font-size: .7rem;
            color: var(--text-dim);
            padding: .75rem .25rem;
        }

        .entry-body { padding: .9rem 1rem; }

        .entry-fecha {
            font-family: var(--mono);
            font-size: .68rem;
            color: var(--warning);
            letter-spacing: .1em;
            margin-bottom: .4rem;
        }

        .entry-desc {
            font-size: .92rem;
            color: #e0eae4;
            font-weight: 400;
            margin-bottom: .3rem;
            line-height: 1.45;
        }

        .entry-resp {
            font-family: var(--mono);
            font-size: .72rem;
            color: var(--text-dim);
        }

        .entry-resp span { color: var(--warning); }

        /* ── Animación entrada ── */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        ol.bitacora-lista li {
            animation: fadeIn .3s ease both;
        }

        <?php for ($i = 1; $i <= 50; $i++): ?>
        ol.bitacora-lista li:nth-child(<?= $i ?>) { animation-delay: <?= ($i - 1) * 0.04 ?>s; }
        <?php endfor; ?>
    </style>
</head>
<body>
<main>

    <!--Formulario-->
    <div class="panel">
        <div class="panel-body">

            <?php if (!empty($mensaje)): ?>
                <div class="msg <?= htmlspecialchars($tipo_mensaje) ?>">
                    <?= $mensaje ?>
                </div>
            <?php endif; ?>
            <!--Dato curioso, no me gustan los div-->
            <form method="POST" action="index.php" novalidate>

                <div class="field">
                    <label for="fecha">Fecha</label>
                    <input
                        type="date"
                        id="fecha"
                        name="fecha"
                        value="<?= htmlspecialchars($_POST['fecha'] ?? date('Y-m-d')) ?>"
                        required
                    >
                </div>

                <div class="field">
                    <label for="responsable">Responsable</label>
                    <input
                        type="text"
                        id="responsable"
                        name="responsable"
                        placeholder="Nombre del agente"
                        value="<?= htmlspecialchars($_POST['responsable'] ?? '') ?>"
                        required
                        autocomplete="off"
                    >
                </div>

                <div class="field">
                    <label for="descripcion">Descripción de la actividad</label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        placeholder="Detalla la actividad, incidente o tarea realizada..."
                        required
                    ><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    &#x25B6;&nbsp; Guardar en bitácora
                </button>

            </form>
        </div>
    </div>

    <!--Panel de Informacion-->
    <div class="panel">
        <div class="panel-body">
            <pre style=" font-family: var(--mono); font-size: .75rem; color: var(--text-dim); background: var(--bg); border: 1px solid var(--border); padding: 1rem; line-height: 1.7; overflow-x: auto;">Fecha: YYYY-MM-DD
                Actividad: descripción
                Responsable: nombre</pre>

            <p style="font-size:.8rem; color:var(--text-dim); margin-top:1rem; line-height:1.6;">
                Cada entrada se añade al final del archivo
                <code style="color:var(--accent);font-family:var(--mono);">bitacora.txt</code>
                usando <code style="color:var(--accent);font-family:var(--mono);">FILE_APPEND</code>
                y <code style="color:var(--accent);font-family:var(--mono);">LOCK_EX</code>
                para evitar escrituras simultáneas.
            </p>

            <?php if (file_exists($archivo)): ?>
            <p style="font-size:.75rem; color:var(--text-dim); margin-top:.75rem; font-family:var(--mono);">
                bitacora.txt &mdash;
                <span style="color:var(--accent);">
                    <?= number_format(filesize($archivo)) ?> bytes
                </span>
            </p>
            <?php endif; ?>
        </div>
    </div>

    <!--Panel bitacora-->
    <div class="panel bitacora-panel">
        <div class="panel-label">
            Registro de actividades
            <span class="contador">
                <?= $archivo_vacio ? '0' : count($entradas) ?> entradas
            </span>
        </div>
        <div class="panel-body">

            <?php if ($archivo_vacio): ?>
                <p class="bitacora-empty">— Bitácora vacía. No hay registros aún. —</p>
            <?php else: ?>
                <ol class="bitacora-lista">
                    <?php foreach ($entradas as $i => $entrada):
                        // Parsear cada bloque
                        preg_match('/^Fecha:\s*(.+)$/m',        $entrada, $mFecha);
                        preg_match('/^Actividad:\s*(.+)$/ms',   $entrada, $mAct);
                        preg_match('/^Responsable:\s*(.+)$/m',  $entrada, $mResp);

                        $fecha_e = isset($mFecha[1])  ? trim($mFecha[1])  : 'Sin fecha';
                        $act_e   = isset($mAct[1])    ? trim($mAct[1])    : 'Sin descripción';
                        $resp_e  = isset($mResp[1])   ? trim($mResp[1])   : 'Desconocido';
                        $num     = count($entradas) - $i;
                    ?>
                    <li>
                        <div class="entry-num"><?= str_pad($num, 2, '0', STR_PAD_LEFT) ?></div>
                        <div class="entry-body">
                            <div class="entry-fecha">&#x25CF; <?= htmlspecialchars($fecha_e) ?></div>
                            <div class="entry-desc"><?= nl2br(htmlspecialchars($act_e)) ?></div>
                            <div class="entry-resp">RESPONSABLE: <span><?= htmlspecialchars($resp_e) ?></span></div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>

        </div>
    </div>

</main>
</body>
</html>