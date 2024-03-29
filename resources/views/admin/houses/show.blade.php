@extends('layouts.admin', ['title' => '#' . $house->id])

@section('content')
    <!-- modal delete -->
    <div class="modal fade" tabindex="-1" id="deleteModalShow" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <img src="/images/boolbnb.png" width="150">
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Vuoi cancellare questo post?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                    <button type="button" class="btn btn-danger" @@click="submitForm()">si
                        cancella</button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal delete -->

    <div class="container">
        <div class="row mb-3">
            <div class="col-8 h-75">
                <h2 class="text-uppercase">{{ $house->title }}</h2>
                @if ($house->image)
                    <img class=" img-show" src="{{ asset('storage/' . $house->image) }}" alt="{{ $house->title }}">
                @endif
                <div class="mt-4">
                    <h3 class="text-uppercase">Descrizione</h3>
                    <p value="{{ $house->description }}">{!! $house->description !!}</p>
                    <h3 class="text-uppercase">Disponibilità</h3>
                    <h5>Data inizio disponibilità: {{ date('d/m/Y', strtotime($house->check_in)) }}</h5>
                    <h5>Data fine disponibilità: {{ date('d/m/Y', strtotime($house->check_out)) }}</h5>
                    <div class="row mt-4">
                        <div class="col-6">
                            <h3 class="text-uppercase">Dettagli</h3>
                            <p>Prezzo per notte: {{ $house->night_price }}€</p>
                            <p>Numero di stanze per: {{ $house->n_room }}</p>
                            <p>Numero di posti letto: {{ $house->n_bed }}</p>
                            <p>Numero di bagni: {{ $house->n_bathroom }}</p>
                            <p>Metri quadrati: {{ $house->square_meters }}</p>
                        </div>


                        <div class="col-6">
                            <h3 class="text-uppercase">Indirizzo</h3>
                            <p>Indirizzo: {{ $house->address }}</p>
                            <p>Città: {{ $house->city }}</p>
                            <p>Stato: {{ $house->state }}</p>
                            <p id="latitude">Latitudine: {{ $house->latitude }}</p>
                            <p id="longitude">Longitude: {{ $house->longitude }}</p>
                        </div>
                    </div>


                </div>
                <div>
                    <h3>Messaggi: </h3>

                    {{-- Nome: {{ $message->name }} <br>
                        Cognome: {{ $message->surname }} <br>
                        Email: {{ $message->email }}<br>
                        {{ $message->text }} --}}

                    @foreach ($house->messages as $message)
                        <div {{-- :key="comment.id" --}} class="comment mt-4 text-justify">
                            <div class="row h-25">
                                <img :src="'https://picsum.photos/200/300?random=' +
                                {{ $message->id }}"
                                    alt="" class="rounded-circle" width="50" height="50" />
                                <div class="h-50">
                                    <h5 class="text-white pt-2 m-0">
                                        {{ $message->name }}
                                        {{ $message->surname }}
                                    </h5>

                                    {{-- <p class="text-white">
                                    Lì
                                    <small>{{
                                        // comment.created_at
                                        //     .substr(0, 19)
                                        //     .replace("T", ", ")
                                    }}</small>
                                </p> --}}
                                </div>
                            </div>

                            <p class="text-white pt-2">
                                {{ $message->text }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-4 rounded border border-3 pb-3">
                <h3 class="text-uppercase pt-3">Info</h3>
                <p class="border-bottom border-3 py-3 mb-0">Data creazione: {{ date_format($house->created_at, 'd/m/Y') }}
                </p>
                <p class="border-bottom border-3 py-3 mb-0">Tipo: {{ $house->type ? $house->type->name : 'Not Defined' }}
                </p>
                @if ($house->visibility)
                    <p class="border-bottom border-3 py-3 mb-0 text-uppercase text-success fw-bold">Published</p>
                @else
                    <p class="border-bottom border-3 py-3 mb-0 text-uppercase text-danger fw-bold">To publish</p>
                @endif


                {{-- <p class="border-bottom border-3 py-3 mb-0">Sponsor {{ $house->sponsorships ? $house->sponsorship->name : 'Not Defined' }}
                </p> --}}
                {{-- @if ($house->visibility)
                    <p class="border-bottom border-3 py-3 mb-0 text-uppercase text-success fw-bold">Published</p>
                @else
                    <p class="border-bottom border-3 py-3 mb-0 text-uppercase text-danger fw-bold">To publish</p>
                @endif --}}




                @foreach ($house->sponsorships as $sponsorship)
                    <div class="p-0 text-center border-bottom border-3 px-2 py-3">
                        <p class="mb-0">Sponsorizzazione: <strong> {{ $sponsorship->name }}</strong></p>
                        @if (count($house->sponsorships) <= 3)
                            @if ($sponsorship->name === 'Gold')
                                <img class="w-25" src="{{ asset('/images/gold.png') }}" alt="">
                            @elseif ($sponsorship->name === 'Silver')
                                <img class="w-25" src="{{ asset('/images/silver.png') }}" alt="">
                            @elseif ($sponsorship->name === 'Bronze')
                                <img class="w-25" src="{{ asset('/images/bronze.png') }}" alt="">
                            @endif
                        @endif
                    </div>
                @endforeach









                <div class="border-bottom border-3 py-3">

                    <p class="mb-0">Servizi:</p>
                    <ul>
                        @foreach ($house->services as $service)
                            <li>{{ $service->name }}</li>
                        @endforeach
                    </ul>
                </div>
                <div id="map-div"></div>

                <div class="text-center pt-3">
                    <a href="{{ route('admin.houses.edit', $house->id) }}" class="w-50 btn btn-warning text-uppercase"
                        type="button">Edit</a>
                </div>

                <div class="text-center pt-3">
                    <form class=" mx-auto" action="{{ route('admin.houses.destroy', $house->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-50 btn btn-danger text-uppercase delete"
                            @@click="openModalShow($event, {{ $house->id }})">Elimina</button>
                    </form>

                </div>
            </div>
        </div>
        @push('head')
            <!-- Styles -->
            <script src="{{ asset('js/map.js') }}" defer></script>
        @endpush

    </div>
@endsection
