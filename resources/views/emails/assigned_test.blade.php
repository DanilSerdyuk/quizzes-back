@component('mail::message')
# Hi, {{ $user->name }}!

You have been assigned a test, can take it, by clicking on the button below.

@component('mail::button', ['url' => $url, 'color' => 'success'])
    Start test
@endcomponent

Assigned by {{ $author->name }}
@endcomponent
