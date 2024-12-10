<x-mail::message>
    # Introduction

    Name: {{$data['name']}}

    Email: {{$data['email']}}

    Category: {{$data['category']}}

    Description:
    {{$data['description']}}

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>