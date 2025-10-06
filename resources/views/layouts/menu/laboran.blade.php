@if (auth()->user()->isBidan())
    @include('layouts.menu.laboran.bidan')
@endif
@if (auth()->user()->isPerawat())
    @include('layouts.menu.laboran.perawat')
@endif
@if (auth()->user()->isK3())
    @include('layouts.menu.laboran.k3')
@endif
@if (auth()->user()->isFarmasi())
    @include('layouts.menu.laboran.farmasi')
@endif
