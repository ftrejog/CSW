<html>
<style type="text/css"> 
/*
      UNIR
      MATERIA:  COMPUTACION EN EL SERVIDOR WEB
      TRABAJO:  DESARROLLO WEB AVANZADO
      ALUMNO:   FELIPE TREJO GARCIA
      PROFESOR: MAESTRO OCTAVIO AGUIRRE LOZANO

*/
/*
    Edicion de los botones "COPIAR CODIGO" y "REGRESAR"
*/
input[type=button] {
      background-color:blue;
      border: none;
      color: white;
      padding: 16px 32px;
      text-decoration: none;
      margin: 4px 2px;
      cursor: pointer;
      text-align: center;
	} 
button[id=copyBlock] {
      background-color:red;
      border: none;
      color: white;
      padding: 16px 32px;
      text-decoration: none;
      margin: 4px 2px;
      cursor: pointer;
      text-align: center;
	} 
/* Centrar Encabezado y color de texto rojo*/
h1 {text-align:center;}	
</style>	
<body>    
<h1 style="color:red;">Codigo Generado</h1>	
<?php 
// Iniciar variables y arreglos
$NX = array( 0,0,0,0,0,0,0,0);	// Iniciar Array trisx, para hacer conversiones
//$NX = array( 0,1,0,1,0,1,0,1);	// Iniciar Array trisx, para hacer conversiones
$RX = array( "O","O","O","O","O","O","O","O");	// Iniciar Array RX string
// Iniciar valor trisx
$ValTrisX = 0;
$ValTrisA = 0;
$ValTrisB = 0;
$ValTrisC = 0;
$ValTrisD = 0;
$ValTrisE = 0;
$ValAnsel = 0;
$ValAnselH = 0;
// clase para la creacion  de los objetos renglones para imprimir codigo
class Renglon  
{
    protected $name, $valx; 
    function __construct($name, $valx)    
    {     
        $this -> name = $name;     
        $this -> valx = $valx;     
    }      
    //Setters    
    function set_name($name)
    {      
        $this->name = $name;    
    }    
    function set_valx($valx)
    {      
        $this->valx = $valx;    
    }    
    //Getters    
   function get_name()
    {      
        return $this->name;    
    }    
    function get_valx()
    {      
        return $this->valx;    
    }    
    //Metodos    
    function MkRow()    //Imprime la configuracio I/O
    {  	  
        echo "\nTris".$this -> name. " = 0x";
        echo strtoupper(dechex($this -> valx));
        echo ";  //Conf IO puerto ". $this -> name ;    
    }    

    function MkRowAn()    //Imprime la configuracion ADC
    {  	  
        if ($this -> name == "H")
        {
            echo "\nANSELH = 0x";
        }
        else 
        {
            echo "\nANSEL = 0x";
        }
        echo strtoupper(dechex($this -> valx));
        echo ";  //Conf. de valores ADC". $this -> name ;    
    }    

}

// funcion confuguracion de puertos de entrada/salida
// convierte un arreglo string en un arreglo numericos de "1"s y "0"s
function get_trisx($AX){
	for($i = 0; $i <8; $i++){
		if($AX[$i]=="O")
		{    
			$NX[$i] = 0;
		}
		else
		{    
			$NX[$i] = 1;
		}
	}
	return $NX;
}
// calcula el puerto y pasa sul valor a ValTrisX
// convierte el arraglo numerico de "1"s y "0"s en un valor numerico
function CalcPort($AX){
	$calc =0;
	$calc =($AX[0] * 1) + ($AX[1] * 2) + ($AX[2] * 4) + ($AX[3] * 8) + ($AX[4] * 16) + ($AX[5] * 32) + ($AX[6] * 64) + ($AX[7] * 128) ;
	return $calc;
}

// funcion calculo registro ADC low
// convierte las entradas elegidas como analogicas (A0..A7) a un valor adc low
// https://www.w3schools.com/php/php_looping_while.asp
function CalcAdcl($AX){
    $adc =0;
     $i = 0;
    while ($i < 8){   
        switch($AX[$i]){
            case "A0":
                $adc = $adc + 1;
                break;
            case "A1":
                $adc = $adc + 2;
                break;
            case "A2":
                $adc = $adc + 4;
                break;
            case "A3":
                $adc = $adc + 8;
                break;
            case "A4":
                $adc = $adc + 16;
                break;
            case "A5":
                $adc = $adc + 32;
                break;
            case "A6":
                $adc = $adc + 64;
                break;
            case "A7":
                $adc = $adc + 128;
                break;       
            }
    $i++;       
    }
    return $adc;
}

