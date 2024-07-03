@component('mail::message')

    Bem-vindo ao {{ config('app.name') }}!

    Olá {{ $name }},

    Agradecemos por se cadastrar no Github Analyzer, a plataforma que te ajuda a analizar seus repositorios.

    Com o {{ config('app.name') }}, você pode:

    Para começar, siga estes passos:

    1. Acesse o site do Missing Pets: https://www.ejsocial.com.
    2. Faça login com seu email e senha.
    3. Crie um post do seu pet.
    4. Compartilhe o seu post nas redes sociais e com amigos.

    Estamos aqui para te ajudar a encontrar seu pet. Se você tiver alguma dúvida, entre em contato conosco pelo email http://www.desafio-reportei.ejsocial.com/help

@endcomponent
