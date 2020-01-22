@extends('emails.layout')

@section('content')
<strong>
@if ($notifiable->metadata)
    @if ($notifiable->metadata->gender == 'male')
        {{ __('Mail/Intro.male', ['name' => $notifiable->name]) }}
    @elseif($notifiable->metadata->gender == 'female')
        {{ __('Mail/Intro.female', ['name' => $notifiable->female]) }}
    @else
        {{ __('Mail/Intro.unknown', ['name' => $notifiable->name]) }}
    @endif
@else
    {{ __('Mail/Intro.unknown', ['name' => $notifiable->name]) }}
@endif
</strong>
<p>
    {!! __('Mail/Task/TaskAssignNotification.taskName', ['task_name' => $task->name]) !!}
</p>
<p>
    {!! __('Mail/Task/TaskAssignNotification.description', ['description' => $task->description]) !!}
</p>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
    <tbody>
      <tr>
        <td align="center">
          <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td> <a href="{{ url('/admin/task/'.$task->id) }}" target="_blank">{{__('Mail/Task/TaskAssignNotification.seeMore')}}</a> </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
  <p>{{__('Mail/Task/TaskAssignNotification.footer')}}</p>
@endsection