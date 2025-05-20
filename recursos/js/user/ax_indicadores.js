document.addEventListener("DOMContentLoaded", function () {
    // Obtener los datos desde el <script> con el ID "datosEstadisticos"
    const rawData = document.getElementById("datosEstadisticos").textContent.trim();

    try {
        // Parsear el JSON
        const datos = JSON.parse(rawData);
        console.log("Datos cargados:", datos);  // Verifica si los datos están correctamente cargados en la consola

        // Verificar que los datos no estén vacíos
        if (!Array.isArray(datos) || datos.length === 0) {
            console.warn("No hay datos disponibles para graficar.");
            return;
        }

        // Extraer las etiquetas (periodos) y los valores (visitas, contactos)
        const etiquetas = datos.map(item => item.periodo);
        const visitas = datos.map(item => parseInt(item.total_visitas, 10));
        const contactos = datos.map(item => parseInt(item.total_contactos, 10));

        // Insertar los datos en la tabla (si es necesario)
        const tablaDatos = document.getElementById("tablaDatos");
        datos.forEach(item => {
            tablaDatos.innerHTML += `
                <tr>
                    <td>${item.periodo}</td>
                    <td>${item.total_visitas}</td>
                    <td>${item.total_contactos}</td>
                </tr>`;
        });

        // Crear el gráfico con Chart.js
        const ctx = document.getElementById('graficoTrafico').getContext('2d');
        new Chart(ctx, {
            type: 'line',  // Tipo de gráfico: línea
            data: {
                labels: etiquetas,  // Los periodos (eje X)
                datasets: [
                    {
                        label: 'Visitas',
                        data: visitas,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'Contactos',
                        data: contactos,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    } catch (error) {
        console.error("Error al procesar el JSON:", error);
        console.log("Datos recibidos:", rawData);  // Ver los datos crudos en la consola
    }
});
