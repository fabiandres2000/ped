let conse = 0;
let respuestaPreg = "";
let letraActual = "";

const atoms = document.querySelectorAll(".atom");

atoms.forEach((bin) => {
  bin.addEventListener("dragstart", dragStart);
  bin.addEventListener("dragend", dragEnd);
});

function dragStart(e) {
  if (isTouchDevice()) {
    initialX = e.touches[0].clientX;
    initialY = e.touches[0].clientY;
    //Start movement for touch
    moveElement = true;
    currentElement = e.target;
  } else {
    //For non touch devices set data to be transfered
    e.dataTransfer.setData("text", e.target.id);
  }
}

function dragEnd() {
  this.classList.remove("dragging");
}

function dragOver(e) {
  e.preventDefault();
}

function dragEnter(e) {
  e.preventDefault();
  this.classList.add("over");
}

function dragLeave() {
  this.classList.remove("over");
}

let cont = 0;

const drop = (e) => {
  e.preventDefault();
  //For touch screen
  if (isTouchDevice()) {
    moveElement = false;
    //Select country name div using the custom attribute
    const pos = currentElement.getBoundingClientRect();
    const currentDrop = document.elementsFromPoint(pos.left, pos.top);

    let id1 = currentElement.getAttribute("data-id");
    let id2 = currentDrop[1].getAttribute("data-id");
    if (id1 == id2) {
      currentDrop[1].classList.add("dropped");
      //hide actual image
      currentElement.classList.add("hide");
      currentDrop[1].innerHTML = ``;
      //Insert new img element
      currentDrop[1].insertAdjacentHTML(
        "afterbegin",
        `<img class='img_drag' style='height: 85pt; width: 80%' src="img/${id1}/${currentElement.id.split("_")[1]}.png">`
      );
      correctas++;
      cont++;
    } else {
      currentDrop[1].classList.add("error");
      //hide actual image
      currentElement.classList.add("hide");
      currentDrop[1].innerHTML = ``;
      //Insert new img element
      currentDrop[1].insertAdjacentHTML(
        "afterbegin",
        `<img class='img_drag' style='height: 85pt; width: 80%' src="img/${id1}/${currentElement.id.split("_")[1]}.png">`
      );

      cont++;
    }
  } else {
    //Access data
    const draggedElementData = e.dataTransfer.getData("text");
    //Get custom attribute value
    const droppableElementData = e.target.getAttribute("data-id");
    const draggedElement = document.getElementById(draggedElementData);
    let imagen_id = draggedElement.getAttribute("data-id");

    if (droppableElementData === imagen_id) {
      draggedElement.classList.add("hide");
      e.target.innerHTML = "";
      e.target.classList.add("dropped");
      e.target.insertAdjacentHTML(
        "afterbegin",
        `<img data-id='${imagen_id}' class='img_drag' style='height: 95%; width: 95%' src="img/${imagen_id}.png">`
      );
      var audio = new Audio("../../sounds/ok.mp3");
      audio.play();
    } else {
      e.target.innerHTML = "";
      draggedElement.classList.add("hide");
      var audio = new Audio("../../sounds/over.mp3");
      audio.play();
      e.target.classList.add("error");
      e.target.insertAdjacentHTML(
        "afterbegin",
        `<img data-id='${imagen_id}' class='img_drag' style='height: 95%; width: 95%' src="img/${imagen_id}.png">`
      );
    }
    cont++;
   
  }
  if (cont == numero_elementos) {
    let elementos_cajon = document.getElementsByClassName("cajon");
    let corerctas2=0;
    for (let index = 0; index < elementos_cajon.length; index++) {
      const element = elementos_cajon[index];
      if(element.getAttribute("data-id") == element.firstChild.getAttribute("data-id")){
        element.style.backgroundColor = "green";
        corerctas2++;
      }else{
        element.style.backgroundColor = "red";
      }

    }

    if(corerctas2==numero_elementos){
      correctas++;
    }
    setTimeout(() => {
      document.getElementById("molecule").innerHTML = "";
      cont=0;
      amarMolecula();
    }, 3000)

  }
};

const isTouchDevice = () => {
  try {
    //We try to create Touch Event (It would fail for desktops and throw error)
    document.createEvent("TouchEvent");
    deviceType = "touch";
    return true;
  } catch (e) {
    deviceType = "mouse";
    return false;
  }
};


var molecula_el = null;
var pregunta_actual = 0;
let correctas = 0;
let actual;

let numero_elementos = 0;