// funcion calculo registro ADC high
// convierte las entradas elegidas como analogicas (A8..A13) a un valor adc high
function CalcAdch($AX){
    $adc =0;
    //for($i = 0; $i <8; $i++){
    //https://www.w3schools.com/php/php_looping_foreach.asp    
    foreach($AX as $value){
        //switch($AX[$i]){
        switch($value){
            case "A8":
                $adc = $adc + 1;
                break;
            case "A9":
                $adc = $adc + 2;
                break;
            case "A10":
                $adc = $adc + 4;
                break;
            case "A11":
                $adc = $adc + 8;
                break;
            case "A12":
                $adc = $adc + 16;
                break;
            case "A13":
                $adc = $adc + 32;
                break;
            }
    }
    return $adc;
}

//CAPTURA VALOR DE PUERTO A y pasra a arreglo temporal RX[]
$RX[0]=$_POST["RA0"];
$RX[1]=$_POST["RA1"];
$RX[2]=$_POST["RA2"];
$RX[3]=$_POST["RA3"];
$RX[4]=$_POST["RA4"];
$RX[5]=$_POST["RA5"];
$RX[6]=$_POST["RA6"];
$RX[7]=$_POST["RA7"];
//valora el arreglo temporal rx y pasa el resultado al arreglo NX
$NX = get_trisx($RX);       //pasa el arreglo string a un arreglo num de "1"s y "0"s
$ValTrisA = CalcPort($NX);  //convierte el arreglo num de "1"s y "0"s a un valor numerico
$ValAnsel = 0;              //inicializa el valor ADC low
$ValAnsel = $ValAnsel + CalcAdcl($RX); //conv. entr. analogicas a un valor adc

//CAPTURAR VALOR DE PUERTO B y pasra a arreglo temporal RX[]
$RX[0]=$_POST["RB0"];
$RX[1]=$_POST["RB1"];
$RX[2]=$_POST["RB2"];
$RX[3]=$_POST["RB3"];
$RX[4]=$_POST["RB4"];
$RX[5]=$_POST["RB5"];
$RX[6]=$_POST["RB6"];
$RX[7]=$_POST["RB7"];
//valora el arreglo temporal rx y pasa el resultado al arreglo NX
$NX = get_trisx($RX);       //pasa el arreglo string a un arreglo num de "1"s y "0"s
$ValTrisB = CalcPort($NX);  //convierte el arreglo num de "1"s y "0"s a un valor numerico
$ValAnselH = 0;             //inicializa el valor ADC high
$ValAnselH = CalcAdch($RX); //conv. entr. analogicas a un valor adc
//CAPTURAR VALOR DE PUERTO C y pasra a arreglo temporal RX[]
$RX[0]=$_POST["RC0"];
$RX[1]=$_POST["RC1"];
$RX[2]=$_POST["RC2"];
$RX[3]=$_POST["RC3"];
$RX[4]=$_POST["RC4"];
$RX[5]=$_POST["RC5"];
$RX[6]=$_POST["RC6"];
$RX[7]=$_POST["RC7"];
//valora el arreglo temporal rx y pasa el resultado al arreglo NX
$NX = get_trisx($RX);       //pasa el arreglo string a un arreglo num de "1"s y "0"s
$ValTrisC = CalcPort($NX);  //convierte el arreglo num de "1"s y "0"s a un valor numerico
//TERMINA PUERTO C

//INICIA PUERTO D
//CAPTURAR VALOR DE PUERTO D y pasra a arreglo temporal RX[]
$RX[0]=$_POST["RD0"];
$RX[1]=$_POST["RD1"];
$RX[2]=$_POST["RD2"];
$RX[3]=$_POST["RD3"];
$RX[4]=$_POST["RD4"];
$RX[5]=$_POST["RD5"];
$RX[6]=$_POST["RD6"];
$RX[7]=$_POST["RD7"];
//valora el arreglo temporal rx y pasa el resultado al arreglo NX
$NX = get_trisx($RX);       //pasa el arreglo string a un arreglo num de "1"s y "0"s
$ValTrisD = CalcPort($NX);  //convierte el arreglo num de "1"s y "0"s a un valor numerico
//TERMINA PUERTO D

