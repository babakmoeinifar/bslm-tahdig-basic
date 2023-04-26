@extends('template.master-user')

@section('title', 'رزرو')

@section('content')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>رزرو غذا</h3>
            </div>
        </div>
    </div>
    @include('template.messages')

    @if($is_temporary_disabled)
        <div class="card ss02">
            <div class="p-5 text-center">
                <h5>متاسفانه رزرو غذا غیرفعاله.</h5>
            </div>
        </div>
        
    @else
        <form action="{{ url('lunch/reserve') }}" method="post">
            @csrf
            <div class="row row-cols-1 row-cols-md-3 mt-4">
                @foreach($bookings as $booking)
                    <div class="col mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="card-title ss02">{{ jdfw($booking->booking_date) }} {{ $booking->meal->name }}</h4>
                                <div class="card-text">
                                    @foreach($booking->foods as $food)
                                        @if($food->restaurant->is_active)
                                        <h6 class="h6 font-weight-bold mt-2">{{ $food->name }}
                                            <span class="mx-2 text-muted">/ {{ $food->restaurant->name }}</span>
                                        </h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <span class="ss02">{{ $food->price }} تومان </span>
                                            </div>
                                            <div class="col-6">
                                                @php
                                                    $value = $booking->reservationsForUser()->where('food_id', $food->id)->first();
                                                @endphp
                                                <input type="number" class="form-control form-control-sm"
                                                    name="booking[{{ $booking->id }}][ {{ $food->id }}]"
                                                    min="0" max="3" value="{{ $value->quantity ?? 0 }}">
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="form-group row m-0 mt-2">
                                    <label for="quantity" class="col-sm-4 col-form-label">ساختمان</label>
                                    <div class="col-sm-8">
                                        <select class="form-control form-select" name="salon[{{ $booking->id }}]">
                                            <option value="1">---</option>
                                            @foreach($salons as $salon)
                                                <option value="{{ $salon->id }}"
                                                        @if($booking->reservationsForUser()->first())
                                                        @if($salon->id == $booking->reservationsForUser()->first()->salon_id)
                                                        selected
                                                        @endif
                                                        @else
                                                        @if($salon->id == auth()->user()->default_salon_id)
                                                        selected
                                                    @endif
                                                    @endif
                                                >
                                                    {{ $salon->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <nav class="navbar">
                <div class="container">
                    @if(count($bookings) > 0)
                    <button class="btn btn-primary ml-auto" type="submit">ثبت</button>
                    @endif
                </div>
            </nav>
        </form>
    @endif

    
@endsection

@push('js')
    <script src="{{ asset('js/input-spinner.js') }}"></script>
    <script>
        $("input[type='number']").inputSpinner()
    </script>
@endpush
