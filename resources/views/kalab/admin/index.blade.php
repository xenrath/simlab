@extends('layouts.app')

@section('title', 'Data Admin')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Admin</h1>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Data Admin</h4>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th>Nama Lengkap</th>
                    <th class="text-center" width="240">Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($users as $key => $user)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-wrap">{{ $user->nama }}</td>
                    <td class="text-center">
                      <a href="{{ url('kalab/admin/' . $user->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> Detail
                      </a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" class="text-center">- Data tidak ditemukan -</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection