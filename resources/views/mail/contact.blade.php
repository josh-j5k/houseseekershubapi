<x-mail::message>
    # Introduction

    Name: {{$data['name']}}
    Email: {{$data['email']}}

    <x-mail::panel>
        Category: {{$data['category']}}

        Description:

        {{$data['description']}}
    </x-mail::panel>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>