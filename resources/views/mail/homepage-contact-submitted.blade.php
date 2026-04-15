<x-mail::message>
# Nova mensagem pelo site

**Nome:** {{ $contactMessage->name }}

**E-mail:** {{ $contactMessage->email }}

@if($contactMessage->phone)
**Telefone:** {{ $contactMessage->phone }}
@endif

@if($contactMessage->subject)
**Assunto:** {{ $contactMessage->subject }}
@endif

---

{{ $contactMessage->message }}

<x-mail::button :url="url('/')">
Abrir site
</x-mail::button>

Enviado em {{ $contactMessage->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i') }}.
</x-mail::message>
