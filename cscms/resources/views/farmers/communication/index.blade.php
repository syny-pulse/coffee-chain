@extends('farmers.layouts.app')

@section('title', 'Communication')

@section('content')
    <h1><i class="fas fa-comments"></i> Communication with Processors</h1>
    @include('farmers.partials.errors')
    <form action="{{ route('farmers.communication.send') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="processor_id">Processor</label>
            <select name="processor_id" id="processor_id" required>
                @foreach ($processors as $processor)
                    <option value="{{ $processor->company_id }}">{{ $processor->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="content">Message</label>
            <textarea name="content" id="content" required></textarea>
        </div>
        <button type="submit" class="btn"><i class="fas fa-paper-plane"></i> Send</button>
    </form>
    <h2><i class="fas fa-messages"></i> Messages</h2>
    <ul class="message-list">
        @foreach ($messages as $message)
            <li>{{ $message->processor->name }}: {{ $message->content }} ({{ $message->created_at->format('d-m-Y H:i') }})</li>
        @endforeach
    </ul>
@endsection