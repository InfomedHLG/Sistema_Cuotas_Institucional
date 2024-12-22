<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de cuota de Infomed - Holguín</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/fonts.css">
    <script src="assets/js/tailwindcss.js"></script>
    <script src="assets/js/progress.js"></script>
</head>
<body>
    <div class="page-wrapper">
        <header class="main-header">
            <div class="header-container">
                <div class="logo-container">
                    <img src="assets/images/logoinfomed.png" alt="Logo Infomed" class="logo-image">
                </div>
                
                <div class="header-title">
                    <h1>Sistema de Cuotas</h1>
                    <p class="subtitle">Infomed Holguín</p>
                </div>
            </div>
        </header>

        <main class="main-content">
            <?php if ($error == '1'): ?>
                <div class="status-card">
                    <div class="institution-info">
                        <div class="flex justify-between items-center">
                            <div>


                            <h2 class="text-2xl font-bold text-gray-800 mb-1">
                            <?php echo $nombre_institucion; ?> 
                            </h2>
                            <p class="text-sm text-gray-600 italic font-semibold">
                            (IP: <?php echo $ip; ?>) 
                            </p>

                            </div>
                            <div class="flex items-center gap-2">
                               <a href="detalles.php?c=<?php echo generarCodigoSeguro($ip); ?>" class="inline-flex items-center px-3 py-1 text-sm text-blue-600 hover:text-blue-800 font-medium rounded hover:bg-blue-50 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver Detalles
                                </a>
                                <span id="next-update" class="text-xs text-gray-500 dark:text-gray-400"></span>
                            </div>
                        </div>
                    </div>

                    <div class="quota-info">
                    <div class="quota-text">
                    Usted no cuenta con cuota de internet, pero ha consumido 
                    <span class="quota" id="consumo-sin-cuota" style="font-weight: 700;"><?php echo $ConsumoUser; ?> <?php echo $unidadConsumoUser; ?></span>

                  </div>

                        <div class="progress-container">
                            <div class="progress-bar" 
                                style="width:<?php echo min($utilizacion, 100); ?>%; 
                                       background-color: <?php echo getColorByPercentage($utilizacion); ?>;
                                       border-radius: 10px;"
                                data-progress="<?php echo round($utilizacion, 1); ?>">
                            </div>
                        </div>

                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-label">Disponibilidad:</span>
                                <span class="stat-value" id="disponibilidad"><?php echo $disponibilidad; ?></span>
                            </div>

                            <div class="stat-item">
                                <span class="stat-label">Consumo:</span>
                                <span class="stat-value" id="consumo"><?php echo $ConsumoUser; ?> <?php echo $unidadConsumoUser; ?></span>
                            </div>


                        </div>


                    </div>
                </div>
            <?php endif; ?>

            <?php if ($error == '2'): ?>
                <div class="status-card">
                    <div class="institution-info">
                        <div class="flex justify-between items-center">
                        <div>
                        </div>
                        </div>
                    </div>

                    <div class="quota-info">
                    <div class="alert alert-danger" style="text-align: center; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            <div class="alert-content">
                                <i class="alert-icon" style="font-size: 3em; margin-bottom: 15px; display: block;">🚫</i>
                                <h3 style="font-size: 1.5em; margin-bottom: 15px; color: #d32f2f; font-weight: bold;">Servicio No Disponible</h3>
                                <p style="font-size: 1.1em; margin-bottom: 15px; color: #333;">
                                    Lo sentimos, su dirección IP <strong><em style="font-weight: 900; font-size: 1.3em;">(<?php echo $ip; ?>)</em></strong><br>
                                    no tiene acceso al servicio de Internet.
                                </p>
                                <div style="width: 50px; height: 2px; background: #d32f2f; margin: 20px auto;"></div>
                                <p style="font-size: 0.95em; color: #666; line-height: 1.5;">
                                    Si considera que esto es un error, <br> por favor contacte al<br> 
                                    administrador del sistema
                                    para obtener asistencia. <br>
                                </p>
                            </div>
                        </div>
                    </div>



                </div>
            <?php endif; ?>

            <?php if ($error == ''): ?>
                <div class="status-card">
                    <div class="institution-info">
                        <div class="flex justify-between items-center">
                            <div>

                            <h2 class="text-2xl font-bold text-gray-800 mb-1">
                            <?php echo $nombre_institucion; ?> 
                            </h2>
                            <p class="text-sm text-gray-600 italic font-semibold">
                            (IP: <?php echo $ip; ?>) 
                            </p>

                       
                            </div>
                            <div class="flex items-center gap-2">
                            <a href="d.php?c=<?php echo generarCodigoSeguro($ip); ?>" class="inline-flex items-center px-3 py-1 text-sm text-blue-600 hover:text-blue-800 font-medium rounded hover:bg-blue-50 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver Detalles
                                </a>
                                <span id="next-update" class="text-xs text-gray-500 dark:text-gray-400"></span>
                            </div>
                        </div>
                    </div>

                    <div class="quota-info">
                        <div class="quota-text">
                            Usted ha consumido el 
                            <span class="percentage" id="utilizacion"><?php echo round($utilizacion, 1); ?>%</span> 
                            de su cuota asignada de 
                            <span class="quota" id="cuota-asignada"><?php echo $CuotaAsignada; ?> <?php echo $unidadCuotaAsignada; ?></span>
                        </div>

                        <!-- Reemplaza la sección de la barra de progreso con esto -->
                        <div class="progress-container">
                            <div class="progress-bar" 
                                style="width:<?php echo min($utilizacion, 100); ?>%; 
                                       background-color: <?php echo getColorByPercentage($utilizacion); ?>;
                                       border-radius: 10px;"
                                data-progress="<?php echo round($utilizacion, 1); ?>">
                            </div>
                        </div>


                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-label">Disponibilidad:</span>
                                <span class="stat-value" id="disponibilidad"><?php echo $disponibilidad; ?></span>
                            </div>

                            <div class="stat-item">
                                <span class="stat-label">Consumo:</span>
                                <span class="stat-value" id="consumo"><?php echo $ConsumoUser; ?> <?php echo $unidadConsumoUser; ?></span>
                            </div>
                        </div>
                      
                    </div>
                </div>
            <?php endif; ?>


            <script src="assets/js/progress.js"></script>   

            </div>
        </main>

        <?php include 'footer.php'; ?>
    </div>
</body>
</html>