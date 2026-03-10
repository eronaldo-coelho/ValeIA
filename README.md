<h1 align="center">ValeIA</h1>

<p align="center">
Sistema inteligente de gestão de vales corporativos com análise automática de notas fiscais utilizando Inteligência Artificial.
</p>

<p align="center">
<strong>Laravel • MySQL • API Integration • AI Analysis • Automation</strong>
</p>

<hr>

<h2>Sobre o Projeto</h2>

<p>
O <strong>ValeIA</strong> é uma plataforma desenvolvida para empresas que desejam gerenciar benefícios de funcionários, como vale alimentação ou outros tipos de vales corporativos.
</p>

<p>
O sistema permite o controle completo de funcionários, vales distribuídos, pagamentos e notas fiscais enviadas para validação.
</p>

<p>
Um dos principais diferenciais da plataforma é o uso de <strong>Inteligência Artificial para análise de notas fiscais</strong>, permitindo identificar automaticamente produtos comprados e detectar possíveis irregularidades, como compra de bebidas alcoólicas com vale alimentação.
</p>

<hr>

<h2>Principais Funcionalidades</h2>

<ul>
<li>Gestão completa de funcionários</li>
<li>Controle de vales corporativos</li>
<li>Cadastro e gerenciamento de empresas</li>
<li>Upload e análise automática de notas fiscais</li>
<li>Validação inteligente de compras usando IA</li>
<li>Relatórios detalhados de uso de benefícios</li>
<li>Auditoria de atividades do sistema</li>
<li>Controle de planos e pagamentos</li>
<li>Integração com APIs externas</li>
<li>Sistema de autenticação e recuperação de senha</li>
</ul>

<hr>

<h2>Arquitetura do Sistema</h2>

<p>O projeto segue arquitetura MVC utilizando o framework:</p>

<ul>
<li>Framework: Laravel</li>
<li>Backend: PHP</li>
<li>Banco de dados: MySQL</li>
<li>Frontend: Blade + TailwindCSS</li>
<li>Build: Vite</li>
<li>APIs externas integradas</li>
</ul>

<hr>

<h2>Estrutura do Projeto</h2>

<pre>
app/
 ├── Http/Controllers
 ├── Models
 ├── Services

config/
database/
 ├── migrations
 ├── seeders

resources/
 ├── views
 ├── js
 ├── css

routes/
 ├── web.php
 ├── api.php

public/
storage/
</pre>

<hr>

<h2>Recursos Técnicos</h2>

<ul>
<li>Arquitetura MVC</li>
<li>Middleware de autenticação</li>
<li>Controle de permissões</li>
<li>Sistema de auditoria</li>
<li>Integração com APIs</li>
<li>Validação automática de notas fiscais</li>
<li>Geração de relatórios</li>
</ul>

<hr>

<h2>Instalação</h2>

<pre>
git clone https://github.com/eronaldo-coelho/ValeIA.git
cd ValeIA

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

npm install
npm run build

php artisan serve
</pre>

<hr>

<h2>Segurança</h2>

<p>
Informações sensíveis como credenciais e configurações são armazenadas no arquivo <code>.env</code>, que não é incluído no repositório.
</p>

<hr>

<h2>Status do Projeto</h2>

<p>
Projeto funcional em produção com recursos de gestão empresarial e automação de análise de notas fiscais.
</p>

<hr>

<h2>Autor</h2>

<p>
<strong>Eronaldo Coelho</strong><br>
Full Stack Developer
</p>

<p>
Experiência com desenvolvimento de sistemas web, APIs, automações e aplicações completas utilizando Laravel, Java, Kotlin e Flutter.
</p>

<hr>

<p align="center">
Desenvolvido por Eronaldo Coelho
</p>
