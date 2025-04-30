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
                    <button id="addButton">teste</button>
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
                <div id="blocoTxt" class="mb-6">
                    <h3>BLOCO DE TEXTO</h3>
                    <div wire:ignore>
                        <div id="trumbowyg-editor" placeholder="Insira seu texto aqui"></div>
                    </div>
                    <input type="hidden" id="editorInput" name="editorContent">
                </div>
                <!-- BOTÕES DE SALVAR/EXCLUIR -->
                <div class="buttons">
                    <button id="editarMidia">
                        Editar Mídia
                    </button>
                    <button id="excluirPainel">
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
                    <input class="" type="text" id="btnTxt"/>
                </div>
                <!-- TRANSIÇÕES -->
                <div class="mb-6">
                    <h3 class="mb-2">TRANSIÇÕES</h3>
                    <select class="select-native">
                        <option value="" disabled selected>Nenhuma</option>
                        <option value="proximo">Próximo Painél</option>
                        <option value="final">Final da Experiência</option>
                    </select>
                </div>
                <!-- SELECIONAR O PAINEL -->
                <div class="mb-6">
                    <h3 class="mb-2 singleTap">
                        SELECIONAR PAINEL
                        <img class="tapSelect" src="{{ asset('images/singletap.svg') }}" alt="Ícone"
                            style="cursor: pointer;">
                    </h3>
                    <select class="select-native">
                        <option disabled selected>Selecione um painel</option>
                        @foreach ($paineisRenderizados as $painel)
                            <option value="{{ $painel->id }}">Painel {{ $painel->id }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- EXCLUIR BOTÃO -->
                <button id="deleteBtn">Excluir botão</button>
            </div>

            <!-- Quando o canvas está "selecionado" -->
            <div class="menu-opcoes canvas-opcoes">
                <!-- NOME DA CENA -->
                <div class="mb-6">
                    <h3 class="mb-2">NOME DA CENA</h3>
                    <input type="text" wire:model.lazy="nameScene" class="form-input"
                        placeholder="Insira o nome da cena" />
                </div>
                <!-- SELECIONAR O PAINEL INICIAL-->
                <div class="mb-6">
                    <h3 class="mb-2 singleTap">
                        SELECIONAR PAINEL INICIAL
                        <img class="tapSelect" src="{{ asset('images/singletap.svg') }}" alt="Ícone"
                            style="cursor: pointer;">
                    </h3>
                    <select wire:model="startPainelId" wire:change="updateStartPanel($event.target.value)"
                        class="select-native">
                        <option disabled selected>Selecione um painel</option>
                        @foreach ($paineisRenderizados as $painel)
                            <option value="{{ $painel->id }}">Painel {{ $painel->id }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- SELECIONAR DISCIPLINA -->
                <div class="mb-6">
                    <h3 class="mb-2">
                        SELECIONAR DISCIPLINA DA CENA
                    </h3>
                    <div class="select" data-default="Selecione uma disciplina">
                        <select wire:model="disciplinaSelecionada" wire:change="updateDisciplinaScene"
                            name="disciplina_id" class="select-native">
                            <option disabled selected>Selecione uma disciplina</option>
                            @foreach($disciplinas as $disciplina)
                                <option value="{{ is_object($disciplina) ? $disciplina->id : $disciplina['id'] }}">
                                    {{ is_object($disciplina) ? $disciplina->name : $disciplina['name'] }}
                                </option>
                            @endforeach
                        </select>
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
                <button id="resizeZoom" hidden>Redefinir zoom</button>
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