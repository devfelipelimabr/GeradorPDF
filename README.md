# Gerador de PDF Personalizado

Este projeto é uma aplicação web que permite aos usuários autenticados gerar e baixar documentos PDF personalizados a partir de dados fornecidos pelo usuário. A aplicação foi desenvolvida utilizando o framework CodeIgniter 4, com suporte para geração de PDFs através da biblioteca Dompdf.

## Tecnologias Utilizadas

- **PHP** (CodeIgniter 4) - Backend e API RESTful
- **MySQL** - Banco de dados para armazenamento de usuários e PDFs gerados
- **Dompdf** - Biblioteca para geração de PDFs a partir de HTML

## Funcionalidades

- **Registro e Autenticação de Usuários**: Permite que os usuários se cadastrem e façam login para acessar a funcionalidade de geração de PDFs.
- **Geração de PDF**: Gera um PDF personalizado com dados fornecidos pelo usuário e armazena o arquivo no servidor.
- **Download Automático**: Após a geração, o PDF é disponibilizado para download com um clique.
- **Proteção de Acesso**: Os PDFs só podem ser acessados pelo usuário que os gerou, garantindo segurança e privacidade.

## Configuração do Ambiente

### Pré-requisitos

- **PHP** 7.3 ou superior
- **Composer** - Gerenciador de dependências para PHP
- **MySQL** - Banco de dados para armazenar informações dos usuários e dos PDFs

### Passo a Passo de Instalação

1. **Clone o Repositório**

   ```bash
   git clone https://github.com/devfelipelimabr/GeradorPDF.git
   cd GeradorPDF
   ```

2. **Instale as Dependências**

   Instale as dependências do projeto usando o Composer:

   ```bash
   composer install
   ```

3. **Configuração do Banco de Dados**

   Configure o banco de dados MySQL. Crie uma nova base de dados e execute o script abaixo para criar as tabelas necessárias.

   ```bash
   php spark migrate
   ```

4. **Configuração do Arquivo `.env`**

   Duplique o arquivo `.env.example` para `.env` e configure as variáveis de ambiente, incluindo as informações de banco de dados e o nome do projeto:

   ```
   database.default.hostname = localhost
   database.default.database = nome_do_banco_de_dados
   database.default.name = seu_usuario
   database.default.password = sua_senha
   database.default.DBDriver = MySQLi

   PROJECT_NAME = "GeradorPDF"
   ```

5. **Configuração do CodeIgniter**

   Execute o comando para criar a chave de criptografia:

   ```bash
   php spark key:generate
   ```

6. **Inicie o Servidor**

   Inicie o servidor de desenvolvimento do CodeIgniter:

   ```bash
   php spark serve
   ```

   A aplicação estará disponível em `http://localhost:8080`.

## Uso da Aplicação

### Registro e Login

- Acesse a rota `/api/register` para registrar um novo usuário.
- Em seguida, faça login utilizando a rota `/api/login`.

### Geração de PDF

- Após o login, envie uma requisição `POST` para `/api/generate-pdf` com os dados necessários para o PDF.
- O servidor retornará o `pdf_id` do arquivo gerado.

### Download do PDF

- Realize uma requisição `GET` para `/api/download-pdf/{pdf_id}`. O download será iniciado automaticamente.

## Estrutura do Projeto

- **app/Controllers**: Contém os controladores `UserController` e `PdfController` para gerenciar autenticação e PDF.
- **app/Entities**: Define as entidades `User` e `Pdf` para manipulação de dados.
- **app/Models**: Contém os modelos `UserModel` e `PdfModel` para interagir com o banco de dados.
- **app/Views**: Contém o template `pdf_template.php`, utilizado para formatar o conteúdo dos PDFs gerados.

## Exemplos de Requisição com Postman

### 1. **Registro de Usuário**

- **Método**: `POST`
- **URL**: `http://localhost:8080/api/register`
- **Body**: Selecione a opção `x-www-form-urlencoded`
  - `name`: `usuario`
  - `email`: `email@example.com`
  - `password`: `senha123`
  - `password_confirm`: `senha123`

**Exemplo de Resposta**:

```json
{
  "status": "success",
  "message": "Usuário registrado com sucesso"
}
```

---

### 2. **Login de Usuário**

- **Método**: `POST`
- **URL**: `http://localhost:8080/api/login`
- **Body**: Selecione a opção `x-www-form-urlencoded`
  - `email`: `email`
  - `password`: `senha123`

**Exemplo de Resposta**:

```json
{
  "status": "success",
  "message": "Login realizado com sucesso"
}
```

> **Nota**: Após o login, o Postman armazenará automaticamente o cookie de sessão se a opção "Automatically follow redirects" estiver ativada. Isso permitirá que você continue a fazer requisições autenticadas enquanto a sessão estiver ativa.

---

### 3. **Geração de PDF**

- **Método**: `POST`
- **URL**: `http://localhost:8080/api/generate-pdf`
- **Headers**:
  - `Content-Type`: `application/json`
- **Body**: Selecione a opção `raw` e insira o JSON com os dados do PDF
  ```json
  {
    "nome": "João",
    "endereco": "Rua Exemplo, 123",
    "data_nascimento": "01/01/1980",
    "detalhes": [
      {
        "item": "Produto 1",
        "descricao": "Descrição do produto 1",
        "quantidade": 2,
        "valor": "R$ 50,00"
      },
      {
        "item": "Produto 2",
        "descricao": "Descrição do produto 2",
        "quantidade": 1,
        "valor": "R$ 30,00"
      }
    ]
  }
  ```

**Exemplo de Resposta**:

```json
{
  "status": "success",
  "message": "PDF gerado com sucesso",
  "pdf_id": 1
}
```

> **Nota**: O campo `"pdf_id"` retornado na resposta será usado para fazer o download do PDF gerado.

---

### 4. **Download de PDF**

- **Método**: `GET`
- **URL**: `http://localhost:8080/api/download-pdf/{pdf_id}`
  - Substitua `{pdf_id}` pelo ID do PDF retornado na resposta da geração.

**Exemplo de Resposta**:  
O PDF será baixado automaticamente como um arquivo chamado `GeradorPDF.pdf` (ou o valor definido na variável `PROJECT_NAME` no arquivo `.env`).

---

## Considerações Importantes

- **Sessões**: As requisições para geração e download de PDFs exigem que o usuário esteja autenticado. Se a sessão expirar ou o login não for realizado, a API retornará um erro de autorização.
- **Organização no Postman**: Recomenda-se criar uma **Coleção Postman** e adicionar essas requisições, permitindo organizar e reutilizar os endpoints de forma conveniente.

Esse guia deve permitir que você interaja com a API usando o Postman de maneira fácil e eficiente.

## Considerações de Segurança

- **Autenticação de Usuários**: Apenas usuários autenticados podem gerar e baixar PDFs.
- **Acesso Restrito a PDFs**: Os PDFs só podem ser baixados pelo usuário que os criou, garantindo privacidade.
- **Proteção de Sessões**: A aplicação utiliza sessões para proteger rotas sensíveis.

## Licença

Este projeto é licenciado sob a [MIT License](LICENSE).

Este `README` inclui instruções detalhadas para instalação, configuração, uso e estrutura do projeto, além de exemplos de requisições para facilitar o entendimento de como interagir com a API.
