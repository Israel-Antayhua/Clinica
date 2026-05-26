function actualizarTiempo() {

  let horaCita = document.getElementById("horaCita")?.value;
  let fechaCita = document.getElementById("fechaCita")?.value;

  if (!horaCita || !fechaCita) {
    console.log("Faltan datos de fecha u hora");
    return;
  }

  let [h, m, s] = horaCita.split(":");

  // 🔥 FIX seguro de fecha (evita bugs del Date string)
  let [year, month, day] = fechaCita.split("-");

  let cita = new Date(
    parseInt(year),
    parseInt(month) - 1,
    parseInt(day),
    parseInt(h),
    parseInt(m),
    parseInt(s || 0)
  );

  let ahora = new Date();

  let diff = cita - ahora;

  if (diff <= 0) {
    document.getElementById("tiempoRestante").innerHTML = "En curso";
    return;
  }

  let dias = Math.floor(diff / (1000 * 60 * 60 * 24));
  let horas = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  let minutos = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

  let texto = "";

  if (dias > 0) texto += dias + "d ";
  texto += horas + "h " + minutos + "min";

  document.getElementById("tiempoRestante").innerHTML = texto;
}

actualizarTiempo();
setInterval(actualizarTiempo, 1000);