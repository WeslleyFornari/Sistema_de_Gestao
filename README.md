# Pré-requisitos:
    - Docker Desktop
    - WSL 2

INICIE O DOCKER DESKTOP

# Instalação
    abra o terminal WSL onde vai ficar o projeto
# Clone o projeto
    git clone https://github.com/WeslleyFornari/Sistema_de_Gestao.git
    feche o WSL e abra dentro da pasta criada

# Execute o comando abaixo
    docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs



# Abra o terminal WSL e os comandos:
ls /home
su - {usuario}  Obs: não pode ser root

sudo nano /etc/wsl.conf

# Na raiz do projeto abra o shell linux WSL e execute o comando abaixo para criar o ambiente em Docker
./vendor/bin/sail up -d --build

# Carregar as tabelas / migrations criados no banco de dados

# parar o projeto #
./vendor/bin/sail down 
# rodar o projeto #
./vendor/bin/sail up -d
# Suba o serviços de filas #
./vendor/bin/sail artisan queue:work

# Crie o banco de dados e as tabelas
./vendor/bin/sail artisan migrate

# Crie um usuario Padrão de acesso
./vendor/bin/sail artisan db:seed

# rodar o projeto #
./vendor/bin/sail up -d

# Acessar
//localhost

# Primeiro Acesso
email: admin@admin  password: password





