<?php

namespace Dcampos\Generador\model;

use League\CommonMark\CommonMarkConverter;

class Generator{

    //Generamos las variables
    private string $title;
    private string $description;

    private array $authors;
    private array $authorLinks;

    private string $markdown;

    private CommonMarkConverter $converter;

    //Función que obtiene el valor
    public function __construct(array $options)
    {
        $this ->title = Generator::get($options, 'title');
        $this ->description = Generator::get($options, 'description');

        $this ->authors = Generator::get($options, 'authors');
        $this ->authorLinks = Generator::get($options, 'author_links');
        
        $this ->converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    //Función que genera el markdown en orden
    public function generate(){
        $this->markdown = '';
        $this->markdown .= $this->createMarkdown('title', $this->title);
        $this->markdown .= $this->createMarkdown('description', $this->description);
        $this->markdown .= $this->createMarkdown('authors', ['authors' => $this ->authors, 'links' => $this -> authorLinks]);
    }

    //Función que imprime el valor en pantalla
    private function createMarkdown($prop, $value){
        if(is_null($value) || $value == ''){
            return '';
        }

        switch($prop){
            case 'title':
                return "# {$value} \n";
            case 'description':
                return "{$value} \n";

            case 'authors':
                $mk = $this->processAuthors($value);
                return "{$mk} \n";
            default:
                return '';
        }
    }

    //Función que obtiene los valores de autor y links
    private function processAuthors(array $arr){
        $mk = "## Authors \n";
        $authors = $arr['authors'];
        $links = $arr['links'];

        for($i = 0; $i < count ($authors); $i++){
            $author = $authors[$i];
            $link = $links[$i];

            $mk .= "- [{$author}]({$link}) \n";
        }

        return $mk;
    }

    //Función para obtener el título
    public function getTitle(){
        return $this->title;
    }

    //Función para obtener la descrición
    public function getDescription(){
        return $this->description;
    }

    //Función para obtener el autor
    public function getAuthors(){
        $arr = [];
        $authors = $this->authors;
        $links = $this->authorLinks;

        for($i = 0; $i < count ($authors); $i++){
            $author = $authors[$i];
            $link = $links[$i];

            $item = ['author' => $author, 'link' => $link];
            array_push($arr, $item);
        }
        return $arr;
    }

    public function getMarkdown(){
        return nl2br($this->markdown);
    }

    public function getHTML(){
        return $this->converter->convert($this->markdown);
    }

    //Funcion que valida que sea un arreglo y que exista el indice para retornarlo
    public static function get($arr, $index){
        if(is_array($arr) && isset($arr[$index])){
            return $arr[$index];
        }else{
            return null;
        }
    }

    public static function getValue($obj, $getter){
        if(isset($obj)){
            return $obj->{$getter}();
        }else{
            return '';
        }
    }
}