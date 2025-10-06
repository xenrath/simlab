@if (auth()->user()->isBidan())
    @include('layouts.menu.peminjam.bidan')
@endif
@if (auth()->user()->isPerawat())
    @include('layouts.menu.peminjam.perawat')
@endif
@if (auth()->user()->isK3())
    @include('layouts.menu.peminjam.k3')
@endif
@if (auth()->user()->isFarmasi())
    @include('layouts.menu.peminjam.farmasi')
@endif
