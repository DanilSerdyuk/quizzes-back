@component('mail::message')
# Hi, {{ $user->name }}!

Here answers on questions:<br>
<table style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;">
    <tr>
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Question</th>
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Your Answer</th>
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Is Correct</th>
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Correct Answer</th>
    </tr>
    @foreach($answers as $item)
        <tr>
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{$item->question->title}}</td>
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{$item->value}}</td>
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{$item->is_correct ? 'Yes' : 'No'}}</td>
            <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{$item->question->correct_answer}}</td>
        </tr>
    @endforeach
</table>
@endcomponent
