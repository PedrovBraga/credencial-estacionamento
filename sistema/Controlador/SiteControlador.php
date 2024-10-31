<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;

class SiteControlador extends Controlador
{

    public function __construct()
    {
        parent::__construct('views');
    }

    /**
     * Home Page
     * @return void
     */
    public function index(): void
    {
        echo $this->template->renderizar('pessoa.html', []);
    }
    
    public function buscar():void
    {
        // $busca = filter_input(INPUT_POST,'busca', FILTER_DEFAULT);
        // if(isset($busca)){
        //     $posts = (new PostModelo())->busca("status = 1 AND titulo LIKE '%{$busca}%'")->resultado(true);
            
        //     foreach ($posts as $post){
        //         echo "<li class='list-group-item fw-bold'><a href=".Helpers::url('post/').$post->id.">$post->titulo</a></li>";
        //     }
        // }
        
    }
    
    /**
     * Busca post por ID
     * @param int $id
     * @return void
     */
    public function post(string $slug):void
    {
        // $post = (new PostModelo())->buscaPorSlug($slug);
        // if(!$post){
        //     Helpers::redirecionar('404');
        // }

        // $post->visitas += 1;
        // $post->ultima_visita_em = date('Y-m-d H:i:s');
        // $post->salvar();
        
        // echo $this->template->renderizar('post.html', [
        //     'post' => $post,
        //     'categorias' => $this->categorias(),
        // ]);
    }
    
    /**
     * Categorias
     * @return array
     */
    // public function categorias(): array
    // {
    //     // return (new CategoriaModelo())->busca("status = 1")->resultado(true);
    // }

    public function categoria(string $slug):void
    {
        // $categoria = (new CategoriaModelo())->buscaPorSlug($slug);

        // if(!$categoria){
        //     Helpers::redirecionar('404');
        // }

        // $categoria->visitas += 1;
        // $categoria->ultima_visita_em = date('Y-m-d H:i:s');
        // $categoria->salvar();
        
        // echo $this->template->renderizar('categoria.html', [
        //     'posts' => (new CategoriaModelo())->posts($categoria->id),
        //     'categorias' => $this->categorias(),
        // ]);
    }
    
    /**
     * Sobre
     * @return void
     */
    public function sobre(): void
    {
        echo $this->template->renderizar('sobre.html', [
            'titulo' => 'Sobre nós'
        ]);
    }
    
    /**
     * ERRO 404
     * @return void
     */
    public function erro404(): void
    {
        echo $this->template->renderizar('404.html', [
            'titulo' => 'Página não encontrada'
        ]);
    }

}
