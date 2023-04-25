<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
	<link rel="stylesheet" href="../assets/bootstrap/all.css">
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <script src="../assets/bootstrap/jquery.min.js"></script>
	<link rel="stylesheet" href="../fontawesome/css/all.min.css">
	<script src="../assets/bootstrap/sweetalert.js"></script>
    <style>

       
        p{
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 0px !important;
            height: 34px !important;
        }

        input {
            font-size: 30px;
            height: 37px !important;
            font-weight: bold;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        .error {
            background-color: #f5153e;
            color: #ffff;
        }

        .bien {
            background-color: #15f515;
            color: rgb(0, 0, 0);
        }

    </style>
</head>
<body>

    <div class="container" style="padding: 40px">
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
                <div class="row">
                    <div class="col-6" style="padding: 10px">
                        <p style="padding-left: 18px; letter-spacing: 27px;" id="dividendo_inicial"></p>
                    </div>
                    <div class="col-6" style="letter-spacing: 27px; padding: 10px ;border-bottom: 3px solid black ;border-left: 3px solid black;">
                        <p id="divisor_inicial"></p>
                    </div>
                </div>
                <div class="row">
                    <div id="operaciones" class="col-6"></div>
                    <div class="col-6">
                        <div id="cociente" style="margin-top: 10px" class="input-group"></div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                       <button type="button" onclick="verificar()" class="btn btn-success">Verificar</button>
                    </div>
                    <div class="col-3"></div>
                </div>
            </div>
            <div class="col-3"></div>
        </div>
    </div>

    <script>
        var operaciones_g = [];
        cociente_g = 0;
        function division(){
            let num1 = Math.round(Math.random()*100000);
            let num2 = Math.round(Math.random()*99);
           
            while(num1 <= num2 || num2 == 0){
                num1 = Math.round(Math.random()*100000);
                num2 = Math.round(Math.random()*99);
            }

            document.getElementById("dividendo_inicial").innerText = num1;
            document.getElementById("divisor_inicial").innerText = num2;

            console.log(num1+" / "+num2);

            let numero_divisiomn = 0;
            let bandera = true;
            let numero_op = 0;
            let cociente = "";
            let dividendo_aux = 0;
            let dividendo_digits_aux = [];
            var operaciones = [];

            while (bandera) {
                let dividendo = "";
                let divisor = num2;
                let dividendo_digits = [];
                let divisor_digits = num2.toString().split('').length;

                if(numero_op == 0){
                    dividendo_digits = num1.toString().split('');
                    let bandera2 = true;
                    let i = 0;
                    while(bandera2){
                        dividendo = dividendo+dividendo_digits[i]+""; 
                        if(parseInt(dividendo) > divisor){
                            bandera2 = false
                        }else{
                            i++;
                        }
                    }

                    for (let index = 0; index <= i ; index++) {
                        dividendo_digits.splice(0, 1);
                    }
                    
                    numero_op++;
                }else{
                    dividendo = dividendo_aux;
                    dividendo_digits = dividendo_digits_aux;
                }

                dividendo = parseInt(dividendo);
                
                let producto = 0;
                let residuo = 0;
                let operador = 9;
                console.log("-----------------------------------------------")
                for (let index = 9; index >= 1; index--) {
                    producto = divisor * index;
                    if(producto <= dividendo){
                        residuo = dividendo - producto;
                        operador = index;
                        cociente = cociente+""+operador;
                        console.log("operador: "+operador)
                        console.log(dividendo+" - "+producto+" = "+residuo)
                        break
                    }
                }

                if(dividendo_digits.length > 0){
                    let j = 0;
                    for (let index = 0; index < dividendo_digits.length; index++) {
                        dividendo  = parseInt(residuo+""+dividendo_digits.join('')[index]);
                        if(dividendo >= divisor){    
                            j++;
                            break;
                        }else{
                            cociente = cociente+"0";
                        }
                    }
                
                    for (let index = 0; index < j; index++) {
                        dividendo_digits.splice(0, 1);
                    }

                    residuo = dividendo;
                    console.log("residuo = "+residuo);
                }else{
                    console.log("residuo final = "+ residuo); 
                }

                operaciones.push({
                    num_2: producto,
                    num_3: residuo
                });
                

                dividendo_digits_aux = dividendo_digits;
                dividendo_aux = residuo;

                if(residuo < divisor){
                    bandera = false;
                    console.log("cociente = "+cociente);
                    console.log("_______________________________________________");
                    console.log(operaciones);
                    pintar(operaciones, cociente)
                }
            }
            
        }

        function pintar(operaciones, cociente){

            operaciones_g = operaciones;
            cociente_g = cociente;

            let padding_left = 0;
            let div_cociente = document.getElementById("cociente");

            cociente = cociente.split("");

            let o = 0;
            cociente.forEach(element => {
                let inputt = document.createElement("input");
                inputt.classList.add("cociente_d");
                inputt.style.marginRight = "10px";
                inputt.style.width = "30px";
                div_cociente.appendChild(inputt);
            });

            let div = document.getElementById("operaciones");
            let i = 0;
            operaciones.forEach(element => {
            
                let dp2 = document.createElement("div");
                dp2.classList.add("input-group")
                dp2.innerHTML ="<p> -  </p>"

                let n2 = element.num_2;
                n2 = n2.toString().split("");
                n2.forEach(element => {
                    let inputt = document.createElement("input");
                    inputt.classList.add("producto_"+i);
                    inputt.style.marginRight = "10px";
                    inputt.style.width = "30px";
                    inputt.setAttribute("max", "9")
                    dp2.appendChild(inputt);
                });

                if(i != 0){
                    dp2.style.paddingLeft = padding_left+20-1+"px";
                }
                
            

                let line = document.createElement("hr");
                line.style.paddingLeft = padding_left+"px";

                let dp3 = document.createElement("div");
                dp3.classList.add("input-group")

                let n3 = element.num_3;
                n3 = n3.toString().split("");
                n3.forEach(element => {
                    let inputt = document.createElement("input");
                    inputt.classList.add("resultado_"+i);
                    inputt.style.marginRight = "10px";
                    inputt.style.width = "30px";
                    inputt.setAttribute("max", "9")
                    dp3.appendChild(inputt);
                });
                
                dp3.style.paddingLeft = padding_left+55+"px";
                dp2.style.marginTop = "10px";
                
                
                div.appendChild(dp2);
                div.appendChild(line);
                div.appendChild(dp3);
                

                padding_left = padding_left + 25;

                i++;
            });
        }

        $(document).ready(function() {
            division();
        });

        function verificar(){
            for (let index = 0; index < operaciones_g.length; index++) {
                let producto_bien = operaciones_g[index].num_2.toString().split("");
                let producto = document.getElementsByClassName("producto_"+index);
                for (let index2 = 0; index2 < producto_bien.length; index2++) {
                    let element = parseInt(producto[index2].value);
                    let element2 = parseInt(producto_bien[index2]);
                    if(element == element2){
                        producto[index2].classList.add("bien")
                    }else{
                        producto[index2].classList.add("error")
                    }
                }                

                let resultado_bien = operaciones_g[index].num_3.toString().split("");
                var resultado = document.getElementsByClassName("resultado_"+index);
                for (let index2 = 0; index2 < resultado_bien.length; index2++) {
                    let element = parseInt(resultado[index2].value);
                    let element2 = parseInt(resultado_bien[index2]);
                    if(element == element2){
                        resultado[index2].classList.add("bien")
                    }else{
                        resultado[index2].classList.add("error")
                    }
                } 
                
                let cociente_bien = cociente_g.split("");
                var cociente = document.getElementsByClassName("cociente_d");
                for (let index2 = 0; index2 < cociente_bien.length; index2++) {
                    let element = parseInt(cociente[index2].value);
                    let element2 = parseInt(cociente_bien[index2]);
                    if(element == element2){
                        cociente[index2].classList.add("bien")
                    }else{
                        cociente[index2].classList.add("error")
                    }
                }  
            }
        }
        
    </script>
</body>
</html>