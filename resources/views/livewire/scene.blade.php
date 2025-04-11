<div>
    <div class="AddPainel">
        <button id="addPanel" wire:click="create">Add painel</button>
    </div>
    <!-- MENU LATERAL -->
    <div class="menu-lateral">
        <div>
            <!-- Quando um painel está selecionado -->
            <div class="menu-opcoes painel-opcoes">
                <!-- FORMATOS -->
                <div class="mb-6">
                    <h3>
                        FORMATO DOS BOTÕES
                    </h3>
                    <div class="tipos">
                        <div class="linhas">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div class="blocos">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div class="alternativas">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                </div>
                <!-- BLOCO DE TEXTO -->
                <div id="blocoTxt">
                    <div id="trumbowyg-demo" placeholder="Insira seu texto aqui"></div>
                </div>
                <!-- BOTÕES DE SALVAR/EXCLUIR -->
                <div class="buttons">
                    <button>
                        Editar Mídia
                    </button>
                    <button>
                        Excluir
                    </button>
                </div>
            </div>

            <!-- Quando o botão está selecionado -->
            <div class="menu-opcoes botao-opcoes">
                <!-- CORES -->
                <div class="mb-6">
                    <h3 class="mb-2">
                        CORES DOS BOTÕES
                    </h3>
                    <div class="mb-2">
                        <div id="color-picker-container"></div>
                    </div>
                </div>
                <!-- TEXTO DO BOTÃO -->
                <div class="mb-6">
                    <h3 class="mb-2">
                        TEXTO DO BOTÃO
                    </h3>
                    <input class="" type="text" />
                </div>
                <!-- TRANSIÇÕES -->
                <div class="mb-6">
                    <h3 class="mb-2">TRANSIÇÕES</h3>
                    <div class="select select-transicoes">
                        <div class="selected" data-default="Nenhuma" data-one="Final da experiência" data-two="Próximo painel">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="arrow">
                                <path
                                    d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z">
                                </path>
                            </svg>
                        </div>
                        <div class="options">
                            <div>
                                <input id="nenhuma" name="option-transicoes" type="radio" checked />
                                <label class="option" for="nenhuma" data-txt="Nenhuma"></label>
                            </div>
                            <div>
                                <input id="final-experiencia" name="option-transicoes" type="radio" />
                                <label class="option" for="final-experiencia" data-txt="Final da experiência"></label>
                            </div>
                            <div>
                                <input id="proximo-painel" name="option-transicoes" type="radio" />
                                <label class="option" for="proximo-painel" data-txt="Próximo painel"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SELECIONAR O PAINEL -->
                <div class="mb-6">
                    <h3 class="mb-2 singleTap">
                        SELECIONAR PAINEL
                        <img src="{{ asset('images/singletap.svg') }}" alt="Ícone">
                    </h3>
                    <div class="select select-painel">
                        <div class="selected" data-default="Painel (nº ID)" data-one="Painel 1" data-two="Painel 2"
                            data-three="Painel 3">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="arrow">
                                <path
                                    d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z">
                                </path>
                            </svg>
                        </div>
                        <div class="options">
                            <div>
                                <input id="painel-1" name="option-painel" type="radio" checked />
                                <label class="option" for="painel-1" data-txt="Painel 1"></label>
                            </div>
                            <div>
                                <input id="painel-2" name="option-painel" type="radio" />
                                <label class="option" for="painel-2" data-txt="Painel 2"></label>
                            </div>
                            <div>
                                <input id="painel-3" name="option-painel" type="radio" />
                                <label class="option" for="painel-3" data-txt="Painel 3"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quando o canvas está "selecionado" -->
            <div class="menu-opcoes canvas-opcoes">
                <!-- NOME DA CENA -->
                <div class="mb-6">
                    <h3 class="mb-2">
                        NOME DA CENA
                    </h3>
                    <input class="" type="text" />
                </div>
                <!-- SELECIONAR O PAINEL INICIAL-->
                <div class="mb-6">
                    <h3 class="mb-2 singleTap">
                        SELECIONAR PAINEL INICIAL
                        <img src="{{ asset('images/singletap.svg') }}" alt="Ícone">
                    </h3>
                    <div class="select select-painel">
                        <div class="selected" data-default="Painel (nº ID)" data-one="Painel 1" data-two="Painel 2"
                            data-three="Painel 3">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="arrow">
                                <path
                                    d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z">
                                </path>
                            </svg>
                        </div>
                        <div class="options">
                            <div>
                                <input id="painel-1" name="option-painel" type="radio" checked />
                                <label class="option" for="painel-1" data-txt="Painel 1"></label>
                            </div>
                            <div>
                                <input id="painel-2" name="option-painel" type="radio" />
                                <label class="option" for="painel-2" data-txt="Painel 2"></label>
                            </div>
                            <div>
                                <input id="painel-3" name="option-painel" type="radio" />
                                <label class="option" for="painel-3" data-txt="Painel 3"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SELECIONAR DISCIPLINA -->
                <div class="mb-6">
                    <h3 class="mb-2 singleTap">
                        SELECIONAR DISCIPLINA CORRESPONDENTE
                        <img src="{{ asset('images/singletap.svg') }}" alt="Ícone">
                    </h3>
                    <div class="select select-painel">
                        <div class="selected" data-default="Painel (nº ID)" data-one="Painel 1" data-two="Painel 2"
                            data-three="Painel 3">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="arrow">
                                <path
                                    d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z">
                                </path>
                            </svg>
                        </div>
                        <div class="options">
                            <div>
                                <input id="painel-1" name="option-painel" type="radio" checked />
                                <label class="option" for="painel-1" data-txt="Disciplina 1"></label>
                            </div>
                            <div>
                                <input id="painel-2" name="option-painel" type="radio" />
                                <label class="option" for="painel-2" data-txt="Disciplina 2"></label>
                            </div>
                            <div>
                                <input id="painel-3" name="option-painel" type="radio" />
                                <label class="option" for="painel-3" data-txt="Disciplina 3"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CANVAS SPACE -->
    <div class="container-paineis">
        <div class="canvas-container">
            <div class="menu-zoom">
                <button id="zoom-out">-</button>
                <button id="zoom-in">+</button>
            </div>
            <div id="canvas" class="canvas">
                @foreach ($paineisRenderizados as $painel)
                    @livewire('panel', ['painel' => $painel], key($painel->id))    
                @endforeach
                @livewireScripts
                <img src="{{ asset('images/inicioConexoes.svg') }}" alt="">
            </div>
        </div>
    </div>
</div>
