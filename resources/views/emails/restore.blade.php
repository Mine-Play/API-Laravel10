<x-mail::message>
# Код подтверждения

На ваш E-Mail была оставлена заявка на смену пароля с сайта <a href="https://mine-play.ru">Mine-Play.Ru</a>
Ваш код подтверждения: <br> <b>{{$pin}}</b>
<br>
Если же вы не оставляли никакой заявки, то просто удалите данное письмо

С уважением,<br>
{{ config('app.name') }}
</x-mail::message>