function amarMolecula() {
console.log(pregunta_actual);
  if (pregunta_actual < 5) {
    molecula_el = moleculas_array[pregunta_actual];
    // Obtener el contenedor de la molécula
    var molecula = document.getElementById("molecule");
    document.getElementById("tipo_mol").innerText = molecula_el.molecula;
    // Iterar sobre los elementos de la molécula
    numero_elementos = molecula_el.elementos.length;
    molecula_el.elementos.forEach(function (elemento) {
      // Crear el elemento div
      var div = document.createElement("div");
      div.setAttribute("data-id", elemento.nombre)

      // Asignar el simbolo del elemento como clase para aplicar los estilos
      div.className = elemento.nombre;
      div.classList.add("cajon");

      // Asignar las coordenadas y los estilos
      div.style.position = "absolute";
      div.style.left = elemento.coordenadas.x + "px";
      div.style.top = elemento.coordenadas.y + "px";
      div.style.width = elemento.ancho + "px";
      div.style.height = elemento.ancho + "px";
      div.style.borderRadius = "50%";
      div.style.border = "1px solid black";



      div.addEventListener("dragover", dragOver);
      div.addEventListener("dragenter", dragEnter);
      div.addEventListener("dragleave", dragLeave);
      div.addEventListener("drop", drop);

      molecula.appendChild(div);

    });
    pregunta_actual++;
  } else {
    $('#principal').fadeToggle(500);
    setTimeout(() => {
      $('#final').fadeToggle(1000);
    }, 500)
    if (correctas < 3) {
      document.getElementById("final").style.backgroundImage = "url(../../images/derrota.gif)";
    } else {
      document.getElementById("final").style.backgroundImage = "url(../../images/victoria.gif)";
    }

    document.getElementById("texto_final").innerText = "Has logrado armar correctamente " + correctas + " moléculas.";

    if (correctas >= 3) {
      var audio = new Audio('../../sounds/victory.mp3');
      audio.play();
    } else {
      var audio = new Audio('../../sounds/game_over.mp3');
      audio.play();
    }
  }
}

// Declaración de variables
let oxygen = {
  name: "oxigeno",
  image: "img/oxigeno.png",
};
let nitrogen = {
  name: "nitrogeno",
  image: "img/nitrogeno.png",
};
let carbon = {
  name: "carbono",
  image: "img/carbono.png",
};
let hidrogeno = {
  name: "hidrogeno",
  image: "img/hidrogeno.png",
};
let currentMolecule = [];
let score = 0;
let timer;

let atomNitrogeno = 0;
let atomOxigeno = 0;
let atomHidrogeno = 0;
let atomCarbono = 0;
let moleculeDiv = document.getElementById("molecule");

// Función para iniciar el juego
function startGame() {
  // Configuración inicial
  inicioTiempo();
  score = 0;
  amarMolecula();

  currentMolecule = [];


  let seconds = 0;
}

function readText(ruta_local) {
  var texto = null;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("GET", ruta_local, false);
  xmlhttp.send();
  if (xmlhttp.status == 200) {
    texto = xmlhttp.responseText;
  }
  return texto;
}

function randomValueGenerator(vector) {
  return vector.sort(function () { return Math.random() - 0.5 });
};


let moleculas_array = [];

$(document).ready(function () {

  let base_preguntas = readText("moleculas.json");
  moleculas_array = JSON.parse(base_preguntas);
  moleculas_array = randomValueGenerator(moleculas_array);

  setTimeout(function () {
    let audio2 = new Audio("../../sounds/enunciado_vocales_1.mp3");
    audio2.play();
  }, 100);

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
            "Hola, soy Genio. <br> Se presentara el nombre de la molécula que tienes que construir, para construirla tendras que arrastra  los átomos que componen la molécula.",
            50,
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
      startGame();
    }, 2000);
  }, 2000);
}

function inicioTiempo() {
  // Obtenemos el elemento del contador
  var contador = document.getElementById("contador");

  // Convertimos 3 minutos a milisegundos
  var tiempoLimite = 5 * 60 * 1000;

  // Obtenemos la hora actual
  var horaActual = new Date().getTime();

  // Establecemos la hora límite
  var horaLimite = horaActual + tiempoLimite;

  // Actualizamos el contador cada segundo
  var intervalo = setInterval(function () {
    // Obtenemos la hora actual
    var horaActual = new Date().getTime();

    // Calculamos la distancia entre la hora límite y la hora actual
    var distancia = horaLimite - horaActual;

    // Calculamos los minutos y segundos restantes
    var minutos = Math.floor((distancia % (1000 * 60 * 60)) / (1000 * 60));
    var segundos = Math.floor((distancia % (1000 * 60)) / 1000);

    // Mostramos el contador en formato mm:ss
    contador.innerHTML =
      "Tiempo restante: " +
      (minutos < 10 ? "0" : "") +
      minutos +
      ":" +
      (segundos < 10 ? "0" : "") +
      segundos;

    // Si el tiempo ha expirado, detenemos el intervalo y mostramos un mensaje
    if (distancia < 0) {
      clearInterval(intervalo);
      contador.innerHTML = "00:00";
      resultadoFinal();
    }
  }, 1000);
}

function resultadoFinal() {
  $("#principal").fadeToggle(1000);
  $("#final").fadeToggle(1000);

  let npreg = conse;
  let prom = npreg / 2;

  if (score <= prom) {
    var audio = new Audio("../../sounds/game_over.mp3");
    audio.play();
    document.getElementById("final").style.backgroundImage =
      "url(../../images/derrota.gif)";
  } else {
    document.getElementById("final").style.backgroundImage =
      "url(../../images/victoria.gif)";
    var audio = new Audio("../../sounds/victory.mp3");
    audio.play();
  }
  document.getElementById("texto_final").innerText =
    "Lograste armar correctamente " + score + " moléculas de " + npreg;
}
