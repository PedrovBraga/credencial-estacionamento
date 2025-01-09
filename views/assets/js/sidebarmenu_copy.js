/*
Template Name: Admin Template
Author: Wrappixel

File: js
*/
// ============================================================== 
// Auto select left navbar
// ============================================================== 
$(function() {
    "use strict";
     var url = window.location + "";
        var path = url.replace(window.location.protocol + "//" + window.location.host + "/", "");
        var element = $('ul#sidebarnav a').filter(function() {
            return this.href === url || this.href === path;// || url.href.indexOf(this.href) === 0;
        });
        element.parentsUntil(".sidebar-nav").each(function (index)
        {
            if($(this).is("li") && $(this).children("a").length !== 0)
            {
                $(this).children("a").addClass("active");
                $(this).parent("ul#sidebarnav").length === 0
                    ? $(this).addClass("active")
                    : $(this).addClass("selected");
            }
            else if(!$(this).is("ul") && $(this).children("a").length === 0)
            {
                $(this).addClass("selected");
                
            }
            else if($(this).is("ul")){
                $(this).addClass('in');
            }
            
        });

    // Garante que os submenus iniciem desativados
    $('#sidebarnav ul').removeClass('in');
    $('#sidebarnav a').removeClass('active');

    $('#sidebarnav a').on('click', function (e) {
        
            if (!$(this).hasClass("active")) {
                // hide any open menus and remove all other classes
                $("ul", $(this).parents("ul:first")).removeClass("in");
                $("a", $(this).parents("ul:first")).removeClass("active");
                
                // open our new menu and add the open class
                $(this).next("ul").addClass("in");
                $(this).addClass("active");
                
            }
            else if ($(this).hasClass("active")) {
                $(this).removeClass("active");
                $(this).parents("ul:first").removeClass("active");
                $(this).next("ul").removeClass("in");
            }
    })
    $('#sidebarnav >li >a.has-arrow').on('click', function (e) {
        e.preventDefault();
    });

/* ALTERAÇÕES AO ATIVAR E DESATIVAR MENU */
    
    const logo = document.getElementById("logo-img");
    
    // Realiza as alterações no menu
    function alteraMenu(target){

        // Verifique se o clique não está dentro do menu
        if (!target.closest('.left-sidebar').length) {
            // Remova as classes "active" e "in" do menu
            $('#sidebarnav ul').removeClass('in');
            $('#sidebarnav a').removeClass('active');

            // Altera logo para mini
            logo.src = "../../assets/images/vivavida-mini.png";
        } else logo.src = "../../assets/images/vivavida.png";
    }

    // 
    function verificaMenu(novoValorSidebarType){
        if(novoValorSidebarType === "mini-sidebar"){
            //verifica se tela é tamanho mobile
            if(window.innerWidth < 770){
                logo.src = "../../assets/images/vivavida-mini.png";
                // Escuta clique no body para manipular barra lateral
                $(document.body).on('click', function (e) {
                    var target = $(e.target);
                    alteraMenu(target);
                });
            } else {
                logo.src = "../../assets/images/vivavida-mini.png";
                // Escuta hover do mouse no body para manipular barra lateral
                $(document.body).on('mouseover', function (e) {
                    var target = $(e.target);
                    alteraMenu(target);
                });
            }
        } else logo.src = "../../assets/images/vivavida.png";
    }

    // elemento que registra tipo do menu
    var conditionalElement = document.getElementById("main-wrapper");

    // função para verificar alteração do tipo do menu
    var observer = new MutationObserver(function(mutationsList) {
    for (var mutation of mutationsList) {
        // verifica na lista de mutações se é a mutação de menu
        if (mutation.type === 'attributes' && mutation.attributeName === 'data-sidebartype') {
            var newSidebartypeValue = mutation.target.getAttribute('data-sidebartype');
            verificaMenu(newSidebartypeValue);
        }
    }
    });

    // executa observer
    observer.observe(conditionalElement, { attributes: true, attributeFilter: ['data-sidebartype'] });
   

});

