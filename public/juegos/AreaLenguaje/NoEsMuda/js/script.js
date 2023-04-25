const textosJson = [
  {
    "texto": "El ospital estaba lleno de pacientes enfermos. Los médicos y enfermeras trabajaban arduamente para ayudarlos.",
    "errores": ["ospital","enfermos", "enfermeras"]
  },
  {
    "texto": "La arina y el uevo son ingredientes necesarios para acer un pastel. También se puede agregar chocolate o vainilla.",
    "errores": ["arina", "uevo", "acer"]
  },
  {
    "texto": "El veículo se detuvo en la mitad de la carretera. El conductor hintentó hencenderlo pero la batería hestaba muerta.",
    "errores": ["veículo","hintentó","hencenderlo","hestaba"]
  },
  {
    "texto": "El ámster salió de su jaula para jugar en la abitación. Es un hanimal muy curioso y hactivo",
    "errores": ["ámster", "abitación", "hanimal", "hactivo"]
  },
  {
    "texto": "La istoria de la umanidad está llena de conflictos y guerras. A pesar de ello, también ha abido grandes logros y havances.",
    "errores": ["istoria", "umanidad", "abido", "havances"]
  },
  {
    "texto": "El ombre caminó por el bosque en busca de un lugar para hacampar. Llevaba consigo una tienda de campaña y halimentos.",
    "errores": ["ombre",  "hacampar",  "halimentos"]
  },
  {
    "texto": "La élice del havión giraba rápidamente. El piloto controlaba la velocidad y dirección del vuelo.",
    "errores": ["élice", "havión"]
  },
  {
    "texto": "El ermoso paisaje estaba lleno de colinas y montañas. Los hárboles y flores lo acían haún más bello.",
    "errores": ["ermoso",  "hárboles", "acían", "haún"]
  },
  {
    "texto": "El umo salía de la chimenea de la casa. Los niños jugaban hafuera mientras sus padres preparaban la cena.",
    "errores": ["umo", "hafuera"]
  },
  {
    "texto": "El uracán causó grandes daños en la ciudad. Muchas personas perdieron sus ogares y pertenencias.",
    "errores": ["uracán","ogares"]
  }
];

function iniciar() {
  let textAleatorio = Math.floor(Math.random() * textosJson.length);
  const texto = textosJson[textAleatorio].texto;
  const errores = textosJson[textAleatorio].errores;

  const palabras = texto.split(" ");
  const textoDiv = document.getElementById("parrafos");
  let nIntentos = 0;
  let tipomost;
  let palaEncontr = 0;
  let mensajeResul = ""; 

  let erroresContados = {};

  palabras.forEach((palabra) => {
    let tipo = "";
    if (errores.includes(palabra.toLowerCase().replace(".", ""))) {
      tipo = "errores";
      if (erroresContados[palabra]) {
        erroresContados[palabra] += 1;
      } else {
        erroresContados[palabra] = 1;
      }
    }

    if (tipo) {
      const elemento = document.createElement("span");
      elemento.innerText = palabra + " ";

      elemento.addEventListener("click", () => {
        nIntentos++;

        console.log(tipo);

        if (tipo === "errores") {
          erroresContados[palabra] += 1;
        }

        if ("errores" == tipo) {
          elemento.classList.add("bien");
          palaEncontr++;
          document.getElementById("sumAcierto").innerHTML = palaEncontr;
        } else {
          elemento.classList.add("error");
        }

        if (nIntentos == cantnombre) {
          $("#principal").fadeToggle(1000);
          $("#final").fadeToggle(1000);
          if (palaEncontr < cantnombre) {
            var audio = new Audio("../../sounds/game_over.mp3");
            audio.play();
            document.getElementById("final").style.backgroundImage =
              "url(../../images/derrota.gif)";
            mensajeResul =
              "Solo has coseguido identificar " + palaEncontr + " errores ortográficos de " + cantnombre;
          } else {
            document.getElementById("final").style.backgroundImage =
              "url(../../images/victoria.gif)";
            var audio = new Audio("../../sounds/victory.mp3");
            audio.play();
            mensajeResul =
              "Felicitaciones has logrado indentidicar todos los errores ortográficos";
          }

          document.getElementById("texto_final").innerText = mensajeResul;
        }
      });
      textoDiv.appendChild(elemento);
    } else {
      textoDiv.appendChild(document.createTextNode(palabra + " "));
    }
  });

  let cantnombre = 0;


    for (let clave in erroresContados) {
      cantnombre += erroresContados[clave];
    }
  
  document.getElementById("totPalabra").innerHTML = cantnombre + " errores ortográficos";

  // debugger;
}

//iniciar();

$(document).ready(function () {
  let audio2 = new Audio("../../sounds/fondo.mp3");
  audio2.play();
  audio2.volume = 0.2;

  iniciar();

  setTimeout(() => {
    $("#principal").fadeToggle(1000);
    $("#fondo_blanco").fadeToggle(3000);
    setTimeout(() => {
      const divAnimado = document.querySelector(".overlay");
      divAnimado.style.animationName = "moverDerecha";
      divAnimado.style.animationDirection = "normal";
      divAnimado.style.display = "block";
      setTimeout(() => {
        const divAnimado2 = document.querySelector(".nube");
        divAnimado2.style.animationName = "moverArriba";
        divAnimado2.style.animationDirection = "normal";
        divAnimado2.style.display = "block";
        setTimeout(() => {
          divAnimado.style.backgroundImage = "url(../../images/normal2.gif)";
          maquina2(
            "bienvenida",
            "Hola, soy Genio. <br> En este juego  debes identificar los Pronombres, Abjetivos y Sustantivos que se encuentran en el parrafo presentado.",
            100,
            1
          );
        }, 3000);
      }, 2000);
    });
  }, 200);
});

function maquina2(contenedor, texto, intervalo, n) {
  var i = 0,
    // Creamos el timer
    timer = setInterval(function () {
      if (i < texto.length) {
        // Si NO hemos llegado al final del texto..
        // Vamos añadiendo letra por letra y la _ al final.
        $("#" + contenedor).html(texto.substr(0, i++) + "_");
      } else {
        // En caso contrario..
        // Salimos del Timer y quitamos la barra baja (_)
        clearInterval(timer);
        $("#" + contenedor).html(texto);
        if (!cerrardo) {
          document.querySelector("#btnomitir").style.display = "none";
          setTimeout(() => {
            cerrar_anuncio();
          }, 3000);
        }
        // Auto invocamos la rutina n veces (0 para infinito)
        if (--n != 0) {
          setTimeout(function () {
            maquina2(contenedor, texto, intervalo, n);
          }, 3600);
        }
      }
    }, intervalo);
}

let cerrardo = false;
function cerrar_anuncio() {
  cerrardo = true;
  const divAnimado2 = document.querySelector(".nube");
  divAnimado2.style.animationName = "moverabajo";
  const divAnimado = document.querySelector(".overlay");
  divAnimado.style.backgroundImage = "url(../../images/normal1.gif)";
  $("#fondo_blanco").fadeToggle(3000);
  setTimeout(function () {
    divAnimado.style.animationName = "moverIzquierda";
    divAnimado.style.animationDirection = "normal";
    setTimeout(() => {
      $("#principal").fadeToggle(1000);
    }, 2000);
  }, 2000);
}