//INICIA PUERTO E
//CAPTURAR VALOR DE PUERTO E y pasra a arreglo temporal RX[]
$RX[0]=$_POST["RE0"];
$RX[1]=$_POST["RE1"];
$RX[2]=$_POST["RE2"];
$RX[3]=$_POST["RE3"];
$RX[4]=$_POST["RE4"];
$RX[5]=$_POST["RE5"];
$RX[6]=$_POST["RE6"];
$RX[7]=$_POST["RE7"];
//valora el arreglo temporal rx y pasa el resultado al arreglo NX
$NX = get_trisx($RX);       //pasa el arreglo string a un arreglo num de "1"s y "0"s
$ValTrisE = CalcPort($NX);  //convierte el arreglo num de "1"s y "0"s a un valor numerico
$ValAnsel = $ValAnsel + CalcAdcl($RX); //conv. entr. analogicas a un valor adc
//TERMINA PUERTO E

// codigo de las propiedades del area de texto para imprimir el resultado
?>
<div align = "center">
<textarea id="textarea" rows="10" cols="50" style="font-size:140%;">
<?php  
echo "\n//CONFIG. PUERTOS I/O PIC16F887 ";
// IMPRIME VALORES DE TRISA
// https://www.php.net/manual/en/function.strtoupper.php
$rw1 = new Renglon("A", $ValTrisA); //Crea objeto rw1 con clase Renglon atributos puerto A
$rw1->MkRow();                      //Imprime con el metodo MkRow, de la clase Renglon
// IMPRIME VALORES DE TRISB
$rw2 = new Renglon("B", $ValTrisB); //Crea objeto rw2 con clase Renglon atributos puerto B
$rw2->MkRow();                      //Imprime con el metodo MkRow, de la clase Renglon
// IMPRIME VALORES DE TRISC
$rw3 = new Renglon("C", $ValTrisC); //Crea objeto rw3 con clase Renglon atributos puerto C
$rw3->MkRow();                      //Imprime con el metodo MkRow, de la clase Renglon
// IMPRIME VALORES DE TRISD
$rw4 = new Renglon("D", $ValTrisD); //Crea objeto rw4 con clase Renglon atributos puerto D
$rw4->MkRow();                      //Imprime con el metodo MkRow, de la clase Renglon
// IMPRIME VALORES DE TRISE
$rw5 = new Renglon("E", $ValTrisE); //Crea objeto rw5 con clase Renglon atributos puerto E
$rw5->MkRow();                      //Imprime con el metodo MkRow, de la clase Renglon
// IMPRIME VALORES DE CONF ADC LOW
echo "\n//CONFIG. COVERTIDOR ADC ";
$rwL = new Renglon("L", $ValAnsel); //Crea objeto rwL con clase Renglon atributos de ADC low
$rwL->MkRowAn();                    //Imprime con el metodo MkRowAn, de la clase Renglon
$rwH = new Renglon("H", $ValAnselH);//Crea objeto rwL con clase Renglon atributos de ADC hig
$rwH->MkRowAn();                    //Imprime con el metodo MkRowAn, de la clase Renglon

?>
</textarea>
<!-- Creacion de botones "COPIAR CODIGO" y "REGRESAR"  -->
<br><br>
<button id="copyBlock">COPIAR CODIGO</button> <span id="copyAnswer"></span>
<!-- <button onclick="copyToClipboard('#textarea')"> Copiar codigo -->
</button>
<!-- Boton para volver atras-->
<input type="button" onclick="history.back()" name="volver atrás" value="REGRESAR"> 
</div>
</body>
<!-- Script para copiar el texto de un textarea-->
<script language="JavaScript">
	//https://unipython.com/copiar-un-texto-al-portapapeles-en-java-script/#:~:text=C%C3%B3mo%20copiar%20un%20texto%20al,texto%20del%20textarea%20al%20portapapeles.
    // Establecemos las variables
    var textarea = document.getElementById("textarea");
    var answer = document.getElementById("copyAnswer");
    var copy   = document.getElementById("copyBlock");
    copy.addEventListener('click', function(e) {
       // Sleccionando el texto
       textarea.select(); 
       try {
           // Copiando el texto seleccionado
           var successful = document.execCommand('copy');
     
           if(successful) answer.innerHTML = 'Copiado!';
           else answer.innerHTML = 'Incapaz de copiar!';
       } catch (err) {
           answer.innerHTML = 'Browser no soportado!';
       }
    });
</script>
</html>