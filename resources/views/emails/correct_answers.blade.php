@component('mail::message')
# Hi, {{ $user->name }}!

Here answers on questions:<br>
<table style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;">
    <tr>
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Question</th>
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Answer</th>
    </tr>
@forelse($answers as $question => $answer)
    <tr>
        <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{$question}}</td>
        <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{$answer}}</td>
    </tr>
@empty
    <p>No Answers...</p>
@endforelse
</table>

@endcomponent

