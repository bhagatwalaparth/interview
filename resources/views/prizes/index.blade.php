@php
  $sumOfCurrentProbability = floatval($prizes->sum('probability'));
  $isProbabilityeFullFill = $sumOfCurrentProbability == 100;
  $remainingProbability = 100 - $sumOfCurrentProbability;
  $sumOfAwaredPrize = $prizes->sum('awarded');
  $isAwaredPrizeAvailable = $sumOfAwaredPrize > 0;
@endphp

@extends('default')

@section('content')
@include('prob-notice')
<div class="container">
  <div class="row">
      @if (!$isProbabilityeFullFill)
          <div class="col-md-12">
              <div class="alert alert-danger" role="alert">
                  sum of all prize probability must be 100%.Current it is {{ $sumOfCurrentProbability }}% you have to add {{ $remainingProbability }}% to the prize
              </div>
          </div>
      @endif
      <div class="col-md-12">
          @if (!$isProbabilityeFullFill)
              <div class="d-flex justify-content-end mb-3">
                  <a href="{{ route('prizes.create') }}" class="btn btn-info">Create</a>
              </div>
          @endif
          <h1>Prizes</h1>
          <table class="table table-bordered table-striped">
              <thead>
                  <tr>
                      <th>Id</th>
                      <th>Title</th>
                      <th>Probability</th>
                      <th>Awarded</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($prizes as $prize)
                      <tr>
                          <td>{{ $prize->id }}</td>
                          <td>{{ $prize->title }}</td>
                          <td>{{ $prize->probability }}</td>
                          <td>{{ $prize->awarded }}</td>
                          <td>
                              <div class="d-flex gap-2">
                                  <a href="{{ route('prizes.edit', [$prize->id]) }}" class="btn btn-primary">Edit</a>
                                  {!! Form::open(['method' => 'DELETE', 'route' => ['prizes.destroy', $prize->id]]) !!}
                                  {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                  {!! Form::close() !!}
                              </div>
                          </td>
                      </tr>
                  @endforeach
              </tbody>
          </table>
      </div>
  </div>
  <hr>
  @if ($isProbabilityeFullFill)
  <div class="row">
      <div class="col-md-6 offset-md-3">
          <div class="card">
              <div class="card-header">
                  <h3>Simulate</h3>
              </div>
              <div class="card-body">
                  {!! Form::open(['method' => 'POST', 'route' => ['simulate']]) !!}
                  <div class="form-group">
                      {!! Form::label('number_of_prizes', 'Number of Prizes') !!}
                      {!! Form::number('number_of_prizes', 50, ['class' => 'form-control']) !!}
                  </div>
                  {!! Form::submit('Simulate', ['class' => 'btn btn-primary']) !!}
                  {!! Form::close() !!}
              </div>

              <br>

              <div class="card-body">
                  {!! Form::open(['method' => 'POST', 'route' => ['reset']]) !!}
                  {!! Form::submit('Reset', ['class' => 'btn btn-primary']) !!}
                  {!! Form::close() !!}
              </div>

          </div>
      </div>
  </div>   
  @endif
</div>
@if ($isProbabilityeFullFill)
<div class="container  mb-4">
  <div class="row">
    <div class="col-md-6">
      <h2>Probability Settings</h2>
      <canvas id="probabilityChart"></canvas>
    </div>
    <div class="col-md-6">
      <h2>Actual Rewards</h2>
      <canvas id="awardedChart"></canvas>
    </div>
  </div>
</div>
@endif
@stop

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        const prizes = {!! $prizes !!}
        const sumOfCurrentProbability = {!! $sumOfCurrentProbability !!}
        const sumOfAwaredPrize = {!! $sumOfAwaredPrize !!}
    </script>
    <script src="{{ asset('/render-chart.js') }}"></script>
@endpush
