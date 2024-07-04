@component('mail::message')

    Bem-vindo ao {{ config('app.name') }}!

    Olá {{ $name }},

    Agradecemos por se cadastrar no Github Analyzer, a plataforma que te ajuda a analizar seus repositorios.

    Com o {{ config('app.name') }}, você pode:

    Para começar, siga estes passos:

    1. Acesse o site do GithubAnalyzer: https://www.desafio-reportei.ejsocial.com.
    2. Faça login com sua conta do github.
    3. Escolha um de seus repositorios publicos.

    Estamos aqui para te ajudar a avaliar seus repositorios. Se você tiver alguma dúvida, entre em contato conosco pelo email jpppedreira@gmail.com

@endcomponent
