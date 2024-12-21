<x-app-layout>
    <x-slot name="header">
        <h2>
            {{ __('Dashboard') }}
        </h2>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
        </div>
    </x-slot>
    <x-slot name="app_asset">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    {{ $product->name }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Details:</strong>
                    {{ $product->detail }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Price:</strong>
                    {{ $product->price }}
                </div>
            </div>
        </div>
        <div id="map" style="height: 250px;"></div>
    </div>
    <x-slot name="page_script">
        <script>
            // Inisialisasi peta
            var map = L.map('map').setView([{{$product->latitude}}, {{$product->longitude}}], 13);

            // Tambahkan tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Tambahkan marker untuk lokasi produk
            var productMarker = L.marker([{{$product->latitude}}, {{$product->longitude}}])
                .addTo(map)
                .bindPopup('<b>{{ $product->name }}</b><br>{{ $product->detail }}')
                .openPopup();

          
        </script>
    </x-slot>
</x-app-layout>
