# Instalação do projeto

# Copie a pasta do projeto /Sistema_de_Gestão para o lugar desejado em sua maquina
# Inicie o Docker Desktop
# Na raiz do projeto abra o shell linux WSL e execute o comando abaixo para criar o ambiente em Docker
./vendor/bin/sail up -d --build

# Carregar as tabelas / migrations criados no banco de dados

# parar o projeto #
./vendor/bin/sail down 
# rodar o projeto #
./vendor/bin/sail up -d


