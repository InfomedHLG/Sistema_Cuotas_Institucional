function blendColors(color1, color2, ratio) {
    return color1.map((c, i) => Math.round(c + ratio * (color2[i] - c)));
}

function getColorByPercentage(percentage) {
    const colorStart = [76, 175, 80];    // Verde
    const colorMiddle = [255, 235, 59];  // Amarillo
    const colorEnd = [244, 67, 54];      // Rojo

    let color;
    if (percentage >= 100) {
        color = colorEnd;
    } else if (percentage <= 50) {
        const ratio = percentage / 50;
        color = blendColors(colorStart, colorMiddle, ratio);
    } else {
        const ratio = (percentage - 50) / 50;
        color = blendColors(colorMiddle, colorEnd, ratio);
    }

    return `rgb(${color.join(",")})`;
}

function updateProgress() {
    fetch('get_data.php')
        .then(response => response.json())
        .then(data => {
            // Elementos comunes
            const disponibilidad = document.getElementById('disponibilidad');
            const consumo = document.getElementById('consumo');
            const consumoSinCuota = document.getElementById('consumo-sin-cuota');

            if (data.error === '1') {
                // Actualización para caso sin cuota
                if (consumoSinCuota) consumoSinCuota.textContent = data.ConsumoUser;
                if (disponibilidad) disponibilidad.textContent = data.disponibilidad;
                if (consumo) consumo.textContent = data.ConsumoUser;
            } else if (data.error === '') {
                // Actualización para caso con cuota
                const utilizacion = document.getElementById('utilizacion');
                const cuotaAsignada = document.getElementById('cuota-asignada');
                const progressBar = document.querySelector('.progress-bar');

                if (utilizacion) utilizacion.textContent = data.utilizacion + '%';
                if (cuotaAsignada) cuotaAsignada.textContent = data.CuotaAsignada;
                if (disponibilidad) disponibilidad.textContent = data.disponibilidad;
                if (consumo) consumo.textContent = data.ConsumoUser;
                if (progressBar) {
                    progressBar.style.width = Math.min(data.utilizacion, 100) + '%';
                    progressBar.style.backgroundColor = getColorByPercentage(data.utilizacion);
                }
            }
        })
        .catch(error => console.error('Error al obtener los datos:', error));
}

document.addEventListener('DOMContentLoaded', function() {
    // Primera actualización inmediata
    updateProgress();

    // Configurar el intervalo de actualización
    const intervalId = setInterval(updateProgress, 5000);

    // Limpiar el intervalo cuando se descarga la página
    window.addEventListener('unload', () => clearInterval(intervalId));
});