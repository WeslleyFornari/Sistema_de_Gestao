## Pré-requisitos:
    Docker Desktop instalado e rodando.
    WSL configurado (para usuários Windows).
    PHP 8.3
    Laravel 10
    
## Instalação e Configuração
    abra o terminal Linux / WSL(Windows) onde vai ficar o projeto
## Clone o repositório
    git clone https://github.com/WeslleyFornari/Sistema_de_Gestao.git
    cd Sistema_de_Gestao

## Instale as dependências do Composer via Docker:
    docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

## Inicie os containers do projeto:
    ./vendor/bin/sail up -d 

# Carregar as tabelas / migrations criados no banco de dados
    ./vendor/bin/sail artisan migrate --seed


# Node dependencias e Filas
    ./vendor/bin/sail npm install
    ./vendor/bin/sail artisan queue:work & ./vendor/bin/sail npm run dev

## Executando o Sistema
    http://localhost
    login: admin@admin
    senha: password

## Comandos Úteis do Docker (Sail)
    Parar o projeto: ./vendor/bin/sail down
    Subir o projeto: ./vendor/bin/sail up -d

## 📖 INSTRUÇÕES DE USO

### 1. Gestão de Entidades (CRUD completo)
O sistema possui módulos para a gestão de **Colaboradores, Grupos Econômicos, Bandeiras e Unidades**. Em todos eles, o fluxo é padronizado:
* **Cadastros:** Você pode Criar, Visualizar, Editar ou Excluir registros em qualquer um desses módulos através do menu lateral.
* **Ações de Lista:** Para manter o visual limpo, as opções de **Editar e Excluir** em cada listagem estão agrupadas em um menu **Dropdown** ao final de cada linha.

### Gestão de Colaboradores e Exportação
* No menu lateral, acesse **Colaboradores**.
* Na tela de listagem a direita, você encontrará os **filtros avançados** para busca de registros.
* **Exportação:** Os botões para gerar **PDF** ou **Excel** da listagem filtrada estão localizados nesta página.
* Você também pode realizar as operações de **Cadastrar, Editar ou Excluir** colaboradores.
* **Novo Colaborador:** Ao cadastrar um novo colaborador, o sistema define automaticamente a **senha padrão: `password`**. O usuário poderá alterá-la posteriormente no seu primeiro acesso através das configurações de perfil.

### Relatórios e Listagens
* Acesse o menu **Relatórios** para visualizar a listagem geral consolidada do sistema e realizar o download dos arquivos gerados.

### 3. Sistema de Auditoria (Logs)
* O sistema registra automaticamente as ações de criação, edição e exclusão.
* Para visualizar o histórico, acesse o menu **Auditoria**.
* **Nota Técnica:** Os logs são processados em segundo plano via **Queues**. Certifique-se de manter o comando `./vendor/bin/sail artisan queue:work` ativo para visualizar os registros atualizados.









