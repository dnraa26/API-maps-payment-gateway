<x-app-layout>
    <x-slot name="header">
        <h2>
            {{ __('Dashboard') }}
        </h2>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm mb-2" href="{{ route('products.index') }}">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </x-slot>
    <x-slot name="app_asset">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    </x-slot>

    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        <input type="text" name="name" value="{{ $product->name }}" class="form-control" placeholder="Name">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Detail:</strong>
                        <textarea class="form-control" style="height:150px" name="detail" placeholder="Detail">{{ $product->detail }}</textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Harga:</strong>
                        <input type="number" name="price" class="form-control" value="{{ $product->price }}" placeholder="Price">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="form-control" 
                               value="{{ $product->latitude }}" readonly required>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" id="longitude" name="longitude" class="form-control" 
                               value="{{ $product->longitude }}" readonly required>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 pt-2">
                    <div id="map" style="height: 250px;"></div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-sm mb-2 mt-2">
                        <i class="fa-solid fa-floppy-disk"></i> Submit
                    </button>
                </div>
            </div>
        </form>
    </div>

    <x-slot name="page_script">
        <script>
            // Inisialisasi peta
            var map = L.map('map').setView([
                {{ $product->latitude ?? -6.200000 }},
                {{ $product->longitude ?? 106.816666 }}
            ], 13);

            var marker;

            // Tambahkan tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Tambahkan marker default jika data produk tersedia
            if ("{{ $product->latitude }}" && "{{ $product->longitude }}") {
                marker = L.marker([{{ $product->latitude }}, {{ $product->longitude }}]).addTo(map);
            }

            // Dapatkan lokasi pengguna (fallback jika produk tidak memiliki koordinat)
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;

                    // Pindahkan view jika tidak ada data produk
                    if (!"{{ $product->latitude }}" || !"{{ $product->longitude }}") {
                        map.setView([latitude, longitude], 13);
                    }
                },
                (error) => {
                    alert('Error mendapatkan lokasi Anda: ' + error.message);
                }
            );

            // Event klik pada peta
            map.on('click', function(e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;

                // Perbarui form latitude dan longitude
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                // Tambahkan atau pindahkan marker
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
            });
        </script>
    </x-slot>
</x-app-layout>
