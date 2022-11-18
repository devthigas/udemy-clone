<?php 

function show($stuff){
  echo "<pre>";
  print_r($stuff);
  echo "<pre>";
}


class App
{

  protected $controller = "_404"; 
  protected $method = "index"; 

  function __construct() // metodo construtor 
  {
    // #2 
    $arr = $this->getURL(); // retorno da função privada
    $filename = "../app/controllers/".ucfirst($arr[0]).".php"; // string de caminho do arquivo
    // #3 
    if(file_exists($filename)) // verificando se o arquivo existe
    {
      require $filename; // se existe importa o arquivo 
      $this->controller = $arr[0];  // e adiciona o nome na variavel controller 
      unset($arr[0]); // limpa na posição 0 
    }
    else
    {
      require "../app/controllers/".$this->controller.".php"; // importa o arquivo 404 já definido com nome na variavel. 
    }

    # 4 
    $mymethod = $arr[1] ?? $this->method; // recebe o metodo 

    $mycontroller = new $this->controller(); // cria objeto 
    // verifica se o array é vazio e o metodo existe
    if(!empty($arr[1]))
    {
      if(method_exists($mycontroller, strtolower($mymethod))){
        $this->method = strtolower($mymethod); 
        unset($arr[1]); 
      };
    } // cria um novo objeto
    
    $arr = array_values($arr); //limpeza index do array 
    // função que executa os metodos 
    call_user_func_array([$mycontroller, $this->method], $arr); 
    show($arr); 

  }

   #1 
  private function getURL()
  {
    $url = $_GET['url'] ?? "home"; // se vazio adiciona o home
    $url = filter_var($url, FILTER_SANITIZE_URL); // filtro para impeditr XSS
    $arr_url = explode("/", $url); // separar string pela "/"
    return $arr_url; // retorno da função
  }

}

$app = new App();
?>