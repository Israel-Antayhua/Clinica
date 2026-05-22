function actualizarTiempo() {
  let horaCita = document.getElementById("horaCita").value;

  let [h, m, s] = horaCita.split(":");

  let ahora = new Date();

  let cita = new Date(
    ahora.getFullYear(),
    ahora.getMonth(),
    ahora.getDate(),
    parseInt(h),
    parseInt(m),
    parseInt(s || 0),
  );

  let diff = cita.getTime() - ahora.getTime();

  if (diff <= 0) {
    document.getElementById("tiempoRestante").innerHTML = "En curso";

    return;
  }

  let horas = Math.floor(diff / 3600000);

  let minutos = Math.floor((diff % 3600000) / 60000);

  document.getElementById("tiempoRestante").innerHTML =
    horas + "h " + minutos + "min";
}

actualizarTiempo();

setInterval(actualizarTiempo, 1000);
