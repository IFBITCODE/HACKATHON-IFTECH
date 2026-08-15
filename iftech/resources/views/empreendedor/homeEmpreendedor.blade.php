<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal do Empreendedor </title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="style.css">

        @vite([

        'resources/css/empreendedor/homeEmpreendedor.css',

        'resources/js/empreendedor/homeEmpreendedor.js'

    ])
</head>

<body>

    <body style="background-image: url('{{ asset('imagens/fundo.png') }}');">

<div class="main-wrapper">

    <!-- Conteúdo Centralizado -->
    <main class="content-container">

        <!-- TELA 1: Boas-vindas (Seleção de Ação) -->
        <section id="view-welcome" class="view-section active">
            <div class="hero-card">
                <div class="badge-label">Portal do Empreendedor</div>
                <h1>Impulsione seu negócio no turismo local</h1>
                <p>Cadastre seu estabelecimento no portal oficial do município para alcançar milhares de turistas ou acesse sua conta para gerenciar seu cadastro.</p>
                
                <div class="action-grid">
                    <div class="action-card" onclick="switchView('register')">
                        <div class="action-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </div>
                        <h3>Cadastrar Empreendimento</h3>
                        <p>Crie sua conta e submeta seu negócio para credenciamento oficial.</p>
                    </div>

                    <div class="action-card secondary" onclick="switchView('login')">
                        <div class="action-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                        </div>
                        <h3>Já tenho conta</h3>
                        <p>Acesse com seu e-mail e senha para acompanhar o status e atualizações.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- TELA 2: Form de Login -->
        <section id="view-login" class="view-section">
            <div class="portal-card small-card">
                <div class="tab-switch">
                    <button type="button" class="tab-btn active">Entrar</button>
                    <button type="button" class="tab-btn" onclick="switchView('register')">Cadastrar</button>
                </div>

                <div class="card-intro">
                    <h2>Acesse sua conta</h2>
                    <p>Informe seus dados de acesso cadastrados.</p>
                </div>

                <form id="loginForm" novalidate>
                    <div class="input-group-custom">
                        <label for="login_email">E-mail <span>*</span></label>
                        <input type="email" id="login_email" placeholder="seuemail@exemplo.com" required>
                        <div class="invalid-feedback">Informe um e-mail válido.</div>
                    </div>

                    <div class="input-group-custom">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="login_password" class="mb-0">Senha <span>*</span></label>
                            <a href="#" class="forgot-link">Esqueceu a senha?</a>
                        </div>
                        <input type="password" id="login_password" placeholder="Sua senha" required>
                        <div class="invalid-feedback">Informe sua senha.</div>
                    </div>

                    <button type="submit" class="submit-button">
                        <span>Entrar no Portal</span>
                    </button>
                </form>

                <div class="card-footer-text">
                    Ainda não possui cadastro? <a href="#" onclick="switchView('register')">Cadastre seu negócio</a>
                </div>
            </div>
        </section>

        <!-- TELA 3: Form de Cadastro do Negócio -->
        <section id="view-register" class="view-section">
            <div class="portal-card">
                <div class="tab-switch">
                    <button type="button" class="tab-btn" onclick="switchView('login')">Entrar</button>
                    <button type="button" class="tab-btn active">Cadastrar</button>
                </div>

                <div class="card-intro">
                    <h2>Cadastre seu negócio</h2>
                    <p>Após o envio, você poderá acessar o portal com seu e-mail e senha para acompanhar a análise.</p>
                </div>

                <div id="statusAlert" class="success-message d-none">
                    <div class="success-icon">✓</div>
                    <div>
                        <strong>Solicitação enviada com sucesso!</strong>
                        <p>Sua conta foi criada. Utilize seu e-mail e senha cadastrados para fazer login e acompanhar o status da aprovação.</p>
                    </div>
                </div>

                <form id="entrepreneurForm" novalidate>
                    <!-- Seção 01: Dados de Acesso -->
                    <div class="form-section">
                        <div class="section-header">
                            <span class="section-number">01</span>
                            <div>
                                <h3>Dados de Acesso</h3>
                                <p>Credenciais para acessar o portal posteriormente</p>
                            </div>
                        </div>

                        <div class="input-group-custom">
                            <label for="email">E-mail corporativo ou pessoal <span>*</span></label>
                            <input type="email" id="email" placeholder="seuemail@exemplo.com" required>
                            <div class="invalid-feedback">Informe um e-mail válido.</div>
                        </div>

                        <div class="input-grid">
                            <div class="input-group-custom">
                                <label for="password">Senha de acesso <span>*</span></label>
                                <input type="password" id="password" placeholder="Mínimo de 8 caracteres" minlength="8" required>
                                <div class="invalid-feedback">A senha deve ter no mínimo 8 caracteres.</div>
                            </div>

                            <div class="input-group-custom">
                                <label for="password_confirmation">Confirmar senha <span>*</span></label>
                                <input type="password" id="password_confirmation" placeholder="Repita a senha" required>
                                <div class="invalid-feedback">As senhas precisam ser iguais.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Seção 02: Sobre o Negócio -->
                    <div class="form-section">
                        <div class="section-header">
                            <span class="section-number">02</span>
                            <div>
                                <h3>Sobre o Empreendimento</h3>
                                <p>Informações visíveis aos turistas após aprovação</p>
                            </div>
                        </div>

                        <div class="input-group-custom">
                            <label for="businessName">Nome do empreendimento <span>*</span></label>
                            <input type="text" id="businessName" placeholder="Ex: Pousada Vista Verde / Restaurante Sabor do Sertão" required>
                            <div class="invalid-feedback">Informe o nome do empreendimento.</div>
                        </div>

                        <div class="input-grid">
                            <div class="input-group-custom">
                                <label for="category">Categoria <span>*</span></label>
                                <select id="category" required>
                                    <option value="" selected disabled>Selecione a categoria</option>
                                    <option value="artesao">Artesanato Local</option>
                                    <option value="hospedagem">Hospedagem & Hotelaria</option>
                                    <option value="gastronomia">Gastronomia / Bares e Restaurantes</option>
                                    <option value="guia">Guia de Turismo / Agência</option>
                                    <option value="lazer">Lazer e Entretenimento</option>
                                </select>
                                <div class="invalid-feedback">Selecione uma categoria.</div>
                            </div>

                            <div class="input-group-custom">
                                <label for="document">CPF ou CNPJ <span>*</span></label>
                                <input type="text" id="document" placeholder="00.000.000/0001-00" required>
                                <div class="invalid-feedback">Informe o documento.</div>
                            </div>
                        </div>

                        <div class="input-group-custom">
                            <label for="description">Descrição do serviço <span>*</span></label>
                            <textarea id="description" rows="3" maxlength="500" placeholder="Descreva sucintamente o que o seu espaço ou serviço oferece aos turistas..." required></textarea>
                            <div class="textarea-counter">
                                <small>Descreva diferenciais, especialidades ou história do local.</small>
                                <span id="charCount">0 / 500</span>
                            </div>
                            <div class="invalid-feedback">Informe uma descrição.</div>
                        </div>
                    </div>

                    <!-- Seção 03: Localização -->
                    <div class="form-section">
                        <div class="section-header">
                            <span class="section-number">03</span>
                            <div>
                                <h3>Localização</h3>
                                <p>Onde o turista encontra você</p>
                            </div>
                        </div>

                        <div class="input-grid">
                            <div class="input-group-custom">
                                <label for="address">Endereço público</label>
                                <input type="text" id="address" placeholder="Rua, Avenida, Praça, nº">
                            </div>

                            <div class="input-group-custom">
                                <label for="city">Cidade</label>
                                <input type="text" id="city" placeholder="Nome da cidade">
                            </div>

                            <div class="input-group-custom">
                                <label for="neighborhood">Bairro ou Distrito</label>
                                <input type="text" id="neighborhood" placeholder="Ex: Centro, Zona Rural">
                            </div>
                        </div>
                    </div>

                    <!-- Seção 04: Acessibilidade -->
                    <div class="form-section">
                        <div class="section-header">
                            <span class="section-number">04</span>
                            <div>
                                <h3>Recursos & Acessibilidade</h3>
                                <p>Selecione os recursos disponíveis no local</p>
                            </div>
                        </div>

                        <div class="accessibility-grid">
                            <label class="accessibility-option">
                                <input type="checkbox" id="acc_rampa">
                                <span class="check-box">✓</span>
                                <span>Rampa de Acesso / Térreo</span>
                            </label>

                            <label class="accessibility-option">
                                <input type="checkbox" id="acc_libras">
                                <span class="check-box">✓</span>
                                <span>Atendimento em Libras</span>
                            </label>

                            <label class="accessibility-option">
                                <input type="checkbox" id="acc_banheiro">
                                <span class="check-box">✓</span>
                                <span>Banheiro Adaptado (PCD)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Termos e Submissão -->
                    <div class="terms-area">
                        <label class="terms-label">
                            <input type="checkbox" id="terms" required>
                            <span>Declaro para os devidos fins que as informações prestadas são verdadeiras e autorizo o credenciamento público no portal.</span>
                        </label>
                    </div>

                    <button type="submit" class="submit-button">
                        <span>Finalizar Cadastro e Criar Conta</span>
                    </button>
                </form>
            </div>
        </section>

    </main>
</div>

<!-- JS Personalizado -->
</body>
</html>