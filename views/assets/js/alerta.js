class Alerta {
    constructor() {
        this.titulo = '';
        this.texto = '';
        this.icon = '';
        this.showCancelButton = false;
        this.urlConfirm = null;
        this.urlCancel = null;
    }

    /**
     * Método responsável pelas mensagens de sucesso
     * @param {string} mensagem
     * @returns {Alerta}
     */
    sucesso(mensagem) {
        this.icon = 'success';
        this.titulo = 'Sucesso!';
        this.texto = this.filtrar(mensagem);
        return this;
    }

    /**
     * Método responsável pelas mensagens de erro
     * @param {string} mensagem
     * @returns {Alerta}
     */
    erro(mensagem) {
        this.icon = 'error';
        this.titulo = 'Erro!';
        this.texto = this.filtrar(mensagem);
        return this;
    }

    /**
     * Método responsável pelas mensagens de alerta
     * @param {string} mensagem
     * @returns {Alerta}
     */
    alerta(mensagem) {
        this.icon = 'warning';
        this.titulo = 'Atenção!';
        this.texto = this.filtrar(mensagem);
        return this;
    }

    /**
     * Método responsável pelas mensagens de informação
     * @param {string} mensagem
     * @returns {Alerta}
     */
    informa(mensagem) {
        this.icon = 'info';
        this.titulo = 'Atenção!';
        this.texto = this.filtrar(mensagem);
        return this;
    }

    /**
     * Método responsável pelas mensagens de questionamento
     * @param {string} mensagem
     * @returns {Alerta}
     */
    questiona(mensagem) {
        this.icon = 'question';
        this.titulo = 'Atenção!';
        this.texto = this.filtrar(mensagem);
        this.showCancelButton = true;
        return this;
    }

    /**
     * Método responsável por configurar ações para confirmação e cancelamento
     * @param {string} urlConfirmar
     * @param {string} urlCancelar
     * @returns {Alerta}
     */
    tratativa({ urlConfirmar = 'javascript:void(0);', urlCancelar = 'javascript:void(0);' } = {}) {
        this.urlConfirm = urlConfirmar;
        this.urlCancel = urlCancelar;
        this.showCancelButton = true;
        return this;
    }

    /**
     * Renderiza o alerta usando SweetAlert2
     */
    renderizar() {
        Swal.fire({
            title: this.titulo,
            text: this.texto,
            icon: this.icon,
            confirmButtonText: "Ok",
            cancelButtonText: "Cancelar",
            showCancelButton: this.showCancelButton,
        }).then((result) => {
            if (result.isConfirmed) {
                if (this.urlConfirm !== null) {
                    window.location.href = this.urlConfirm;
                }
            } else if (result.isDismissed) {
                if (this.urlCancel !== null) {
                    window.location.href = this.urlCancel;
                }
            }
        });
    }

    /**
     * Filtra o conteúdo da mensagem
     * @param {string} mensagem
     * @returns {string}
     */
    filtrar(mensagem) {
        const temp = document.createElement('div');
        temp.textContent = mensagem;
        return temp.innerHTML;
    }
}